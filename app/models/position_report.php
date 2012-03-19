<?php

class PositionReport extends AppModel {
    var $name = 'PositionReport';
	var $belongsTo ='Fund, Currency, Sec';
	
	
	//combine latest trades with the last recorded locked balance (from the Balance model)
	function getPositions($fund, $date) {
		App::import('model','Balance');
		$balmodel = new Balance();
		$baldata = $balmodel->attachprices($fund, $date);
		if ($balmodel->islocked($fund, $date)) {
			$final = 1;
		}
		else {
			$final = 0;
		}
		
		//check to see if there are any missing prices or fx rates
		foreach ($baldata as $b) {
			if ((empty($b['Price']['price']) || empty($b['PriceFX']['fx_rate'])) && ($b['Balance']['balance_quantity'] <> 0)) {
				$missing = true;
			}
		}
		if (isset($missing)) {
			return (array(false, 'Missing prices and fx rates for this date, operation aborted'));
		}
		
		//deactivate all previous position reports for this date
		if (!$this->updateAll(array('PositionReport.act' => 0), 
									array('PositionReport.pos_date =' => $date,
										  'PositionReport.fund_id =' => $fund))) {
			return (array(false, 'Could not access database, operation aborted'));
		}
		
		//need to segregate all cash items together
		//ignore account books, that is for the NAV report
		$temp = array();
		foreach ($baldata as $b) {
			$temp[$b['Sec']['id']][] = $b;
		}
		
		$display = array();
		$timenow = date('Y-m-d H:i:s');
		foreach ($temp as $secid=>$s) {
			$totqty = 0;
			foreach ($s as $c) {
				$secid = $c['Sec']['id'];
				$sectype = $c['Sec']['sec_type_id'];
				$totqty += $c['Balance']['balance_quantity'];
				$price = $c['Price']['price'];
				$ccy = $c['Balance']['currency_id'];
				$fxrate = $c['PriceFX']['fx_rate'];
				$valpoint = $c['Sec']['valpoint'];
				$cfd = $c['SecType']['cfd'];
				$trinv = $c['Balance']['trinv'];
			}
			if ($cfd == 0) {
				$mvlocal = $totqty * $price * $valpoint;
				$mvusd = $mvlocal * $fxrate;
			}
			else {
				$closeout = strtotime('now').':'.(-$totqty).':'.$price.':'.$valpoint.';';
				list($pnl, $trinv_out) = $balmodel->fifo($trinv, $closeout);
				if (!empty($trinv_out)) {
					return (array(false, 'Problem with FIFO calculation, operation aborted '));
				}
				$mvlocal = $pnl;
				$mvusd = $pnl * $fxrate;				
			}
			
			//save this record to the database
			$data['PositionReport'] = array('act' => 1,
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
											'mkt_val_local'=>$mvlocal,
											'mkt_val_usd'=>$mvusd);
			$this->create();
			$this->save($data);
		}
		
		return array(true, null);
	}
}

?>