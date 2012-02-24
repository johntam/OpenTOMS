<?php
class BalancesController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Balances';

	function index() {
		if (isset($this->params['form']['Submit'])) {
			$this->Session->write('balances_data', $this->data);
			
			$fund = $this->data['Balance']['fund_id'];
			$month = $this->data['Balance']['accounting_period']['month'];
			$year = $this->data['Balance']['accounting_period']['year'];
			$monthenddate = mktime(0, 0, 0, $month + 1, 0, $year);	//last day of month		
			
			switch ($this->params['form']['Submit']) {
				case 'View':
					$this->redirect(array('controller' => 'balances', 'action' => 'view/'.$fund.'/'.$month.'/'.$year.'/'.$monthenddate));
					break;
				
				case 'Calc':
					$this->redirect(array('controller' => 'balances', 'action' => 'calc/'.$fund.'/'.$month.'/'.$year.'/'.$monthenddate));
					break;
					
				case 'Unlock':
					$this->redirect(array('controller' => 'balances', 'action' => 'unlockMonthEnd'));
					break;
					
				case 'Lock':
					$this->redirect(array('controller' => 'balances', 'action' => 'lock/'.$fund.'/'.$month.'/'.$year.'/'.$monthenddate));
					break;
					
				default:
					$this->redirect(array('controller' => 'balances', 'action' => 'view/'.$fund.'/'.$month.'/'.$year.'/'.$monthenddate));
			}
		}
		else {
			//page just loaded
			$fund = $this->Balance->Fund->find('first', array('fields'=>array('Fund.id'),'order'=>array('Fund.fund_name')));
			$fund = $fund['Fund']['id'];
			$month = date('n');
			$year = date('Y');
			$monthenddate = mktime(0, 0, 0, $month + 1, 0, $year);	//last day of month
			
			$this->Session->write('balances_data', $this->data);
			$this->redirect(array('controller' => 'balances', 'action' => 'view/'.$fund.'/'.$month.'/'.$year.'/'.$monthenddate));
		}
	}
	
	
	function view($fund, $month, $year, $monthenddate) {
		$this->data = $this->Session->read('balances_data');
		$this->dropdownchoices();
		$this->set('balances', $this->Balance->attachprices($fund, $monthenddate));
		
		if ($this->Balance->islocked($fund, $month, $year)) {
			$this->set('locked', true);
		}
		
		$this->render('index');
	}
	
	
	function calc($fund, $month, $year, $monthenddate) {
		$this->dropdownchoices();
	
		//First check that this month end is not locked
		if ($this->Balance->islocked($fund, $month, $year)) {
			$this->Session->setFlash('Cannot recalculate balances as this month end is locked.');
		}
		else {
			//work out the month end balances, the function also saves the results to the model table
			if (!$this->Balance->monthend($fund, $month, $year)) {
				$this->Session->setFlash('Problem with calculating balances.');
			}
		}
		$this->redirect(array('controller' => 'balances', 'action' => 'view/'.$fund.'/'.$month.'/'.$year.'/'.$monthenddate));
	}
	
	
	function lock($fund, $month, $year, $monthenddate) {
		$this->data = $this->Session->read('balances_data');
		$this->dropdownchoices();
	
		//try to lock month end balances
		if ($this->Balance->lock($fund, $month, $year)) {
			$this->Session->setFlash('Month successfully locked.');
		}
		else {
			$this->Session->setFlash('Problem with locking this month end.');
		}
		$this->redirect(array('controller' => 'balances', 'action' => 'view/'.$fund.'/'.$month.'/'.$year.'/'.$monthenddate));
	}
	
	
	//Unlock this month end balance and all future month end balances from this date on. This is why this action has its own page with a big warning on it.
	function unlockMonthEnd() {
		$this->data = $this->Session->read('balances_data');
		$this->dropdownchoices();
		
		if (isset($this->params['form']['Submit'])) {
			$fund = $this->data['Balance']['fund_id'];
			$month = $this->data['Balance']['accounting_period']['month'];
			$year = $this->data['Balance']['accounting_period']['year'];
			$monthenddate = mktime(0, 0, 0, $month + 1, 0, $year);	//last day of month
				
			if ($this->params['form']['Submit'] == 'Yes') {
				//do it and stand back, they were warned!
				if ($this->Balance->unlock($fund, $month, $year)) {
					$this->Session->setFlash('Month successfully unlocked.');
				}
				else {
					$this->Session->setFlash('Problem with unlocking this month end.');
				}
			}
		
			$this->redirect(array('controller' => 'balances', 'action' => 'view/'.$fund.'/'.$month.'/'.$year.'/'.$monthenddate));
		}
	}
	
	
	function dropdownchoices() {
		//funds dropdown list
		$this->set('funds', $this->Balance->Fund->find('list', array('fields'=>array('Fund.id','Fund.fund_name'),'order'=>array('Fund.fund_name'))));
	}
}	
?>