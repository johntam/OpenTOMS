<?php
class JournalsController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Journals';

	function index() {
		if (!empty($this->data)) {
			//save data to trades table, but add other fields first
			$this->completeTradeDetails();
			
			//save
			list($done, $msg) = $this->saveJournal($this->data);
			if (!$done) {
				$this->Session->setFlash($msg);
			}
		}
		else {
			if ($this->Session->check('fund_chosen')) {
				$fund = $this->Session->read('fund_chosen');
			}
			else {
				$fund = $this->Journal->Fund->find('first', array('fields'=>array('Fund.id'),'order'=>array('Fund.fund_name')));
				$fund = $fund['Fund']['id'];
			}
			$this->data['Journal']['fund_id'] = $fund;
		}
		$this->dropdownchoices();
	}
	
	
	function edit() {
		$date = $this->params['form']['date'];
		$qty = $this->params['form']['quantity'];
		$id = $this->params['form']['id'];
		$notes = $this->params['form']['notes'];
		
		$this->data = $this->Journal->read(null, $id);
		$this->data['Journal']['crd'] = DboSource::expression('NOW()');
		$this->data['Journal']['trade_date'] = $date;
		$this->data['Journal']['settlement_date'] = $date;
		$sign = $this->getConsSign($this->data['Journal']['trade_type_id']);
		$this->data['Journal']['consideration'] = $sign * abs($qty);
		$this->data['Journal']['quantity'] = $sign * abs($qty);
		$this->data['Journal']['notes'] = $notes;
		
		list($done, $msg) = $this->saveJournal($this->data);
		if ($done) {
			$this->set('data','Y');
		}
		else {
			$this->set('data',$msg);
		}
		$this->render('/elements/ajax_common', 'ajax');
	}
	
	
	//save this journal, but check to see if its after the last locked balance date first
	function saveJournal($data) {
		$lockeddate = $this->getLockedDate($data['Journal']['fund_id']);
		if (strtotime($data['Journal']['trade_date']) <= strtotime($lockeddate)) {
			return (array(false, 'This posting date is before the latest locked balance date'));
		}
		else {
			if ($this->Journal->save($data)) {
				return (array(true, 'Journal saved'));
			}
			else {
				return (array(false, 'Problem with saving journal, please try again'));
			}
		}
	}
	
	
	function delete() {
		$id = $this->params['form']['id'];
		$this->Journal->read(null, $id);
		$this->data['cancelled'] = 1;
		
		if ($this->Journal->save($this->data)) {
			$this->set('data','Y');
		}
		else {
			$this->set('data','N');
		}
		$this->render('/elements/ajax_common', 'ajax');
	}
	
	
	//get list of previous journal entries
	function ajax_history() {
		$this->getLockedDate($this->params['data']['Journal']['fund_id']);
		$data = $this->Journal->find('all', array('conditions'=> array('Journal.trade_type_id =' => $this->params['data']['Journal']['trade_type_id'],
																	   'Journal.fund_id =' => $this->params['data']['Journal']['fund_id'],
																	   'Journal.cancelled =' => 0),
												  'order' => array('Journal.trade_date DESC', 'Journal.crd DESC')));
		$this->set('journals', $data);
		$this->dropdownchoices();
		$this->render('/elements/ajax_journal_history', 'ajax');
	}
	
	
	//get the last locked date from the Balance model, view displays padlocks for journal entries prior to this date
	function getLockedDate($fund_id) {
		App::import('model','Balance');
		$bal = new Balance();
		$lockeddate = $bal->getPrevLockedDate($fund_id);
		if (empty($lockeddate)) {
			$lockeddate = date('Y-m-d', strtotime('-10 years'));
		}
		$this->set('lockeddate', $lockeddate);
		return $lockeddate; 
	}
	
	
	//determine whether it should be positive or negative
	function getConsSign($tt_id) {
		$acctype = $this->Journal->TradeType->read('debit_account_id', $tt_id);
	
		if ($acctype['TradeType']['debit_account_id'] == 2) {
			//this is an income
			return 1;
		}
		else {
			//this is an expense
			return -1;
		}
	}
	
	
	function completeTradeDetails() {
		$this->data['Journal']['act'] = 1;
		$this->data['Journal']['sec_id'] = $this->Journal->Currency->getsecid($this->data['Journal']['currency_id']);
		$this->data['Journal']['trade_date'] = $this->data['Journal']['account_date'];
		$this->data['Journal']['settlement_date'] = $this->data['Journal']['account_date'];
		$this->data['Journal']['cancelled'] = 0;
		$this->data['Journal']['executed'] = 1;
		$this->data['Journal']['execution_price'] = 1;
		$sign = $this->getConsSign($this->data['Journal']['trade_type_id']);
		$this->data['Journal']['consideration'] =  $sign * abs($this->data['Journal']['quantity']);
		$this->data['Journal']['quantity'] = $sign * abs($this->data['Journal']['quantity']);
	}
	
	//choices for dropdown lists
	function dropdownchoices() {
		$this->set('funds', $this->Journal->Fund->find('list', array('fields'=>array('Fund.id','Fund.fund_name'),'order'=>array('Fund.fund_name'))));
		$this->set('tradeTypes', $this->Journal->TradeType->find('list', array('conditions'=>array('TradeType.category ='=>'Non-trading'),'fields'=>array('TradeType.trade_type'),'order'=>array('TradeType.id'))));
		$this->set('currencies', $this->Journal->Currency->find('list', array('fields'=>array('Currency.currency_iso_code'),'order'=>array('Currency.currency_iso_code'))));
		$this->set('custodians', $this->Journal->Custodian->find('list', array('fields'=>array('Custodian.custodian_name'),'order'=>array('Custodian.custodian_name'))));
	}
}
?>