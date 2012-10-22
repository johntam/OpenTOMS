<?php
/*
	OpenTOMS - Open Trade Order Management System
	Copyright (C) 2012  JOHN TAM, LPR CONSULTING LLP

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

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
