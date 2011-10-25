<?php
class TradesController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Trades';
	var $funds = array();
	
	
	function index() {		
		$this->setchoices();
		$conditions=array(
			'Trade.crd >' => date('Y-m-d', strtotime($this->data['Trade']['daterange'])),
			'Trade.fund_id =' => $this->data['Trade']['fundchosen'],
			'Trade.broker_id =' => $this->data['Trade']['brokerchosen']
		);
		
	
	
		$params=array(
			'conditions' => $conditions, //array of conditions
			//'recursive' => 1, //int
			//'fields' => array('Model.field1', 'DISTINCT Model.field2'), //array of field names
			'order' => array('Trade.crd DESC') //string or array defining order
			//'group' => array('Model.field'), //fields to GROUP BY
			//'limit' => n, //int
			//'page' => n, //int
			//'offset'=>n, //int   
			//'callbacks' => true //other possible values are false, 'before', 'after'
		);
		
		$this->set('trades', $this->Trade->find('all', $params));
		
		//echo "$this->data";
		//echo "funds=";
		//print_r($this->Trade->$funds);	// view variable not visible to controller
		
		$this->set('title_for_layout', 'View Trades');

        //$this->layout = 'default_small_ad';
		
		
	}
		
	function add() {
		$this->setchoices();
	
		if (!empty($this->data)) {
			if ($this->Trade->saveAll($this->data)) {
				$this->Session->setFlash('Your trade has been saved.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
	
	
	function edit($id = null) {
		$this->setchoices();
		$this->Trade->id = $id;
		
		if (empty($this->data)) {
			$this->data = $this->Trade->read();
		} else {
			if ($this->Trade->save($this->data)) {
				$this->Session->setFlash('Your trade has been updated.');
				$this->redirect(array('action' => 'index'));
			}
	}
}

	
	function setchoices() {
		$this->set('funds', $this->Trade->Fund->find('list', array('fields'=>array('Fund.fund_name'))));
		$this->set('secs', $this->Trade->Sec->find('list', array('fields'=>array('Sec.sec_name'))));
		$this->set('tradeTypes', $this->Trade->TradeType->find('list', array('fields'=>array('TradeType.trade_type'))));
		$this->set('reasons', $this->Trade->Reason->find('list', array('fields'=>array('Reason.reason_desc'))));
		$this->set('brokers', $this->Trade->Broker->find('list', array('fields'=>array('Broker.broker_name'))));
		$this->set('traders', $this->Trade->Trader->find('list', array('fields'=>array('Trader.trader_name'))));
		
		//if ($mode==1) {
		//	array_unshift($brokers,"All","All");
		//};
	}
}

?>