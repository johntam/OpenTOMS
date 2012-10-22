<?php
class CountriesController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Countries';

	function index() {
		$this->set('countries', $this->Country->find('all'));
	}
	
	function add() {
		if (!empty($this->data)) {
			if ($this->check_unique()) {
				if ($this->Country->save($this->data)) {
					$this->Session->setFlash('Country info has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}
			else {
				$this->Session->setFlash('Sorry, there is already a duplicate country in the table');
			}
		}
	}
	
	function edit($id = null) {
		if (empty($this->data)) {
			$this->data = $this->Country->read();
		} else {
			if ($this->check_unique($id)) {
				if ($this->Country->save($this->data)) {
					$this->Session->setFlash('Country info has been updated.');
					$this->redirect(array('action' => 'index'));
				}
			}
			else {
				$this->Session->setFlash('Sorry, there is already a duplicate country in the table');
			}
		}
	}
	
	//Check to see that the data entered is unique for this model
	function check_unique($id = null) {		
		$country_code = $this->data['Country']['country_code'];
		$country_name = $this->data['Country']['country_name'];
		
		$conditions=array(
			'OR' => array(	
						'Country.country_code =' => $country_code,
						'Country.country_name =' => $country_name
					),
			'Country.id <>' => $id,
		);
	
		$params=array(
			'conditions' => $conditions, 
		);
		
		$num = $this->Country->find('count', $params);
		if ($num > 0) { 
			return false;
		} 
		else {
			return true;
		}
	}
}
?>