<?php

class Currency extends AppModel {
    var $name = 'Currency';
	var $belongsTo = 'Sec';
	var $validate = array(
		'currency_iso_code' => array('rule' => 'notEmpty', 'message' => 'Code cannot be blank'),
		'currency_name' => array('rule' => 'notEmpty', 'message' => 'Name cannot be blank')
	);
	
	//return the secs table id corresponding to this currency id
	function getsecid($id) {
		$secid = $this->find('first', array('conditions'=>array('Currency.id ='=>$id), 'fields'=>array('Sec.id')));
		if (empty($secid)) {
			return(false);
		}
		else {
			return($secid['Sec']['id']);
		}
	}
}
?>