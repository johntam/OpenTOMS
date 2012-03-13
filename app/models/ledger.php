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
							'Fund.fund_name', 'Sec.sec_name', 'Sec.id', 'Trade.execution_price', 'Sec.valpoint','Trade.commission','Trade.tax','Trade.other_costs', 'Sec.currency_id'),
			'order' => array('Trade.trade_date ASC', 'Trade.crd ASC') 
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
		
		//get trades from the last balance calculation date to the date selected
		$posts = $this->Trade->find('all', $sqlparam);
		
		if (empty($posts)) {
			//no trades to post, however must create a dummy line in the table so that we can choose this date on the balances screen
			$data = array(	'act' => 1,
							'crd' => DboSource::expression('NOW()'),
							'fund_id' => $fund,
							'account_id' => 1,	//cash
							'ledger_date' => $date,
							'trade_date' => $date,
							'trade_id' => 0,
							'trade_crd' => DboSource::expression('NOW()'),
							'ledger_debit' => 0,
							'ledger_credit' => 0,
							'ledger_cfd' => 0,
							'currency_id' => 1,	//USD
							'ledger_quantity' => 0,
							'sec_id' => -1);
				$this->create($data);
				$this->save();
				return null;
		}
		
		$trad_cost_acc_id = $this->Account->getNamed('Trading Costs');
		
		//make inactive all previous ledger entries for this month that are unlocked (the check for the lock occurs in the controller).
		if ($this->updateAll( array('Ledger.act' => 0), array('Ledger.ledger_date =' => $date, 'Ledger.fund_id =' => $fund, 'Ledger.act =' => 1))) {
			$last_td = 0;
			$last_td_crd = 0;
			foreach ($posts as $post) {
				$fund = $post['Trade']['fund_id'];
				$td = $post['Trade']['trade_date'];
				$tid = $post['Trade']['id'];
				$ttid = $post['Trade']['trade_type_id'];
				$tcrd = $post['Trade']['crd'];
				$tccy = $post['Currency']['id'];
				$trading_costs = $post['Trade']['commission'] + $post['Trade']['tax'] + $post['Trade']['other_costs'];	//always positive
				$consX = $post['Trade']['consideration'] + $trading_costs;
				$cons = $post['Trade']['consideration'];
				$cfd = abs($post['Trade']['notional_value']);
				if ($cfd) { $cfd = 1; } else { $cfd = 0; }
				$debitid = $post['TradeType']['debit_account_id'];
				$creditid = $post['TradeType']['credit_account_id'];
				$ccy = $post['Sec']['currency_id'];
				$qty = $post['Trade']['quantity'];
				$secid = $post['Sec']['id'];
				$price = $post['Trade']['execution_price'];
				$valp = $post['Sec']['valpoint'];
				//Get the order right for the trinv so that FIFO etc work properly
				if (strtotime($td) == $last_td) {
					$trinv = ($last_td_crd+1).':'.$qty.':'.$price.':'.$valp.';';
					$last_td_crd += 1;
				}
				else {
					$trinv = strtotime($td).':'.$qty.':'.$price.':'.$valp.';';
					$last_td_crd = strtotime($td);
				}
				$last_td = strtotime($td);
				//
				
				//first line of double-entry
				if ($debitid > 1) {		//cash
					$secid2 = $this->Currency->getsecid($tccy);
					$qty2 = $cons;
					$tr2 = '';
					$ccy2 = $tccy;
					$cons2 = $cons;
					$cfd2 = 0;
				}
				else {
					$secid2 = $secid;
					$qty2 = $qty;
					$tr2 = $trinv;
					$ccy2 = $ccy;
					$cons2 = abs($consX);
					$cfd2 = $cfd;
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
								'ledger_cfd' => $cfd2,
								'currency_id' => $ccy2,
								'ledger_quantity' => $qty2,
								'sec_id' => $secid2,
								'trinv' => $tr2);
				$this->create($data);
				$this->save();
				
				//second line of double-entry
				if ($creditid > 1) { 	//cash
					$data['sec_id']  = $this->Currency->getsecid($tccy);
					$data['ledger_quantity'] = $cons;
					$data['trinv'] = '';
					$data['currency_id'] = $tccy;
					$data['ledger_credit'] = -$cons;
					$data['ledger_cfd'] = 0;
				}
				else {
					$data['sec_id']  = $secid;
					$data['ledger_quantity'] = $qty;
					$data['trinv'] = $trinv;
					$data['currency_id'] = $ccy;
					$data['ledger_credit'] = abs($consX);
					$data['ledger_cfd'] = $cfd;
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
					$data['ledger_cfd'] = 0;
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
	function getPrevPostDate($fund, $date) {
		$fetch = $this->find('first', array('conditions'=>array('Ledger.fund_id ='=>$fund, 'Ledger.ledger_date <'=>$date, 'Ledger.act ='=>1), 'order'=>'Ledger.ledger_date DESC'));
		if (empty($fetch)) {
			return null;
		}
		else {
			return $fetch['Ledger']['ledger_date'];
		}
	}
	
	//get date of next ledger posting date, PHP value of 0=false, anything else=true
	function getNextPostDate($fund, $date) {
		$fetch = $this->find('first', array('conditions'=>array('Ledger.fund_id ='=>$fund, 'Ledger.ledger_date >'=>$date, 'Ledger.act ='=>1), 'order'=>'Ledger.ledger_date ASC'));
		if (empty($fetch)) {
			return null;
		}
		else {
			return $fetch['Ledger']['ledger_date'];
		}
	}
}

?>