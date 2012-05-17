<?php

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