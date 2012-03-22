<?php

class Holding extends AppModel {
    var $name = 'Holding';
	var $useTable = 'trades';
	var $belongsTo ='Fund, Sec';
	var $actsAs = array('Containable');
	
	//get the holdings as at this date
	function getHoldings($fund, $date) {
		$holdings = $this->find('all', array('conditions'=>array('Holding.fund_id =' => $fund,
																 'Holding.trade_date <=' => $date,
																 'Holding.cancelled =' => 0,
																 'Holding.executed =' => 1,
																 'Holding.act =' => 1),
											 'fields'=>array('Sec.sec_name', 'SUM(Holding.quantity) AS quantity'),
											 'group'=>array('Sec.id', 'Sec.sec_name HAVING quantity <> 0'),
											 'order'=>array('Sec.sec_type_id="2" ASC', 'Sec.sec_name ASC'),
											 'contain' => array('Sec')));		
		return $holdings;
	}
}

?>