<?php

class Trade extends AppModel {
    var $name = 'Trade';
	var $belongsTo = 'Fund,Sec,TradeType,Reason,Broker,Trader,Currency,Custodian';
	var $validate = array(
		'quantity' => array('rule' => 'notEmpty', 'message' => 'This field cannot be blank'),
		'sec_id' => array('rule' => 'notEmpty', 'message' => 'Must choose a security'),
		'currency_id' => array('rule' => 'notEmpty', 'message' => 'Must choose a currency'),
		'commission' => array('rule' => array('comparison', '>=', 0), 'message' => 'Must be a positive number'),
		'tax' => array('rule' => array('comparison', '>=', 0), 'message' => 'Must be a positive number'),
		'other_costs' => array('rule' => array('comparison', '>=', 0), 'message' => 'Must be a positive number'),
		'accrued' => array('rule' => array('comparison', '>=', 0), 'message' => 'Must be a positive number'),
		'trade_date' => array('rule' => 'notEmpty', 'message' => 'This field cannot be blank')
	);
}
?>