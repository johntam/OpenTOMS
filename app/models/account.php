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
}
?>