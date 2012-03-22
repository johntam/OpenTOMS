<?php

class SecType extends AppModel {
    var $name = 'SecType';
	
	function default_settlement($id) {
		$data = $this->read(null, $id);
	
		if ($data['SecType']['bond'] == 1) {
			return 2;
		}
		else if ($data['SecType']['exchrate'] == 1) {
			return 2;
		}
		else if ($data['SecType']['cfd'] == 1) {
			return 0;
		}
		else {
			return 3;
		}
	}
	
	//set the act flag in the model table
	function status($id, $val) {
		$this->id = $id;
		$this->saveField('act', $val);
	}
}

?>