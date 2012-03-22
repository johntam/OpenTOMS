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
	
	//gets the currency ID of the given security, if not it returns zero
	//function can be used to determine whether a given security id is a currency instrument or not
	function getCurrencyID($secid) {
		$id = $this->find('first', array('conditions'=>array('Currency.sec_id =' => $secid)));
		$id = $id['Currency']['id'];
		return (empty($id) ? 0 : $id);
	}
}
?>