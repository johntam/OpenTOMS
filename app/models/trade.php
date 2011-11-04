<?php

class Trade extends AppModel {
    var $name = 'Trade';
	var $belongsTo = 'Fund,Sec,TradeType,Reason,Broker,Trader,Currency';
	var $validate = array(
		'quantity' => array('rule' => 'notEmpty', 'message' => 'This field cannot be blank'),
		'price' => array('rule' => 'notEmpty', 'message' => 'This field cannot be blank'),
		'sec_id' => array('rule' => 'notEmpty', 'message' => 'Must choose a security'),
		'currency_id' => array('rule' => 'notEmpty', 'message' => 'Must choose a currency')
	);
}

?>