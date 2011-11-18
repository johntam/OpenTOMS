<?php

class Portfolio extends AppModel {
    var $name = 'Portfolio';
	var $belongsTo = 'Trade';
	var $actsAs = array('Containable');
	
	//create an array of trades relevant to this portfolio
	function get_trades($fund_id,$start_date, $end_date) {
		$params=array(
			'conditions' => array(  'Trade.fund_id =' => $fund_id, 
									'Trade.trade_date >=' => $start_date, 
									'Trade.trade_date <=' => $end_date, 
									'Trade.cancelled <>' => 1,
									'Trade.executed =' => 1,
									'Trade.act =' => 1),
			'order' => array('Trade.trade_date DESC'),
			'contain' => false,
			'fields' => array('Sec.sec_name','SUM(Trade.quantity) AS quantity'),
			'group' => array('Sec.sec_name')
		);
		
		return $this->Trade->find('all', $params);
	}
}

?>