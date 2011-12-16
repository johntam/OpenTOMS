<?php
class HolidaysController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Holidays';

	function index() {		
		if (isset($this->params['pass']['0'])) {
			$country_id = $this->params['pass']['0'];
		}
		elseif (isset($this->data['Holiday']['country_id'])) {
			$country_id = $this->data['Holiday']['country_id'];
		}
		else {
			$country_id = 2;
		}
		
		$params=array(
			'conditions' => array('Holiday.country_id =' => $country_id),
			'order' => array('Holiday.crd')
		);
		
		$this->set('countryid', $country_id);
		$this->set('holidays', $this->Holiday->find('all', $params));
		$this->set('countries', $this->Holiday->Country->find('list', array('fields'=>array('Country.country_name'),'order'=>array('Country.country_name'))));
	}
	
	function add() {
		if (!empty($this->data)) {
			if ($this->Holiday->save($this->data)) {
				$this->Session->setFlash('Holiday date has been saved.');
				$this->redirect(array('action' => 'index', $this->data['Holiday']['country_id']));
			}
		}
	}
}
?>