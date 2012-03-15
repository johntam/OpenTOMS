<?php
class CashLedgersController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'CashLedgers';

	function index() {
		$this->autoRender = false;
		$d = new Dispatcher();
		App::import('model','Ledger');
		$ledger = new Ledger();
		
		if (isset($this->data)) {
			//user has made a choice
			$this->Session->write('fund_chosen', $this->data['CashLedger']['fund_id']);
			
			if (isset($this->params['form']['Backdate_x'])) {
				//back button pressed
				$prevdate = $ledger->getPrevPostDate($this->data['CashLedger']['fund_id'], $this->data['CashLedger']['account_date']);
				if (!empty($prevdate)) { $this->data['CashLedger']['account_date'] = $prevdate; }
			}
			else if (isset($this->params['form']['Nextdate_x'])) {
				//forward button pressed
				$nextdate = $ledger->getNextPostDate($this->data['CashLedger']['fund_id'], $this->data['CashLedger']['account_date']);
				if (!empty($nextdate)) { $this->data['CashLedger']['account_date'] = $nextdate; }
			}
		}	
		else {
			//just directed here from another page, try if possible to use whatever fund was chosen on the trade blotter as the default fund choice
			if ($this->Session->check('fund_chosen')) {
				$fund = $this->Session->read('fund_chosen');
			}
			else {
				$fund = $this->CashLedger->Fund->find('first', array('fields'=>array('Fund.id'),'order'=>array('Fund.fund_name')));
				$fund = $fund['Fund']['id'];
			}
			
			$date = $ledger->getPrevPostDate($fund, date('Y-m-d', strtotime('tomorrow')));
			if (empty($date)) {
				$date = date('Y-m-d');
			}
			
			$this->data = array('CashLedger' => array('fund_id'=>$fund, 'account_date'=>$date, 'ccy'=>14));	//ccy code 14 is USD
		}
		
		//pass control over to the view action
		$d->dispatch(array('controller' => 'CashLedgers', 'action' => 'view'), array('data' => $this->data));
	}
	
	
	//view ledger for this month
	function view() {
		$date = $this->data['CashLedger']['account_date'];
		$fund = $this->data['CashLedger']['fund_id'];
		$ccy = $this->data['CashLedger']['ccy'];
		
		App::import('model','Balance');
		$balmodel = new Balance();
		
		if (!$balmodel->balanceExists($fund, $date)) {
			$this->set('message', 'No balance calculation has been done for this date. Please run it first.');
		}
		else if ($balmodel->needsRecalc($fund, $date)) {
			$this->set('message', 'A newer journal posting has been made. Please rerun the balance calculation to update the cash ledger.');
		}
		else {
			$this->set('cashledgers', $this->CashLedger->getCash($fund, $date, $ccy));
			
			//get the balances calculated at the end date from the balances table
			list($end_debit, $end_credit, $end_qty) = $this->CashLedger->carry_forward($fund, $date, $ccy);
			$this->set(compact('end_debit', 'end_credit', 'end_qty'));
		}
																	
		//get the carried forward balances from the beginning of the period
		//need the balance date prior to $date
		$prevdate = $balmodel->getPrevBalanceDate($fund, $date);
		if (!empty($prevdate)) {
			list($start_debit, $start_credit, $start_qty) = $this->CashLedger->carry_forward($fund, $prevdate, $ccy);
			$this->set(compact('start_debit', 'start_credit', 'start_qty'));
			$this->set('prevdate', $prevdate);
		}
		
		$this->dropdownchoices();
	}
	
	
	//Get list of fund names and currency names for the dropdown lists
	function dropdownchoices() {
		$this->set('funds', $this->CashLedger->Fund->find('list', array('fields'=>array('Fund.id','Fund.fund_name'),'order'=>array('Fund.fund_name'))));
		$this->set('currencies', $this->CashLedger->Currency->find('list', array('fields'=>array('Currency.currency_iso_code'),'order'=>array('Currency.currency_iso_code'))));
	}
	
	
	
}
?>