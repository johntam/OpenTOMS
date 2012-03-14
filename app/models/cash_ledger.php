<?php

class CashLedger extends AppModel {
    var $name = 'CashLedger';
	var $useTable = 'ledgers';
	var $belongsTo ='Account, Trade, Fund, Currency, Sec';
	
	
	//get the carried forward cash balance from the previous balance date
	function carry_forward($fund, $date, $ccy) {
		App::import('model','Balance');
		$balmodel = new Balance();
	
		//need the date prior to $date
		$prevdate = $balmodel->getPrevBalanceDate($fund, $date);
		
		if (empty($prevdate)) {  
			return array(0,0,0);
		}
		else {
			//get the total sum for that currency for that date
			$cash_acc_id = $this->Account->getNamed('Cash');
			$result = $balmodel->find('all', array( 'conditions'=>array('Balance.act ='=>1,
																		'Balance.fund_id ='=>$fund,
																		'Balance.balance_date ='=>$prevdate,
																		'Balance.account_id ='=>$cash_acc_id,
																		'Balance.currency_id ='=>$ccy), 
													'fields'=>array('Balance.balance_debit', 'Balance.balance_credit', 'Balance.balance_quantity')));
			
			return array($result[0]['Balance']['balance_debit'], 
						 $result[0]['Balance']['balance_credit'], 
						 $result[0]['Balance']['balance_quantity']);
		}
	}
}

?>