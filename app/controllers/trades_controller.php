<?php
App::Import('Model','Fund');
App::Import('Model','Sec');
App::Import('Model','TradeType');
App::Import('Model','Reason');
App::Import('Model','Broker');
App::Import('Model','Trader');

class TradesController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Trades';
	
	function index() {
		$this->set('trades', $this->Trade->find('all'));
	}
	
	function add() {
		$fund	= new Fund();
		$sec = new Sec();
		$trade_type = new TradeType();
		$reason = new Reason();
		$broker = new Broker();
		$trader = new Trader();
		
		$this->set('funds', $fund->find('list', array('fields'=>array('Fund.fund_name'))));
		$this->set('secs', $sec->find('list', array('fields'=>array('Sec.sec_name'))));
		$this->set('tradetypes', $trade_type->find('list', array('fields'=>array('TradeType.trade_type'))));
		$this->set('reasons', $reason->find('list', array('fields'=>array('Reason.reason_desc'))));
		$this->set('brokers', $broker->find('list', array('fields'=>array('Broker.broker_name'))));
		$this->set('traders', $trader->find('list', array('fields'=>array('Trader.trader_name'))));
	
		if (!empty($this->data)) {
			if ($this->Trade->save($this->data)) {
				$this->Session->setFlash('Your trade has been saved.');
				$this->redirect(array('action' => 'index'));
			}
		} 
	}
}
?>