<?php

class TradeType extends AppModel {
    var $name = 'TradeType';
	var $belongsTo = array(
							'Debit' => array(
								'className' => 'Account',
								'foreignKey' => 'debit_account_id'
							),
							'Credit' => array(
								'className' => 'Account',
								'foreignKey' => 'credit_account_id'
							)
    );
}

?>