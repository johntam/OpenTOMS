<?php

class Currency extends AppModel {
    var $name = 'Currency';
	var $validate = array(
		'currency_iso_code' => array('rule' => 'notEmpty', 'message' => 'Code cannot be blank'),
		'currency_name' => array('rule' => 'notEmpty', 'message' => 'Name cannot be blank')
	);
}
?>