<?php
class BalancesController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Balances';

	function index() {
		if (isset($this->data['Balance'])) {
			$fund = $this->data['Balance']['fund_id'];
			$month = $this->data['Balance']['accounting_period']['month'];
			$year = $this->data['Balance']['accounting_period']['year'];
			$monthenddate = date('Y-m-d',mktime(0, 0, 0, $month + 1, 0, $year));	//last day of month
		
			//see which button was pressed
			if (isset($this->params['form']['Calc'])) {
				//First check that this month end is not locked
				if ($this->Balance->islocked($fund, $month, $year)) {
					$this->Session->setFlash('Cannot recalculate balances as this month end is locked.');
				}
				else {
					//work out the month end balances, the function also saves the results to the model table
					if ($this->Balance->monthend($fund, $month, $year)) {
						//if everything is ok then get the results just saved to the table, and left join onto prices table
						$params=array(	'fields' => array(	'Fund.fund_name',
															'Account.id',
															'Account.account_name',
															'Balance.balance_debit',
															'Balance.balance_credit',
															'Currency.currency_iso_code',
															'Sec.sec_name',
															'Balance.balance_quantity',
															'Price.price',
															'Price.fx_rate',
															'Price.sec_id',
															'Balance.sec_id'
														),
										'joins' => array(
														array('table'=>'prices',
															  'alias'=>'Price',
															  'type'=>'left',
															  'foreignKey'=>false,
															  'conditions'=>
																	array(	'Price.sec_id=Balance.sec_id',
																			"Price.price_date='".$monthenddate."'")
															  )
														),
										'conditions' => array('Balance.act ='=>1, 'Balance.fund_id ='=>$fund, 'Balance.ledger_month ='=>$month, 'Balance.ledger_year ='=>$year),
										'order' => array('Balance.account_id')
									);
						
						$this->set('balances', $this->Balance->find('all', $params));
						
						echo debug($this->Balance->find('all', $params));
					}
					else {
						$this->Session->setFlash('Problem with calculating balances.');
					}
				}
			}
			else if (isset($this->params['form']['Lock'])) {
				//try to lock month end balances
				App::import('model','Ledger');
				$ledger = new Ledger();
				if ($ledger->lock($fund, $month, $year)) {
					if ($this->Balance->lock($fund, $month, $year)) {
						$this->Session->setFlash('Month successfully locked.');
					}
				}
			}
		}
		
		//funds dropdown list
		$this->set('funds', $this->Balance->Fund->find('list', array('fields'=>array('Fund.id','Fund.fund_name'),'order'=>array('Fund.fund_name'))));
		
		
		
	}
}
?>