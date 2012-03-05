<?php

class Balance extends AppModel {
    var $name = 'Balance';
	var $belongsTo ='Account, Currency, Fund, Sec';
	
	//calculate the month end balances, using the last month end balances and this month's general ledger
	function calc($fund, $date) {
		//first get the date when the last balance was calculated.
		$prevdate = $this->getPrevBalanceDate($fund, $date);
				
		//get the last balance data, else use a null array
		if (empty($prevdate)) {
			$baldata = array();
		}
		else {
			$baldata = $this->find('all', array('conditions'=>array('Balance.act =' => 1, 'Balance.balance_date =' => $prevdate, 'Balance.fund_id =' => $fund)));
		}
				
		//get this month's ledger entries
		App::import('model','Ledger');
		$ledger = new Ledger();
		$ledgdata = $ledger->find('all', array('conditions'=>array('Ledger.act =' => 1, 'Ledger.ledger_date =' => $date, 'Ledger.fund_id =' => $fund)));
		
		//Aggregate these two sets together, GROUP BY (account_id, sec_id)
		$newbal = array();
		foreach ($baldata as $b) {
			$newbal[$b['Balance']['account_id']][$b['Balance']['sec_id']][] = array('ledger_debit'=>$b['Balance']['balance_debit'],
																					'ledger_credit'=>$b['Balance']['balance_credit'],
																					'quantity'=>$b['Balance']['balance_quantity'],
																					'currency_id'=>$b['Balance']['currency_id']);
		}
				
		foreach ($ledgdata as $l) {
			$newbal[$l['Ledger']['account_id']][$l['Ledger']['sec_id']][] = array(  'ledger_debit'=>$l['Ledger']['ledger_debit'],
																					'ledger_credit'=>$l['Ledger']['ledger_credit'],
																					'quantity'=>$l['Ledger']['ledger_quantity'],
																					'currency_id'=>$l['Ledger']['currency_id']);
		}
		
		//deactivate all previous balances for this month end
		$result = $this->updateAll( array('Balance.act' => 0), 
										array(	'Balance.balance_date =' => $date,
												'Balance.fund_id =' => $fund,
												'Balance.locked =' => 0,
												'Balance.act =' => 1));
		
		if (!$result) { return false; }
		
		//we have a two-dimensional array of aggregated data, save it to the table now
		foreach ($newbal as $acc=>$n1) {
			foreach ($n1 as $sec=>$n2) {
				$totdeb = 0;
				$totcred = 0;
				$totqty = 0;
				$ccy = 0;
				foreach ($n2 as $d) {
					$totdeb += $d['ledger_debit'];
					$totcred += $d['ledger_credit'];
					$totqty += $d['quantity'];
					$ccy = $d['currency_id'];
				}
				$data['Balance'] = array('act' => 1,
										 'locked' => 0,
										 'crd'=>DboSource::expression('NOW()'),
										 'fund_id' => $fund,
										 'account_id'=>$acc,
										 'balance_date'=>$date,
										 'balance_debit'=>$totdeb,
										 'balance_credit'=>$totcred,
										 'currency_id'=>$ccy,
										 'balance_quantity'=>$totqty,
										 'sec_id'=>$sec);
				$this->create($data);
				$this->save();
			}
		}
		
		return true;
	}
	
	
	//put prices and fx rates next to balance items by left joining onto the prices table
	function attachprices($fund, $date) {
		$this->unBindModel(array('belongsTo' => array('Currency')));
		
		$params=array(	'fields' => array(	'Fund.fund_name',
											'Account.id',
											'Account.account_name',
											'Balance.balance_debit',
											'Balance.balance_credit',
											'Currency.currency_iso_code',
											'Sec.sec_name',
											'Balance.balance_quantity',
											'Price.price',
											'PriceFX.fx_rate',
											'Price.sec_id',
											'Balance.sec_id',
											'Sec.id',
											'Currency.sec_id',
											'Sec.sec_type_id'),
						'joins' => array(
										array('table'=>'currencies',
											  'alias'=>'Currency',
											  'type'=>'inner',
											  'foreignKey'=>false,
											  'conditions'=>
													array(	'Currency.id=Balance.currency_id')
											  ),
										array('table'=>'prices',
											  'alias'=>'Price',
											  'type'=>'left',
											  'foreignKey'=>false,
											  'conditions'=>
													array(	'Price.sec_id=Balance.sec_id',
															"Price.price_date='".$date."'")
											  ),
										array('table'=>'prices',
											  'alias'=>'PriceFX',
											  'type'=>'left',
											  'foreignKey'=>false,
											  'conditions'=>
													array(	'PriceFX.sec_id=Currency.sec_id',
															"PriceFX.price_date='".$date."'")
											  )
										),
						'conditions' => array('Balance.act ='=>1, 'Balance.fund_id ='=>$fund, 'Balance.balance_date ='=>$date),
						'order' => array('Balance.account_id')
					);		
		return ($this->find('all', $params));
	}
	
	
	//lock month end
	function lock($fund, $date) {
		$result = $this->updateAll( array('Balance.locked' => 1), 
										array(	'Balance.balance_date =' => $date,
												'Balance.fund_id =' => $fund,
												'Balance.act =' => 1));
		return ($result);
	}
	
	//unlock month end
	function unlock($fund, $date) {
		$result = $this->updateAll( array('Balance.locked' => 0), 
										array(	'Balance.balance_date =' => $date, 
												'Balance.fund_id =' => $fund,
												'Balance.act =' => 1));
		//unlock all future month ends
		$result2 = $this->updateAll( array('Balance.locked' => 0), 
										array(	'Balance.balance_date >' => $date, 
												'Balance.fund_id =' => $fund,
												'Balance.act =' => 1));
		return ($result && $result2);
	}
	
	//is this month locked?
	//the better way would be to have a record dates table with a locked status field, maybe do this for a future version.
	function islocked($fund, $date) {
		$result = $this->find('first', array('conditions'=>array('Balance.fund_id ='=>$fund, 'Balance.balance_date ='=>$date, 'Balance.act ='=>1), 'fields'=>array('Balance.locked')));
		
		if (empty($result['Balance']['locked'])) {
			return false;
		}
		else if ($result['Balance']['locked'] == 1) {
			return true;
		}
		else {
			return false;
		}
	}
	
	
	//clear all the balance data for this fund
	//!Warning, use with extreme caution!
	function wipe($fund) {
		$result = $this->updateAll( array('Balance.locked' => 0,
										  'Balance.act' => 0), 
										array(	'Balance.fund_id =' => $fund));
		return $result;
	}
	
	
	//checks to see if the specified month end balances exist for the fund, PHP value of 0=false, anything else=true
	function getPrevBalanceDate($fund, $date) {
		$fetch = $this->find('first', array('conditions'=>array('Balance.fund_id ='=>$fund, 'Balance.balance_date <' => $date, 'Balance.act ='=>1), 'order'=>'Balance.balance_date DESC'));
		if (empty($fetch)) {
			return null;
		}
		else {
			return $fetch['Balance']['balance_date'];
		}
	}
	
	//get date of last locked balance date, PHP value of 0=false, anything else=true
	function getPrevLockedDate($fund) {
		$fetch = $this->find('first', array('conditions'=>array('Balance.fund_id ='=>$fund, 'Balance.locked =' => 1, 'Balance.act ='=>1), 'order'=>'Balance.balance_date DESC'));
		if (empty($fetch)) {
			return null;
		}
		else {
			return $fetch['Balance']['balance_date'];
		}
	}
}

?>