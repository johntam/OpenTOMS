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

class Currency extends AppModel {
    var $name = 'Currency';
	var $belongsTo = 'Sec';
	var $validate = array(
		'currency_iso_code' => array('rule' => 'notEmpty', 'message' => 'Code cannot be blank'),
		'currency_name' => array('rule' => 'notEmpty', 'message' => 'Name cannot be blank')
	);
	
	//return the secs table id corresponding to this currency id
	function getsecid($id) {
		$secid = $this->find('first', array('conditions'=>array('Currency.id ='=>$id), 'fields'=>array('Sec.id')));
		if (empty($secid)) {
			return(false);
		}
		else {
			return($secid['Sec']['id']);
		}
	}
	
	//gets the currency ID of the given security, if not it returns zero
	//function can be used to determine whether a given security id is a currency instrument or not
	function getCurrencyID($secid) {
		$id = $this->find('first', array('conditions'=>array('Currency.sec_id =' => $secid)));
		$id = $id['Currency']['id'];
		return (empty($id) ? 0 : $id);
	}
}
?>
