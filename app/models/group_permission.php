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

class GroupPermission extends AppModel {
    var $name = 'GroupPermission';
	var $belongsTo = 'Group, Fund';
	
	//return an array of fund ids that this group is allowed to access
	function getAllowedFunds($group_id) {
		$params=array(
			'conditions' => array('GroupPermission.group_id =' => $group_id),
			'contain' => false,
			'fields' => array('GroupPermission.fund_id')
		);
		$data = $this->find('all', $params);
		
		//collapse return array
		$out = array();
		foreach ($data as $d) {
			$out[] = $d['GroupPermission']['fund_id'];
		}
		
		return($out);
	}
}

?>
