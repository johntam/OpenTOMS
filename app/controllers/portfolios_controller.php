<?php
class PortfoliosController extends AppController {
	var $name = 'Portfolios';
	var $fund_id;
	var $trades;
	
	
	//create an array of trades relevant to this portfolio
	function get_trades($start_date, $end_date) {
	
		$params=array(
			'conditions' => array('Trade.fund_id =' => $fund_id, 'Trade.trade_date >=' => $start_date, 'Trade.trade_date <=' => $end_date, 'Trade.cancelled <>' => 1), //array of conditions
			//'fields' => array('Model.field1', 'DISTINCT Model.field2'), //array of field names
			'order' => array('Trade.crd DESC') //string or array defining order
			//'group' => array('Model.field'), //fields to GROUP BY
		);
		
		$trades = $this->Portfolio->Trade->find('all', $params);
	}
	
	function index() {
		$this->set('trades', $trades);
	}
}

?>