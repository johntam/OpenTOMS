<?php
class JournalsController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Journals';

	function index() {
		if (!empty($this->data)) {
			//save data to trades table, but add other fields first
			$this->completeTradeDetails();
			
			//save
			if (!$this->Journal->save($this->data)) {
				$this->Session->setFlash('Problem with saving data, please try again');
			}
		} 
		$this->dropdownchoices();
	}
	
	
	
	function edit($id) {
		$this->autoRender = false;
		$this->completeTradeDetails();
	}
	
	
	
	function delete($id) {
		$this->autoRender = false;
	}
	
	
	//get list of previous journal entries
	function ajax_history() {	
		$data = $this->Journal->find('all', array('conditions'=> array('Journal.trade_type_id =' => $this->params['data']['Journal']['trade_type_id'],
																	   'Journal.fund_id =' => $this->params['data']['Journal']['fund_id'],
																	   'Journal.cancelled =' => 0),
												  'order' => array('Journal.trade_date DESC')));
		$this->set('journals', $data);
		$this->render('/elements/ajax_journal_history', 'ajax');
	}
	
	
	function completeTradeDetails() {
		$this->data['Journal']['act'] = 1;
		$this->data['Journal']['sec_id'] = $this->Journal->Currency->getsecid($this->data['Journal']['currency_id']);
		$this->data['Journal']['trade_date'] = $this->data['Journal']['account_date'];
		$this->data['Journal']['settlement_date'] = $this->data['Journal']['account_date'];
		$this->data['Journal']['cancelled'] = 0;
		$this->data['Journal']['executed'] = 1;
		$this->data['Journal']['execution_price'] = 1;
		if ($this->Journal->TradeType->read('debit_account_id', $this->data['Journal']['trade_type_id']) == 2) {
			//this is an income
			$this->data['Journal']['consideration'] = abs($this->data['Journal']['quantity']);
		}
		else {
			//this is an expense
			$this->data['Journal']['consideration'] = -abs($this->data['Journal']['quantity']);
		}
	}
	
	//choices for dropdown lists
	function dropdownchoices() {
		$this->set('funds', $this->Journal->Fund->find('list', array('fields'=>array('Fund.id','Fund.fund_name'),'order'=>array('Fund.fund_name'))));
		$this->set('tradeTypes', $this->Journal->TradeType->find('list', array('conditions'=>array('TradeType.category ='=>'Non-trading'),'fields'=>array('TradeType.trade_type'),'order'=>array('TradeType.id'))));
		$this->set('currencies', $this->Journal->Currency->find('list', array('fields'=>array('Currency.currency_iso_code'),'order'=>array('Currency.currency_iso_code'))));
	}
}
?>