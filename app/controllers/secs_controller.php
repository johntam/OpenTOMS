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
	
	function edit($id = null) {
		$this->Sec->id = $id;
		$this->set('secTypes', $this->Sec->SecType->find('list', array('fields'=>array('SecType.sec_type_name'))));
		
		if (empty($this->data)) {
			$this->data = $this->Sec->read();
		} else {
			if ($this->Sec->save($this->data)) {
				$this->Session->setFlash('Security has been updated.');
				$this->redirect(array('action' => 'view',$id));
			}
		}
	}
	
	function view($id = null) {
		$this->Sec->id = $id;
		$this->set('sec', $this->Sec->read());
	}

}
?>