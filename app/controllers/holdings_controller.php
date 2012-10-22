<?php
/*
	OpenTOMS - Open Trade Order Management System
	Copyright (C) 2012  JOHN TAM, LPR CONSULTING LLP

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

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
