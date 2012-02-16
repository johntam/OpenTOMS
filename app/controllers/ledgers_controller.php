<?php
class LedgersController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Ledgers';

	function index() {
		//Get list of fund names and accounting book names
		$this->set('funds', $this->Ledger->Fund->find('list', array('fields'=>array('Fund.id','Fund.fund_name'),'order'=>array('Fund.fund_name'))));
		$this->set('accounts', array('0'=>'All Books') + $this->Ledger->Account->find('list', array('fields'=>array('Account.id','Account.account_name'),'order'=>array('Account.account_name'))));
		
		echo debug($this->params);
		
		if (empty($this->data)) {
			$month = date('n');
			$year = date('Y');
			$fund = $this->Ledger->Fund->find('first', array('fields'=>array('Fund.id'),'order'=>array('Fund.fund_name')));
			

			
			$this->set('ledgers', $this->Ledger->find('all', array('conditions'=>array('Ledger.fund_id =' => $fund['Fund']['id'], 'Ledger.ledger_month =' => $month, 'Ledger.ledger_year =' => $year, 'Ledger.act =' => 1), 'order'=>'Ledger.ledger_date ASC')));
		}
	}
	
	
	//Post trade journal entries to the general ledger
	function post() {
		$month = $this->data['Ledger']['accounting_period']['month'];
		$year = $this->data['Ledger']['accounting_period']['year'];
		$fund = $this->data['Ledger']['fund_id'];
		$accountid = $this->data['Ledger']['account_id'];
		
		echo debug($this->data);
		
		//get trades with trade date in the month
		$sqlparam=array(
			'conditions' => array('MONTH(Trade.trade_date) =' => $month, 'YEAR(Trade.trade_date) =' => $year, 'Trade.fund_id =' => $fund, 'Trade.act' => 1, 'Trade.cancelled' => 0, 'Trade.executed' => 1), 
			'fields' => array('Trade.fund_id','Trade.trade_date','Trade.id','Trade.trade_type_id','TradeType.trade_type','TradeType.debit_account_id',
							'TradeType.credit_account_id', 'Trade.consideration', 'Currency.id','Currency.currency_iso_code','Trade.quantity','Fund.fund_name', 'Sec.sec_name'),
			'order' => array('Trade.trade_date ASC') 
		);
		
		//restrict this to a specific book or fetch for all books (don't need extra filter in this case)
		if ($accountid != 0) {
			$sqlparam['conditions']['OR'] = array( 'TradeType.debit_account_id' => $accountid, 'TradeType.credit_account_id' => $accountid );
		}
		
		echo debug($this->params);
		
		$posts = $this->Ledger->Trade->find('all', $sqlparam);
		$this->set('posts', $posts);
		
		//Get list of fund names and accounting book names
		$this->set('funds', $this->Ledger->Trade->Fund->find('list', array('fields'=>array('Fund.id','Fund.fund_name'),'order'=>array('Fund.fund_name'))));
		$this->set('accounts', array('0'=>'All Books') + $this->Ledger->Account->find('list', array('fields'=>array('Account.id','Account.account_name'),'order'=>array('Account.account_name'))));
		
		//Just view the trades that would be posted
		if ($this->params['form']['Submit'] == 'View') {

		}
		else {	//post these trades into the model database
			
			//echo debug($posts);
		
			
			foreach ($posts as $post) {
				$fund = $post['Trade']['fund_id'];
				$td = $post['Trade']['trade_date'];
				$tid = $post['Trade']['id'];
				$ttid = $post['Trade']['trade_type_id'];
				$cons = abs($post['Trade']['consideration']);
				$debitid = $post['TradeType']['debit_account_id'];
				$creditid = $post['TradeType']['credit_account_id'];
				$ccy = $post['Currency']['id'];
				$qty = $post['Trade']['quantity'];
				
				//first line of double-entry
				$data = array(	'act' => 1,
								'locked' => 0,
								'crd' => DboSource::expression('NOW()'),
								'fund_id' => $fund,
								'account_id' => $debitid,
								'ledger_month' => $month,
								'ledger_year' => $year,
								'ledger_date' => $td,
								'trade_id' => $tid,
								'ledger_debit' => $cons,
								'ledger_credit' => 0,
								'ledger_balance' => 0,
								'currency_id' => $ccy,
								'ledger_quantity' => $qty );
								
				$this->Ledger->create($data);
				$this->Ledger->save();
				
				//second line of double-entry
				$data['crd'] = DboSource::expression('NOW()');
				$data['account_id'] = $creditid;
				$data['ledger_debit'] = 0;
				$data['ledger_credit'] = $cons;
				$this->Ledger->create($data);
				$this->Ledger->save();
			}
		
			$this->Session->setFlash('Trades posted to journal.');
		}
	}
	

}
?>