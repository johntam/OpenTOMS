<?php
class TradesController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Trades';
	var $funds = array();
	
	
	function index() {
		$this->setchoices();
		$conditions=array(
			'Trade.crd >' => strtotime('-1 week'),
			'Trade.act =' => 1
		);
	
		$params=array(
			'conditions' => $conditions, //array of conditions
			'order' => array('Trade.crd DESC') //string or array defining order
		);
		
		$this->set('trades', $this->Trade->find('all', $params));
		$this->set('title_for_layout', 'View Trades');
	}
	
	
	
	function indexFiltered() {		
		$this->setchoices();
		$conditions=array(
			'Trade.crd >' => date('Y-m-d', strtotime($this->data['Trade']['daterange'])),
			'Trade.fund_id =' => $this->data['Trade']['fundchosen'],
			'Trade.broker_id =' => $this->data['Trade']['brokerchosen'],
			'Trade.act =' => 1
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
		$this->set('title_for_layout', 'View Trades');
	}
	
	
	
	function add() {
		$this->setchoices();
	
		if (!empty($this->data)) {
			if ($this->Trade->save($this->data)) {
				//Do a second update to the same record to set the oid and act fields
				$id = $this->Trade->id;
				if ($this->Trade->saveField('act',1) && $this->Trade->saveField('oid',$id)) {
					$this->Session->setFlash('Your trade has been saved.');
					$this->redirect(array('action' => 'add'));
				}
			}
		}
	}
	
	
	
	function edit($id = null) {
		$this->setchoices();
		$this->Trade->id = $id;
		
		if (empty($this->data)) {
			$this->data = $this->Trade->read();
		} else {
			$id = $this->Trade->id;
			unset($this->data['Trade']['id']);	//remove id so that Cake will create a new model record
			$this->data['Trade']['act'] = 1;
			$this->data['Trade']['crd'] = DboSource::expression('NOW()');	//weird DEFAULT TIMESTAMP not working
			$this->Trade->create();
			
			if ($this->Trade->save($this->data)) {
				$this->Trade->id = $id;
				//Clear the active flag on the trade that was edited
				if ($this->Trade->saveField('act',0)) {
					$this->Session->setFlash('Your trade has been updated.');
					$this->redirect(array('action' => 'index'));
				}
			}
		}
		
	}
	
	
	function view($oid = null) {
		$conditions=array(
			'Trade.oid =' => $oid
		);
	
		$params=array(
			'conditions' => $conditions, //array of conditions
			'limit' => 1,
			'order' => array('Trade.crd DESC') //string or array defining order
		);
			
		$this->paginate = $params;
		$data=$this->paginate('Trade');
		$this->set(compact('data'));
	}
	
	function setchoices() {
		//Could be a lot of securities so cache this list
		if (($secsCACHE = Cache::read('secs')) === false) {
			$secsCACHE = $this->Trade->Sec->find('list', array('fields'=>array('Sec.sec_name'),'order'=>array('Sec.sec_name')));
			Cache::write('secs', $secsCACHE);
		}

		$this->set('secs', $secsCACHE);
		$this->set('funds', $this->Trade->Fund->find('list', array('fields'=>array('Fund.fund_name'),'order'=>array('Fund.fund_name'))));
		$this->set('tradeTypes', $this->Trade->TradeType->find('list', array('fields'=>array('TradeType.trade_type'),'order'=>array('TradeType.trade_type'))));
		$this->set('reasons', $this->Trade->Reason->find('list', array('fields'=>array('Reason.reason_desc'),'order'=>array('Reason.reason_desc'))));
		$this->set('brokers', $this->Trade->Broker->find('list', array('fields'=>array('Broker.broker_name'),'order'=>array('Broker.broker_name'))));
		$this->set('traders', $this->Trade->Trader->find('list', array('fields'=>array('Trader.trader_name'),'order'=>array('Trader.trader_name'))));
		$this->set('currencies', $this->Trade->Currency->find('list', array('fields'=>array('Currency.currency_iso_code'),'order'=>array('Currency.currency_iso_code'))));
	}
}

?>