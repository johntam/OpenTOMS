<?php
class ReasonsController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Reasons';

	function index() {
		$this->set('reasons', $this->Reason->find('all'));
	}
	
	function add() {
		if (!empty($this->data)) {
			if ($this->Reason->save($this->data)) {
				$this->Session->setFlash('Your post has been saved.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
}
?>