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

class Account extends AppModel {
    var $name = 'Account';
	
	function getNamed($name) {
		$result = $this->find('first', array('fields'=>array('Account.id'),'conditions'=>array('account_name'=>$name)));
		
		if (empty($result)) {
			return 0;
		}
		else {
			return $result['Account']['id'];
		}
	}
	
	//given a posting value to a specific account, this function returns the credit debit journal entry
	function debitcredit($id, $value) {
		$type = $this->read('account_type', $id);
		$type = $type['Account']['account_type'];
		
		if (($type == 'Assets') || ($type == 'Expenses')) {
			if ($value >= 0) {
				return (array($value, 0));
			}
			else {
				return (array(0, abs($value)));
			}
		}
		else if (($type == 'Owners Equity') || ($type == 'Income')) {
			if ($value >= 0) {
				return (array(0, $value));
			}
			else {
				return (array(abs($value), 0));
			}
		}
		else {
			return null;
		}
	}
}
?>
