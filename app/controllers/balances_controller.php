<?php
class BalancesController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Balances';

	function index() {
		if (isset($this->data['Balance'])) {
			$fund = $this->data['Balance']['fund_id'];
			$month = $this->data['Balance']['accounting_period']['month'];
			$year = $this->data['Balance']['accounting_period']['year'];
			$monthenddate = mktime(0, 0, 0, $month + 1, 0, $year);	//last day of month
		
			//see which button was pressed
			if (isset($this->params['form']['View'])) {
				$this->set('balances', $this->Balance->attachprices($fund, $monthenddate));
				if ($this->Balance->islocked($fund, $month, $year)) {
					$this->set('locked', true);
				}
			}
			else if (isset($this->params['form']['Calc'])) {
				//First check that this month end is not locked
				if ($this->Balance->islocked($fund, $month, $year)) {
					$this->Session->setFlash('Cannot recalculate balances as this month end is locked.');
				}
				else {
					//work out the month end balances, the function also saves the results to the model table
					if ($this->Balance->monthend($fund, $month, $year)) {
						//if everything is ok then get the results just saved to the table and join them with any available prices and fx rates
						$this->set('balances', $this->Balance->attachprices($fund, $monthenddate));
					}
					else {
						$this->Session->setFlash('Problem with calculating balances.');
					}
				}
			}
			else if (isset($this->params['form']['Lock'])) {
				//try to lock month end balances
				if ($this->Balance->lock($fund, $month, $year)) {
					$this->Session->setFlash('Month successfully locked.');
					//$this->redirect(array('controller' => 'balances', 'action' => 'index'));
				}
				else {
					$this->Session->setFlash('Problem with locking this month end.');
				}
			}
			else if (isset($this->params['form']['Unlock'])) {
				if ($this->Balance->unlock($fund, $month, $year)) {
					$this->Session->setFlash('Month successfully unlocked.');
				}
				else {
					$this->Session->setFlash('Problem with unlocking this month end.');
				}
			}
		}
		
		//funds dropdown list
		$this->set('funds', $this->Balance->Fund->find('list', array('fields'=>array('Fund.id','Fund.fund_name'),'order'=>array('Fund.fund_name'))));
	}
}
?>