<?php

class Ledger extends AppModel {
    var $name = 'Ledger';
	var $belongsTo ='Account, Trade, Fund, Currency';
}

?>