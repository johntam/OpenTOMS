<?php

class Ledger extends AppModel {
    var $name = 'Ledger';
	var $belongsTo ='Account, Trade, Fund, Currency, Sec';
	
	//Post trade journal entries to the general ledger
	function post($fund, $date) {	
		//get trades with trade date in the month
		$sqlparam=array(
			'conditions' => array(	'Trade.fund_id =' => $fund, 
									'Trade.act =' => 1, 
									'Trade.cancelled =' => 0, 
									'Trade.executed =' => 1
								 ), 
			'fields' => array('Trade.fund_id','Trade.trade_date','Trade.id','Trade.crd','Trade.trade_type_id','TradeType.trade_type','TradeType.debit_account_id',
							'TradeType.credit_account_id', 'Trade.consideration', 'Trade.notional_value','Currency.id','Currency.currency_iso_code','Trade.quantity',
							'Fund.fund_name', 'Sec.sec_name', 'Sec.id', 'Trade.price', 'Sec.valpoint','Trade.commission','Trade.tax','Trade.other_costs', 'Sec.currency_id'),
			'order' => array('Trade.trade_date ASC') 
		);
		
		//get the date of the previous balance calculation date
		App::import('model','Balance');
		$bal = new Balance();
		$prevdate = $bal->getPrevBalanceDate($fund, $date);
		
		if (empty($prevdate)) {
			$sqlparam['conditions']['Trade.trade_date <='] = $date;	//get all trades before $date since no prior balance date exists
		}
		else {
			$sqlparam['conditions']['AND'] = array('Trade.trade_date >' => $prevdate,
												   'Trade.trade_date <=' => $date);
		}
			
		$posts = $this->Trade->find('all', $sqlparam);
			
		$trad_cost_acc_id = $this->Account->getNamed('Trading Costs');
		
		//make inactive all previous ledger entries for this month that are unlocked (the check for the lock occurs in the controller).
		if ($this->updateAll( array('Ledger.act' => 0), array('Ledger.ledger_date =' => $date, 'Ledger.fund_id =' => $fund, 'Ledger.act =' => 1))) {
			foreach ($posts as $post) {
				$fund = $post['Trade']['fund_id'];
				$td = $post['Trade']['trade_date'];
				$tid = $post['Trade']['id'];
				$ttid = $post['Trade']['trade_type_id'];
				$tcrd = $post['Trade']['crd'];
				$tccy = $post['Currency']['id'];
				$trading_costs = $post['Trade']['commission'] + $post['Trade']['tax'] + $post['Trade']['other_costs'];	//always positive
				$consX = abs($post['Trade']['consideration'] + $trading_costs);
				$cons = abs($post['Trade']['consideration']);
				$cfd = abs($post['Trade']['notional_value']);
				if ($cfd) { $cfd = 1; } else { $cfd = 0; }
				$debitid = $post['TradeType']['debit_account_id'];
				$creditid = $post['TradeType']['credit_account_id'];
				$ccy = $post['Sec']['currency_id'];
				$qty = $post['Trade']['quantity'];
				$secid = $post['Sec']['id'];
				$price = $post['Trade']['price'];
				$valp = $post['Sec']['valpoint'];
				$trinv = strtotime($tcrd).':'.$qty.':'.$price.':'.$valp.';';
				
				//first line of double-entry
				if ($debitid > 1) {		//cash
					$secid2 = $this->Currency->getsecid($tccy);
					$qty2 = abs($cons);
					$tr2 = '';
					$ccy2 = $tccy;
					$cons2 = $cons;
				}
				else {
					$secid2 = $secid;
					$qty2 = $qty;
					$tr2 = $trinv;
					$ccy2 = $ccy;
					$cons2 = $consX;
				}
				$data = array(	'act' => 1,
								'crd' => DboSource::expression('NOW()'),
								'fund_id' => $fund,
								'account_id' => $debitid,
								'ledger_date' => $date,
								'trade_date' => $td,
								'trade_id' => $tid,
								'trade_crd' => $tcrd,
								'ledger_debit' => $cons2,
								'ledger_credit' => 0,
								'ledger_cfd' => $cfd,
								'currency_id' => $ccy2,
								'ledger_quantity' => $qty2,
								'sec_id' => $secid2,
								'trinv' => $tr2);
				$this->create($data);
				$this->save();
				
				//second line of double-entry
				if ($creditid > 1) { 	//cash
					$data['sec_id']  = $this->Currency->getsecid($tccy);
					$data['ledger_quantity'] = -abs($cons);
					$data['trinv'] = '';
					$data['currency_id'] = $tccy;
					$data['ledger_credit'] = $cons;
				}
				else {
					$data['sec_id']  = $secid;
					$data['ledger_quantity'] = $qty;
					$data['trinv'] = $trinv;
					$data['currency_id'] = $ccy;
					$data['ledger_credit'] = $consX;
				}
				$data['crd'] = DboSource::expression('NOW()');
				$data['account_id'] = $creditid;
				$data['ledger_debit'] = 0;
				$this->create($data);
				$this->save();
				
				//third line for trading costs
				//trading costs are an expense and so usually should be debited
				if ($trading_costs <> 0) {
					$data['sec_id']  = $this->Currency->getsecid($tccy);
					$data['ledger_quantity'] = 0;
					$data['trinv'] = '';
					$data['crd'] = DboSource::expression('NOW()');
					$data['account_id'] = $trad_cost_acc_id;
					$data['ledger_debit'] = abs($trading_costs);
					$data['ledger_credit'] = 0;
					$data['currency_id'] = $tccy;
					$this->create($data);
					$this->save();
				}
			}
			
			return($posts);
		}
		else {
			return null;
		}
	}
	
	
	//wipe all ledgers for a particular fund
	function wipe($fund) {
		$result = $this->updateAll( array('Ledger.act'=> 0), 
										array(	'Ledger.fund_id =' => $fund));
		return ($result);
	}
	
	
	//get date of last ledger posting date, PHP value of 0=false, anything else=true
	function getPrevPostDate($fund) {
		$fetch = $this->find('first', array('conditions'=>array('Ledger.fund_id ='=>$fund, 'Ledger.act ='=>1), 'order'=>'Ledger.ledger_date DESC'));
		if (empty($fetch)) {
			return null;
		}
		else {
			return $fetch['Ledger']['ledger_date'];
		}
	}
}

?>