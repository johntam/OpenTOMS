<?php
class SecsController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Secs';

	function index() {
		$this->set('secs', $this->Sec->find('all'));
	}
	
	function add() {
		if (!empty($this->data)) {
			if ($this->Sec->save($this->data)) {
				$this->Session->setFlash('Your post has been saved.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
}
?>