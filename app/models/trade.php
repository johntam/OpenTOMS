<?php

class Trade extends AppModel {
    var $name = 'Trade';
	var $belongsTo = 'Fund, Sec,TradeType,Reason,Broker,Trader';
	
	
}

?>