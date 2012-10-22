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

class Fund extends AppModel {
    var $name = 'Fund';
	var $belongsTo ='Currency';
	
	function get_fund_ccy($fund_id) {
		$this->id = $fund_id;
		$this->read();
		return($this->data['Fund']['currency_id']);
	}
	
	function get_fund_ccy_name($fund_id) {
		$ccyid = $this->read('currency_id', $fund_id);
		$ccyid = $ccyid['Fund']['currency_id'];
		$ccy = $this->Currency->read('currency_iso_code', $ccyid);
		return($ccy['Currency']['currency_iso_code']);
	}
}

?>
