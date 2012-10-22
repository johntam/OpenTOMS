<?php
class IndustriesController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Industries';

	function index() {
		$this->set('industries', $this->Industry->find('all', array('order'=> array('Industry.industry_code'))));
	}
	
	function add() {
		if (!empty($this->data)) {
			if ($this->check_unique()) {
				if ($this->Industry->save($this->data)) {
					$this->Session->setFlash('Industry info has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}
			else {
				$this->Session->setFlash('Sorry, there is already a duplicate industry in the table');
			}
		}
	}
	
	function edit($id = null) {
		if (empty($this->data)) {
			$this->data = $this->Industry->read();
		} else {
			if ($this->check_unique($id)) {
				if ($this->Industry->save($this->data)) {
					$this->Session->setFlash('Industry info has been updated.');
					$this->redirect(array('action' => 'index'));
				}
			}
			else {
				$this->Session->setFlash('Sorry, there is already a duplicate industry in the table');
			}
		}
	}
	
	//Check to see that the data entered is unique for this model
	function check_unique($id = null) {		
		$industry_code = $this->data['Industry']['industry_code'];
		$industry_name = $this->data['Industry']['industry_name'];
		
		$conditions=array(
			'OR' => array(	
						'Industry.industry_code =' => $industry_code,
						'Industry.industry_name =' => $industry_name
					),
			'Industry.id <>' => $id,
		);
	
		$params=array(
			'conditions' => $conditions, 
		);
		
		$num = $this->Industry->find('count', $params);
		if ($num > 0) { 
			return false;
		} 
		else {
			return true;
		}
	}
}
?>