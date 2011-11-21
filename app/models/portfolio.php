<?php

class Portfolio extends AppModel {
    var $name = 'Portfolio';
	var $belongsTo = 'Trade';
	var $actsAs = array('Containable');
	var $portfolio;
	var $port_type;
	var $fund_id;
	var $start_date;
	var $end_date;
	
	
	//create an array of trades relevant to this portfolio
	function get_trades() {
		$params=array(
			'conditions' => array(  'Trade.fund_id =' => $this->fund_id, 
									'Trade.trade_date >=' => $this->start_date, 
									'Trade.trade_date <=' => $this->end_date, 
									'Trade.cancelled <>' => 1,
									'Trade.executed =' => 1,
									'Trade.act =' => 1),
			'order' => array('Trade.trade_date DESC'),
			'contain' => false,
			'fields' => array('SUM(Trade.quantity) AS quantity','Sec.sec_name','Sec.id'),
			'group' => array('Sec.sec_name','Sec.id')
		);
		
		$this->portfolio = $this->Trade->find('all', $params);
		return($this->portfolio);
	}
	
	//save this portfolio to the portfolios table
	function save_portfolio() {
		foreach ($this->portfolio as $p) {
			$this->create(array('Portfolio' => array(   'crd'=>DboSource::expression('NOW()'),
														'report_id'=>$this->report_id,
														'portfolio_type'=>$this->port_type,
														'run_date'=>$this->end_date,
														'fund_id'=>$this->fund_id,
														'sec_id'=>$p['Sec']['id'], 
														'position'=>$p['0']['quantity'])));
			$this->save();
		}
	}
}

?>