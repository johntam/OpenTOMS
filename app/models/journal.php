<?php

class Journal extends AppModel {
    var $name = 'Journal';
	var $useTable = 'trades';
	var $belongsTo = 'Fund,TradeType,Currency';
}
?>