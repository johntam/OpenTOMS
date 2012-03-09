<?php

class PositionReport extends AppModel {
    var $name = 'PositionReport';
	var $belongsTo ='Fund, Currency, Sec';
	
	
	//combine latest trades with the last recorded locked balance (from the Balance model)
	function getPositions($fund, $date) {
		//deactivate all previous position reports for this date
		if (!$this->updateAll(array('PositionReport.act' => 0), 
									array('PositionReport.pos_date =' => $date,
										  'PositionReport.fund_id =' => $fund))) {
			$this->Session->setFlash('Could not access database, operation aborted');
			return false;
		}
	
		App::import('model','Balance');
		$balmodel = new Balance();
		$baldata = $balmodel->attachprices($fund, $date);
		if ($balmodel->islocked($fund, $date)) {
			$final = 1;
		}
		else {
			$final = 0;
		}
		
		//need to segregate all cash items together
		//ignore account books, that is for the NAV report
		$temp = array();
		foreach ($baldata as $b) {
			$temp[$b['Sec']['id']][] = $b;
		}
		
		$display = array();
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
			}
			$mvlocal = $totqty * $price * $valpoint;
			$mvusd = $mvlocal * $fxrate * $valpoint;
			
			//save this record to the database
			$data['PositionReport'] = array('act' => 1,
											'crd'=>DboSource::expression('NOW()'),
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
		
		return true;
	}
}

?>