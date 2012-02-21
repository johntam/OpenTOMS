<?php
class LedgersController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Ledgers';

	function index() {
		if (isset($this->params['form']['Submit'])) {
			switch ($this->params['form']['Submit']) {
				case 'Post':
					$this->Session->write('ledger_post_data', $this->data);
					$this->redirect(array('controller' => 'ledgers', 'action' => 'post'));
					break;
			}
		}
		
		//Get list of fund names and accounting book names
		$this->set('funds', $this->Ledger->Fund->find('list', array('fields'=>array('Fund.id','Fund.fund_name'),'order'=>array('Fund.fund_name'))));
		$this->set('accounts', array('0'=>'All Books') + $this->Ledger->Account->find('list', array('fields'=>array('Account.id','Account.account_name'),'order'=>array('Account.account_name'))));
		
		if (empty($this->data)) {
			$month = date('n');
			$year = date('Y');
			$fund = $this->Ledger->Fund->find('first', array('fields'=>array('Fund.id'),'order'=>array('Fund.fund_name')));
			
		
			$this->set('ledgers', $this->Ledger->find('all', array( 'conditions'=>array( 'Ledger.fund_id =' => $fund['Fund']['id'], 
																						'Ledger.ledger_month =' => $month, 
																						'Ledger.ledger_year =' => $year, 
																						'Ledger.act =' => 1), 
																	'order'=>'Ledger.account_id ASC, Ledger.ledger_date ASC')));
		}
		else {			
			$month = $this->data['Ledger']['accounting_period']['month'];
			$year = $this->data['Ledger']['accounting_period']['year'];
			$fund = $this->data['Ledger']['fund_id'];
			$account = $this->data['Ledger']['account_id'];
			if ($account == 0) { $account = '%'; }
			
			$this->set('ledgers', $this->Ledger->find('all', array( 'conditions'=>array( 'Ledger.fund_id =' => $fund, 
																						'Ledger.ledger_month =' => $month, 
																						'Ledger.ledger_year =' => $year, 
																						'Ledger.account_id LIKE' => $account,
																						'Ledger.act =' => 1), 
																	'order'=>'Ledger.account_id ASC, Ledger.ledger_date ASC')));
		}
	}
	
	
	//Post trade journal entries to the general ledger
	function post() {
		$this->data = $this->Session->read('ledger_post_data');
		$month = $this->data['Ledger']['accounting_period']['month'];
		$year = $this->data['Ledger']['accounting_period']['year'];
		$fund = $this->data['Ledger']['fund_id'];
		$accountid = $this->data['Ledger']['account_id'];
		
		$this->set('posts', $this->Ledger->post($month, $year, $fund, $accountid));
	
		//Get list of fund names and accounting book names
		$this->set('funds', $this->Ledger->Trade->Fund->find('list', array('fields'=>array('Fund.id','Fund.fund_name'),'order'=>array('Fund.fund_name'))));
		$this->set('accounts', array('0'=>'All Books') + $this->Ledger->Account->find('list', array('fields'=>array('Account.id','Account.account_name'),'order'=>array('Account.account_name'))));
		$this->Session->setFlash('Trades posted to general ledger.');	
	}
}
?>