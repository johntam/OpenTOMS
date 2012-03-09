<?php

class PositionReport extends AppModel {
    var $name = 'PositionReport';
	var $belongsTo ='Fund, Currency, Sec, Account';
	
	
	//combine latest trades with the last recorded locked balance (from the Balance model)
	function getPositions($fund, $date) {
		App::import('model','Balance');
		$balmodel = new Balance();
		$baldata = $balmodel->attachprices($fund, $date);
		
		//need to segregate all cash items together
		//ignore account books, that is for the NAV report
		$temp = array();
		foreach ($baldata as $b) {
			$temp[$b['Sec']['id']][] = $b;
		}
		
		$display = array();
		foreach ($temp as $secid=>$t) {
			$totqty = 0;
			foreach ($t as $line) {
				$totqty += $line['Balance']['balance_quantity'];
				if ($line['Account']['id'] > 1) {
					//check to see if this is a cash-type account
				
				}
			
			
			
			}
		}
		
		echo debug($temp);
		return;
		

	}
}

?>