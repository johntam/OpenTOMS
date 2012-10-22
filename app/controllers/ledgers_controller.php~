<?php
class LedgersController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Ledgers';

	function index($fund_in = false, $date_in = false) {
		$this->autoRender = false;
		$d = new Dispatcher();
		
		if (isset($this->params['form']['Submit'])) {
			$this->Session->write('fund_chosen', $this->data['Ledger']['fund_id']);
			
			switch ($this->params['form']['Submit']) {
				case 'Post':
					$d->dispatch(
									array('controller' => 'ledgers', 'action' => 'post'),
									array('data' => $this->data)
								);
					break;
				
				case 'Create':
					$d->dispatch(
									array('controller' => 'ledgers', 'action' => 'create'),
									array('data' => $this->data)
								);
					break;
					
				default:
					$d->dispatch(
									array('controller' => 'ledgers', 'action' => 'view'),
									array('data' => $this->data)
								);
			}
		}
		else if (isset($this->params['form']['Backdate_x'])) {
			$prevdate = $this->Ledger->getPrevPostDate($this->data['Ledger']['fund_id'], $this->data['Ledger']['account_date']);
			if (!empty($prevdate)) { $this->data['Ledger']['account_date'] = $prevdate; }
			$d->dispatch(array('controller' => 'ledgers', 'action' => 'view'),
								 array('data' => $this->data));
		}
		else if (isset($this->params['form']['Nextdate_x'])) {
			$nextdate = $this->Ledger->getNextPostDate($this->data['Ledger']['fund_id'], $this->data['Ledger']['account_date']);
			if (!empty($nextdate)) { $this->data['Ledger']['account_date'] = $nextdate; }
			$d->dispatch(array('controller' => 'ledgers', 'action' => 'view'),
								 array('data' => $this->data));
		}
		else {
			//page just loaded, try if possible to use whatever fund was chosen on the trade blotter as the default fund choice on this page.
			if ($fund_in) {
				$fund = $fund_in;
				$this->Session->write('fund_chosen', $fund_in);
			}
			else if ($this->Session->check('fund_chosen')) {
				$fund = $this->Session->read('fund_chosen');
			}
			else {
				$fund = $this->Ledger->Fund->find('first', array('fields'=>array('Fund.id'),'order'=>array('Fund.fund_name')));
				$fund = $fund['Fund']['id'];
			}
			
			if ($date_in) {
				$date = $date_in;
			}
			else {
				$date = $this->Ledger->getPrevPostDate($fund, date('Y-m-d', strtotime('tomorrow')));
				if (empty($date)) {
					$date = date('Y-m-d');
				}
			}
			
			$this->data = array('Ledger' => array('fund_id'=>$fund, 'account_date'=>$date));
			$d->dispatch(array('controller' => 'ledgers', 'action' => 'view'),
								 array('data' => $this->data));
		}
	}
	
	
	//view ledger for this month
	function view() {
		$this->dropdownchoices();
		
		if (empty($this->data)) {
			$date = date('Y-m-d');
			$fund = $this->Ledger->Fund->find('first', array('fields'=>array('Fund.id'),'order'=>array('Fund.fund_name')));
			$fund = $fund['Fund']['id'];
		}
		else {
			$date = $this->data['Ledger']['account_date'];
			$fund = $this->data['Ledger']['fund_id'];
		}
		
		$this->set('ledgers', $this->Ledger->find('all', array( 'fields'=>array('Fund.fund_name',
																				'Account.account_name',
																				'Custodian.custodian_name',
																				'Ledger.trade_date',
																				'Ledger.ledger_debit',
																				'Ledger.ledger_credit',
																				'Currency.currency_iso_code',
																				'Sec.sec_name',
																				'Ledger.ledger_quantity',
																				'Trade.oid'),
																'conditions'=>array('Ledger.fund_id =' => $fund,
																					'Ledger.ledger_date =' => $date,
																					'Ledger.act =' => 1,
																					'Ledger.sec_id >'=> 0), 
																'order'=>array('Ledger.custodian_id' => 'ASC', 'Ledger.account_id' => 'ASC', 'Ledger.trade_date' => 'ASC'))));
	}
	
	
	//Post trade journal entries to the general ledger
	function post() {
		$date = $this->data['Ledger']['account_date'];
		$fund = $this->data['Ledger']['fund_id'];
		
		App::import('model','Balance');
		$bal = new Balance();
		$lastbaldate = $bal->getPrevLockedDate($fund);
		if (empty($lastbaldate)) { $lastbaldate = '1999-12-31'; }
		
		//check to see if this month is locked
		if ($bal->islocked($fund, $date)) {
			$this->Session->setFlash('Sorry, this month end is locked from further changes');
		}
		else if (strtotime($date) < strtotime($lastbaldate)) {
			$this->Session->setFlash('Sorry, cannot post prior to the last locked date');
		}
		else {
			$this->Ledger->post($fund, $date);
		}
		
		//redirect to view action, whilst passing $this->data
		$this->autoRender = false;
		$d = new Dispatcher();
		$d->dispatch(
			array('controller' => 'ledgers', 'action' => 'view'),
			array('data' => $this->data)
		);
	}
	
	//create new general ledger. This is a very destructive action which scrubs all month end balances. This is why this action has its own page with a big warning on it.
	function create() {
		$this->dropdownchoices();
		
		if (isset($this->params['form']['Submit'])) {
			if ($this->params['form']['Submit'] == 'Yes') {
				//do it and stand back, they were warned!
				if (!empty($this->data)) {
					App::import('model','Balance');
					$bal = new Balance();
					$bal->wipe($this->data['Ledger']['fund_id']);
					
					$this->Ledger->wipe($this->data['Ledger']['fund_id']);
					
					$this->Ledger->post($this->data['Ledger']['fund_id'],
										$this->data['Ledger']['account_date']);
										 
					$this->Session->setFlash('First ledger has now been created for this fund.');
				}
			}
			
			//redirect to view action, whilst passing $this->data
			$this->autoRender = false;
			$d = new Dispatcher();
			$d->dispatch(
				array('controller' => 'ledgers', 'action' => 'view'),
				array('data' => $this->data)
			);
		}
	}
	
	//Get list of fund names and accounting book names for the dropdown lists
	function dropdownchoices() {
		$this->set('funds', $this->Ledger->Fund->find('list', array('fields'=>array('Fund.id','Fund.fund_name'),'order'=>array('Fund.fund_name'))));
	}
}
?>