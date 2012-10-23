<?php
/*
	OpenTOMS - Open Trade Order Management System
	Copyright (C) 2012  JOHN TAM, LPR CONSULTING LLP

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

class ValuationReport extends AppModel {
    var $name = 'ValuationReport';
	var $belongsTo ='Fund, Currency, Sec';
	
	
	//combine latest trades with the last recorded locked balance (from the Balance model)
	function getValuation($fund, $date) {
		App::import('model','Balance');
		$balmodel = new Balance();
		$baldata = $balmodel->attachprices($fund, $date);
		if ($balmodel->islocked($fund, $date)) {
			$final = 1;
		}
		else {
			$final = 0;
		}
		
		//check to see if fx rate for fund currency to USD exists or not
		$fundccy = $this->Fund->get_fund_ccy($fund);
		App::import('model','Price');
		$pricemodel = new Price();
		if (!($fundfx = $pricemodel->get_fx($fundccy, $date))) {
			return (array(false, 'Missing fx rate for fund currency for date '.$date));
		}
		
		//check to see if there are any other missing prices or fx rates
		foreach ($baldata as $b) {
			if ((empty($b['Price']['price']) || empty($b['PriceFX']['fx_rate'])) && ($b['Balance']['balance_quantity'] <> 0)) {
				$missing = true;
			}
		}
		if (isset($missing)) {
			return (array(false, 'Missing prices and fx rates for this date, operation aborted'));
		}
		
		//deactivate all previous valuation reports for this date
		if (!$this->updateAll(array('ValuationReport.act' => 0), 
									array('ValuationReport.pos_date =' => $date,
										  'ValuationReport.fund_id =' => $fund))) {
			return (array(false, 'Could not access database, operation aborted'));
		}
		
		//need to aggregate all individual securities and cash, this will group by accounts and custodians
		$temp = array();
		foreach ($baldata as $b) {
			$temp[$b['Sec']['id']][] = $b;
		}
		
		$display = array();
		$timenow = date('Y-m-d H:i:s');
		foreach ($temp as $secid=>$s) {
			$totqty = 0;
			$notional = 0;
			$mvlocal = 0;
			$mvfund = 0;
			$accrued = 0;
			foreach ($s as $c) {
				$secid = $c['Sec']['id'];
				$sectype = $c['Sec']['sec_type_id'];
				$qty = $c['Balance']['balance_quantity'];
				$price = $c['Price']['price'];
				$ccy = $c['Balance']['currency_id'];
				$valpoint = $c['Sec']['valpoint'];
				$cfd = $c['SecType']['cfd'];
				$trinv = $c['Balance']['trinv'];
				//accrued interest
				$acc = 0;
				if ($c['Account']['id'] == 14) {
					if ($c['Balance']['balance_credit'] > 0) {
						$acc = $c['Balance']['balance_credit'];
					}
					else {
						$acc = -$c['Balance']['balance_debit'];
					}	
				}
				
				if (empty($c['Price']['fx_rate'])) {
					//normal security
					$fxrate = $c['PriceFX']['fx_rate'] / $fundfx;
				}
				else {
					//cash security
					$fxrate = $c['Price']['fx_rate'] / $fundfx;
					$ccy = $this->Currency->getCurrencyID($secid);
				}
				
				//now calculate totals
				$totqty += $qty;
				if ($cfd == 0) {
					$notional += ($qty * $price * $valpoint);
					$accrued += $acc;
					$mvlocal += ($qty * $price * $valpoint + $acc);
					$mvfund += (($qty * $price * $valpoint + $acc) * $fxrate);
				}
				else {
					$closeout = strtotime('now').':'.(-$qty).':'.$price.':'.$valpoint.';';
					list($pnl, $trinv_out) = $balmodel->fifo($trinv, $closeout);
					if (!empty($trinv_out)) {
						return (array(false, 'Problem with FIFO calculation, operation aborted '));
					}
					$notional += $pnl;
					$accrued = 0;
					$mvlocal += $pnl;
					$mvfund += ($pnl * $fxrate);
				}
			}
			
			//save this record to the database
			$data['ValuationReport'] = array('act' => 1,
											'crd'=>$timenow,
											'final'=>$final,
											'pos_date'=>$date,
											'fund_id' => $fund,
											'sec_id'=>$secid,
											'sec_type_id'=>$sectype,
											'quantity'=>$totqty,
											'price'=>$price,
											'currency_id'=>$ccy,
											'fx_rate'=>$fxrate,
											'accrued'=>$accrued,
											'notion_val_local'=>$notional,
											'mkt_val_local'=>$mvlocal,
											'mkt_val_fund'=>$mvfund);
			$this->create();
			$this->save($data);
		}
		
		return array(true, null);
	}
}

?>
