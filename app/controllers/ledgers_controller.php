<?php
class LedgersController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Ledgers';

	function index() {
		$this->autoRender = false;
		$d = new Dispatcher();
		
		if (isset($this->params['form']['Submit'])) {
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
					
				case 'View':
					$d->dispatch(
									array('controller' => 'ledgers', 'action' => 'view'),
									array('data' => $this->data)
								);
					break;
			}
		}
		else {
			$this->redirect(array('controller' => 'ledgers', 'action' => 'view'));
		}
	}
	
	
	//view ledger for this month
	function view() {
		$this->dropdownchoices();
		
		if (empty($this->data)) {
			$date = date('Y-m-d');
			$fund = $this->Ledger->Fund->find('first', array('fields'=>array('Fund.id'),'order'=>array('Fund.fund_name')));
		
			$this->set('ledgers', $this->Ledger->find('all', array( 'conditions'=>array( 'Ledger.fund_id =' => $fund['Fund']['id'], 
																						'Ledger.ledger_date =' => $date,
																						'Ledger.act =' => 1), 
																	'order'=>'Ledger.account_id ASC, Ledger.trade_date ASC')));
		}
		else {
			$date = $this->data['Ledger']['account_date'];
			$fund = $this->data['Ledger']['fund_id'];
			if (!isset($this->data['Ledger']['account_id'])) {
				$account = '%';
			}
			else {
				$account = $this->data['Ledger']['account_id'];
				if ($account == 0) { $account = '%'; }
			}
			
			$this->set('ledgers', $this->Ledger->find('all', array( 'conditions'=>array( 'Ledger.fund_id =' => $fund,
																						'Ledger.ledger_date =' => $date, 
																						'Ledger.account_id LIKE ' => $account,
																						'Ledger.act =' => 1), 
																	'order'=>'Ledger.account_id ASC, Ledger.trade_date ASC')));
		}
	}
	
	
	//Post trade journal entries to the general ledger
	function post() {
		$this->dropdownchoices();
		
		$date = $this->data['Ledger']['account_date'];
		$fund = $this->data['Ledger']['fund_id'];
		
		//check to see if this month is locked
		if ($this->Ledger->islocked($fund, $date)) {
			$this->Session->setFlash('Sorry, this month end is locked from further changes.');
		}
		else {
			$this->set('posts', $this->Ledger->post($fund, $date));
		}
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
		$this->set('accounts', array('0'=>'All Books') + $this->Ledger->Account->find('list', array('fields'=>array('Account.id','Account.account_name'),'order'=>array('Account.account_name'))));
	}
}
?>