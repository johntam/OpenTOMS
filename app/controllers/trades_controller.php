<?php
class TradesController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Trades';
	
	function index() {
		$this->set('trades', $this->Trade->find('all'));
	}
	
	function add() {
		$this->set('funds', $this->Trade->Fund->find('list', array('fields'=>array('Fund.fund_name'))));
		$this->set('secs', $this->Trade->Sec->find('list', array('fields'=>array('Sec.sec_name'))));
		$this->set('tradeTypes', $this->Trade->TradeType->find('list', array('fields'=>array('TradeType.trade_type'))));
		$this->set('reasons', $this->Trade->Reason->find('list', array('fields'=>array('Reason.reason_desc'))));
		$this->set('brokers', $this->Trade->Broker->find('list', array('fields'=>array('Broker.broker_name'))));
		$this->set('traders', $this->Trade->Trader->find('list', array('fields'=>array('Trader.trader_name'))));
	
		if (!empty($this->data)) {
			if ($this->Trade->saveAll($this->data)) {
				$this->Session->setFlash('Your trade has been saved.');
				$this->redirect(array('action' => 'index'));
			}
		} 
	}
}
?>