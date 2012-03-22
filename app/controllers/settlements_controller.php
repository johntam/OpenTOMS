<?php
class SettlementsController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Settlements';

	function index() {	
	
		if (isset($this->params['pass']['0'])) {
			$sectype_id = $this->params['pass']['0'];
		}
		elseif (isset($this->data['Settlement']['sec_type_id'])) {
			$sectype_id = $this->data['Settlement']['sec_type_id'];
		}
		else {
			$sectype_id = 13;	//Equity
		}
		
		$params=array(
			'conditions' => array('Settlement.sec_type_id =' => $sectype_id),
			'order' => array('Country.country_name')
		);
		
		$this->set('settlements', $this->Settlement->find('all', $params));
		$this->set('sectype_id', $sectype_id);
		$this->set('secTypes', $this->Settlement->SecType->find('list', array('conditions'=>array('SecType.act ='=>1),'fields'=>array('SecType.sec_type_name'),'order'=>array('SecType.sec_type_name'))));
		$this->set('countries', $this->Settlement->Country->find('list', array('fields'=>array('Country.country_name'),'order'=>array('Country.country_name'))));
		
		//Find the default settlement rule for this sec type
		$this->set('default_settlement', $this->Settlement->SecType->default_settlement($sectype_id));
	}
	
	function add() {
		if (!empty($this->data)) {
			if ($this->Settlement->save($this->data)) {
				$this->Session->setFlash('Settlement Rule has been saved.');
				$this->redirect(array('action' => 'index', $this->data['Settlement']['sec_type_id']));
			}
		}
	}
	
	function delete($id, $sectype_id) {
		$this->Settlement->delete($id);
		$this->redirect(array('action' => 'index', $sectype_id));
	}
}
?>