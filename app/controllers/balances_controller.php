<?php
class BalancesController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Balances';

	function index($fund_in = false, $date_in = false) {
		$this->autoRender = false;
		$d = new Dispatcher();
			
		if (isset($this->params['form']['Submit'])) {
			$this->Session->write('fund_chosen', $this->data['Balance']['fund_id']);
			
			switch ($this->params['form']['Submit']) {
				case 'Calc':
					$d->dispatch(array('controller' => 'balances', 'action' => 'calc'),
								 array('data' => $this->data));
					break;
					
				case 'Unlock':
					$d->dispatch(array('controller' => 'balances', 'action' => 'unlock'),
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
		else if (isset($this->params['form']['Backdate_x'])) {
			$prevdate = $this->Balance->getPrevBalanceDate($this->data['Balance']['fund_id'], $this->data['Balance']['account_date']);
			if (!empty($prevdate)) { $this->data['Balance']['account_date'] = $prevdate; }
			$d->dispatch(array('controller' => 'balances', 'action' => 'view'),
								 array('data' => $this->data));
		}
		else if (isset($this->params['form']['Nextdate_x'])) {
			$nextdate = $this->Balance->getNextBalanceDate($this->data['Balance']['fund_id'], $this->data['Balance']['account_date']);
			if (!empty($nextdate)) { $this->data['Balance']['account_date'] = $nextdate; }
			$d->dispatch(array('controller' => 'balances', 'action' => 'view'),
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
				$fund = $this->Balance->Fund->find('first', array('fields'=>array('Fund.id'),'order'=>array('Fund.fund_name')));
				$fund = $fund['Fund']['id'];
			}
			
			if ($date_in) {
				$date = $date_in;
			}
			else {
				$date = $this->Balance->getPrevBalanceDate($fund, date('Y-m-d', strtotime('tomorrow')));
				if (empty($date)) {
					$date = date('Y-m-d');
				}
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
		
		//check to see if we need to add an extra line for the fund currency fx rate
		list($foundfundccy, $fundccysecid, $fundccyname) = $this->Balance->hasfundccy($fund, $date, $balances);
		if (!$foundfundccy) {
			$this->set(compact('foundfundccy', 'fundccysecid', 'fundccyname'));
		}
		
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
		///////////////
		
		if ($this->Balance->islocked($fund, $date)) {
			$this->set('locked', true);
			$this->set('message', 'This month end is locked');
		}
		
		if ($this->Balance->needsRecalc($fund, $date)) {
			$this->set('message', 'A newer journal posting has been made. Please rerun the balance calculation to update the cash ledger.');
		}
		
		$this->dropdownchoices();
		$this->render('index');				
	}
	
	
	function calc() {	
		$fund = $this->data['Balance']['fund_id'];
		$date = $this->data['Balance']['calc_date'];
	
		//First check that this date is not locked
		if ($this->Balance->islocked($fund, $date)) {
			$this->Session->setFlash('Cannot recalculate balances as this date is locked');
		}
		else {
			//work out the month end balances, the function also saves the results to the model table
			if (!$this->Balance->calc($fund, $date)) {
				$this->Session->setFlash('Problem with calculating balances');
			}
			else {
				$this->Session->setFlash('Balances successfully calculated');
				$this->data['Balance']['account_date'] = $date;
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
	
	
	//Unlock this balance date and all future locked balance dates from this date on. This is why this action has its own page with a big warning on it.
	function unlock() {
		$fund = $this->data['Balance']['fund_id'];
		$date = $this->data['Balance']['account_date'];
		
		if (isset($this->params['form']['Submit'])) {			
			if ($this->params['form']['Submit'] == 'Yes') {
				//do it and stand back, they were warned!
				if ($this->Balance->unlock($fund, $date)) {
					$this->Session->setFlash('Month successfully unlocked');
				}
				else {
					$this->Session->setFlash('Problem with unlocking this month end');
				}
			}
			$this->autoRender = false;
			$d = new Dispatcher();
			$d->dispatch(array('controller' => 'balances', 'action' => 'view'), array('data' => $this->data));
		}
		else {
			$this->dropdownchoices();
		}
	}
	
	
	function dropdownchoices() {
		//funds dropdown list
		$this->set('funds', $this->Balance->Fund->find('list', array('fields'=>array('Fund.id','Fund.fund_name'),'order'=>array('Fund.fund_name'))));
	}
	
	//allow user to save a price to the prices table
	function ajax_enterprice() {
		//load up Price model and try to write the price to the database
		$sec_id = $this->params['form']['sec_id'];
		$price_date = $this->params['form']['price_date'];
		$price_value = $this->params['form']['price'];
		$fx_rate = $this->params['form']['fx_rate'];
		
		App::import('model','Price');
		$price = new Price();
		
		$this->set('data', $price->put_price($sec_id, $price_date, $price_value, $fx_rate));
		$this->render('/elements/ajax_common', 'ajax');
	}
	
	
	//count how many missing price fields are left to fill in. Can't seem to do this on the page itself because results are cached.
	function ajax_checkfinished() {
		if (isset($this->params['data']['Balance']['Pricebox'])) {
			$this->set('data', 'no');
		}
		else {
			$this->set('data', 'yes');
		}
		$this->render('/elements/ajax_common', 'ajax');
	}
}	
?>