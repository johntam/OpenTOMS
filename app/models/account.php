<?php

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