<?php
class SecsController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Secs';

	function index() {		
		if (!empty($this->params['pass'])) {
			$a = $this->params['pass'][0];
		} else {
			$a = 'A';
		};
		
		$conditions=array(
			'Sec.sec_name LIKE ' => $a.'%'
		);
	
		$params=array(
			'conditions' => $conditions, 
			'fields' => array('Sec.id', 'Sec.sec_name', 'ticker', 'tradarid', 'currency', 'valpoint'),
			'order' => array('Sec.sec_name ASC') 
		);
		
		$this->set('secs', $this->Sec->find('all', $params));
	}
	
	function add() {
		if (!empty($this->data)) {
			if ($this->Sec->save($this->data)) {
				$this->Session->setFlash('Security has been saved.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
}
?>