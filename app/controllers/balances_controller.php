<?php
class BalancesController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Balances';

	function index() {
		$this->autoRender = false;
		$d = new Dispatcher();
			
		if (isset($this->params['form']['Submit'])) {
			switch ($this->params['form']['Submit']) {
				case 'View':
					$d->dispatch(array('controller' => 'balances', 'action' => 'view'),
								 array('data' => $this->data));
					break;
				
				case 'Calc':
					$d->dispatch(array('controller' => 'balances', 'action' => 'calc'),
								 array('data' => $this->data));
					break;
					
				case 'Unlock':
					$d->dispatch(array('controller' => 'balances', 'action' => 'unlockMonthEnd'),
								 array('data' => $this->data));
					break;
					
				case 'Lock':
					$d->dispatch(array('controller' => 'balances', 'action' => 'lock'),
								 array('data' => $this->data));
					break;
					
				default:
					$d->dispatch(array('controller' => 'balances', 'action' => 'view'),
								 array('data' => $this->data));
			}
		}
		else {
			//page just loaded
			$fund = $this->Balance->Fund->find('first', array('fields'=>array('Fund.id'),'order'=>array('Fund.fund_name')));
			$fund = $fund['Fund']['id'];
			$date = $this->Balance->getPrevBalanceDate($fund, date('Y-m-d', strtotime('tomorrow')));
			if (empty($date)) {
				$date = date('Y-m-d');
			}
			$this->data['Balance'] = array('fund_id'=>$fund, 'account_date'=>$date);
			$d->dispatch(array('controller' => 'balances', 'action' => 'view'),
								 array('data' => $this->data));
		}
	}
	
	
	function view() {	
		$fund = $this->data['Balance']['fund_id'];
		$date = $this->data['Balance']['account_date'];
		
		$balances = $this->Balance->attachprices($fund, $date);
		$this->set('balances', $balances);
		
		//get a list of ledger posting dates which are after the last locked balance date on the system
		$lastlockeddate = $this->Balance->getPrevLockedDate($fund);
		if (empty($lastlockeddate)) { $lastlockeddate = '1999-12-31'; }
		App::import('model','Ledger');
		$ledger = new Ledger();
		$datelist = $ledger->find('all', array('conditions'=>array(	'Ledger.act ='=>1, 
																	'Ledger.fund_id ='=>$fund, 
																	'Ledger.ledger_date >' =>$lastlockeddate), 
											  'fields'=>array('DISTINCT Ledger.ledger_date'), 
											  'order'=>array('Ledger.ledger_date DESC')));
		$calcdates = array();
		foreach ($datelist as $d) {
			$calcdates[$d['Ledger']['ledger_date']] = $d['Ledger']['ledger_date'];
		}
		$this->set('calcdates', $calcdates);
		
		if ($this->Balance->islocked($fund, $date)) {
			$this->set('locked', true);
		}
		
		$this->dropdownchoices();
		$this->render('index');
	}
	
	
	function calc() {	
		$fund = $this->data['Balance']['fund_id'];
		$date = $this->data['Balance']['calc_date'];
	
		//First check that this date is not locked
		if ($this->Balance->islocked($fund, $date)) {
			$this->Session->setFlash('Cannot recalculate balances as this date is locked.');
		}
		else {
			//work out the month end balances, the function also saves the results to the model table
			if (!$this->Balance->calc($fund, $date)) {
				$this->Session->setFlash('Problem with calculating balances.');
			}
		}
		$this->autoRender = false;
		$d = new Dispatcher();
		$d->dispatch(array('controller' => 'balances', 'action' => 'view'),
					 array('data' => $this->data));
	}
	
	
	function lock() {
		$fund = $this->data['Balance']['fund_id'];
		$date = $this->data['Balance']['account_date'];
		
		//try to lock date balances
		if ($this->Balance->lock($fund, $date)) {
			$this->Session->setFlash('Month successfully locked.');
		}
		else {
			$this->Session->setFlash('Problem with locking this month end.');
		}
		$this->autoRender = false;
		$d = new Dispatcher();
		$d->dispatch(array('controller' => 'balances', 'action' => 'view'),
							 array('data' => $this->data));
	}
	
	
	//Unlock this month end balance and all future month end balances from this date on. This is why this action has its own page with a big warning on it.
	function unlockMonthEnd() {
		$fund = $this->data['Balance']['fund_id'];
		$date = $this->data['Balance']['account_date'];
		
		if (isset($this->params['form']['Submit'])) {			
			if ($this->params['form']['Submit'] == 'Yes') {
				//do it and stand back, they were warned!
				if ($this->Balance->unlock($fund, $date)) {
					$this->Session->setFlash('Month successfully unlocked.');
				}
				else {
					$this->Session->setFlash('Problem with unlocking this month end.');
				}
			}
			$this->autoRender = false;
			$d = new Dispatcher();
			$d->dispatch(array('controller' => 'balances', 'action' => 'view'),
						 array('data' => $this->data));
		}
		else {
			$this->dropdownchoices();
		}
	}
	
	
	function dropdownchoices() {
		//funds dropdown list
		$this->set('funds', $this->Balance->Fund->find('list', array('fields'=>array('Fund.id','Fund.fund_name'),'order'=>array('Fund.fund_name'))));
	}
}	
?>