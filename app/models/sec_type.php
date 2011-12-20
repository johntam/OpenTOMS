<?php

class SecType extends AppModel {
    var $name = 'SecType';
	
	function default_settlement($id) {
		$data = $this->read('bond', $id);
	
		if ($data['SecType']['bond'] == 1) {
			return 2;
		}
		else {
			return 3;
		}
	}
}

?>