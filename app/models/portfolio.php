<?php
/* There are different classes of portfolio handled by this class
	port_type	description					used by
	stock		securities only				positions report, nav report
	cash		currencies only				nav report
*/
class Portfolio extends AppModel {
    var $name = 'Portfolio';
	//var $belongsTo = 'Trade';
	var $actsAs = array('Containable');
	var $portfolio;
	var $port_type;
	var $fund_id;
	var $start_date;
	var $end_date;
	var $prev_report_id;
	var $calc_start_date;
	
	
	
	
	//create an array of trades relevant to this portfolio
	function get_trades() {
		$this->loadModel('Trade');
		$params=array(
			'conditions' => array(  'Trade.fund_id =' => $this->fund_id, 
									'Trade.trade_date >' => $this->start_date, 
									'Trade.trade_date <=' => $this->end_date, 
									'Trade.cancelled <>' => 1,
									'Trade.executed =' => 1,
									'Trade.act =' => 1),
			'order' => array('Trade.trade_date DESC'),
			'contain' => false,
			'fields' => array('SUM(Trade.quantity) AS quantity','Sec.sec_name','Sec.id'),
			'group' => array('Sec.sec_name','Sec.id')
		);
		
		return($this->find('all', $params));
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
														'sec_name'=>$p['Sec']['sec_name'], 
														'position'=>$p['0']['quantity'])));
			$this->save();
		}
	}
}

?>