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

class Holding extends AppModel {
    var $name = 'Holding';
	var $useTable = 'trades';
	var $belongsTo ='Fund, Sec';
	var $actsAs = array('Containable');
	
	//get the holdings as at this date
	function getHoldings($fund, $date) {
		$holdings = $this->find('all', array('conditions'=>array('Holding.fund_id =' => $fund,
									'Holding.trade_date <=' => $date,
									'Holding.cancelled =' => 0,
									'Holding.executed =' => 1,
									'Holding.act =' => 1,
									'Holding.trade_type_id <' => 5),	//only consider buy and sells, not dividend income, etc.
									'fields'=>array('Sec.sec_name', 'SUM(Holding.quantity) AS quantity'),
									'group'=>array('Sec.id', 'Sec.sec_name HAVING quantity <> 0'),
									'order'=>array('Sec.sec_type_id="2" ASC', 'Sec.sec_name ASC'),
									'contain' => array('Sec')));		
		return $holdings;
	}
}

?>
