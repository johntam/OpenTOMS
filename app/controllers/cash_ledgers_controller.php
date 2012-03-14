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
		$cash_acc_id = $this->CashLedger->Account->getNamed('Cash');
		$pnl_acc__id = $this->Account->getNamed('Profit And Loss');
		
		$cashdata = $this->CashLedger->find('all', array( 'fields'=>array('Fund.fund_name',
																						'Account.account_name',
																						'CashLedger.trade_date',
																						'CashLedger.ledger_debit',
																						'CashLedger.ledger_credit',
																						'Currency.currency_iso_code',
																						'Sec.sec_name',
																						'CashLedger.ledger_quantity',
																						'Trade.oid',
																						'Sec2.sec_name'),
																		'conditions'=>array('CashLedger.fund_id =' => $fund,
																							'CashLedger.account_id =' => $cash_acc_id,
																							'CashLedger.ledger_date =' => $date,
																							'CashLedger.currency_id =' => $ccy,
																							'CashLedger.act =' => 1,
																							'CashLedger.sec_id >'=> 0), 
																		'order'=> array('CashLedger.trade_date' => 'ASC', 
																						'CashLedger.trade_crd' => 'ASC' ),
																		'joins' => array(
																						array('table'=>'secs',
																							  'alias'=>'Sec2',
																							  'type'=>'left',
																							  'foreignKey'=>false,
																							  'conditions'=>
																									array(	'CashLedger.ref_id=Sec2.id')
																							  ))));
		//add on any pnl generated by cfd type instruments
		//this will be recorded in the ref_id column of the balances table
		App::import('model','Balance');
		$balmodel = new Balance();
		$addpnl = $balmodel->find('all', array('conditions'=>array('Balance.act ='=>1,
																	'Balance.fund_id ='=>$fund,
																	'Balance.balance_date ='=>$date,
																	'Balance.account_id ='=>$pnl_acc__id,
																	'Balance.currency_id ='=>$ccy),
											'fields'=>array('Balance.ref_id')));
		if (!empty($addpnl[0]['Balance']['ref_id']) {
			$arr = array();
			$sp1 = explode(";", $tr);
			foreach ($sp1 as $sp2) {
				if (!empty($sp2)) {
					$sp3 = explode(':', "$sp2:::");
					$arr[$sp3[0]] = array('quantity'=>$sp3[1], 'price'=>$sp3[2], 'valpoint'=>$sp3[3]);
				}
			}
		
		}
		
		
		$this->set('cashledgers', $cashdata);
																						
		//get the carried forward balances from the beginning of the period
		list($debit, $credit, $qty) = $this->CashLedger->carry_forward($fund, $date, $ccy);
		$this->set(compact('debit', 'credit', 'qty'));
		$this->dropdownchoices();
	}
	
	
	//Get list of fund names and currency names for the dropdown lists
	function dropdownchoices() {
		$this->set('funds', $this->CashLedger->Fund->find('list', array('fields'=>array('Fund.id','Fund.fund_name'),'order'=>array('Fund.fund_name'))));
		$this->set('currencies', $this->CashLedger->Currency->find('list', array('fields'=>array('Currency.currency_iso_code'),'order'=>array('Currency.currency_iso_code'))));
	}
}
?>