<?php

class Balance extends AppModel {
    var $name = 'Balance';
	var $belongsTo ='Account, Fund, Currency, Sec';
	
	//calculate the month end balances, using the last month end balances and this month's general ledger
	function monthend($fund, $month, $year) {
		//first get the end of last month's balances
		$lm = $month - 1;
		$ly = $year;
		if ($lm == 0) {
			$lm = 12;
			$ly = $ly - 1;
		}
		
		//get last month end balances
		$lmdata = $this->find('all', array('conditions'=>array('act =' => 1, 'ledger_month =' => $lm, 'ledger_year =' => $ly, 'fund_id =' => $fund)));
		
		//get this month's ledger entries
		App::import('model','Ledger');
		$ledger = new Ledger();
		$ldata = $ledger->find('all', array('conditions'=>array('act =' => 1, 'ledger_month =' => $lm, 'ledger_year =' => $ly, 'fund_id =' => $fund)));
		
		
	}
}

?>