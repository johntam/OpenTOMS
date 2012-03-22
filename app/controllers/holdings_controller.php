<?php
class HoldingsController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Holdings';
	
	function index() {
		$funds = $this->Holding->Fund->find('list', array('fields'=>array('Fund.id','Fund.fund_name'),'order'=>array('Fund.fund_name')));
		
		if (empty($this->data)) {
			//just entered this page, try to retrieve fund name from trade blotter if possible
			if ($this->Session->check('fund_chosen')) {
				$fund = $this->Session->read('fund_chosen');
			}
			else {
				$fund = key($funds);	//get key of first element in array $funds
			}
			//run report for today's date initially
			$date = date('Y-m-d');
			
			//pass information to the form
			$this->data = array('Holding'=>array('fund_id'=>$fund, 'holdings_date'=>$date));
		}
		else {
			$fund = $this->data['Holding']['fund_id'];
			$date = $this->data['Holding']['holdings_date'];
		}
		
		//get holdings as at date
		$this->set('holdings', $this->Holding->getHoldings($fund, $date));
		
		//set choices for fund drop down list
		$this->set('funds', $funds);
	}
}
?>