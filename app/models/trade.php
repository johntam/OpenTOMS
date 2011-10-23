<?php

class Trade extends AppModel {
    var $name = 'Trade';
	//var $belongsTo = 'Fund, Sec,TradeType,Reason,Broker,Trader';
	
	var $belongsTo = array(
		'Fund' => array(
		'className' => 'Fund',
		'foreignKey' => 'fund_id'
		),
		'Sec' => array(
		'className' => 'Sec',
		'foreignKey' => 'sec_id'
		),
		'TradeType' => array(
		'className' => 'TradeType',
		'foreignKey' => 'trade_type_id'
		),
		'Reason' => array(
		'className' => 'Reason',
		'foreignKey' => 'reason_id'
		),
		'Broker' => array(
		'className' => 'Broker',
		'foreignKey' => 'broker_id'
		),
		'Trader' => array(
		'className' => 'Trader',
		'foreignKey' => 'trader_id'
		)
	);
}

?>