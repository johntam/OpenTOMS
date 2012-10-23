<?php
/*
	OpenTOMS - Open Trade Order Management System
	Copyright (C) 2012  JOHN TAM, LPR CONSULTING LLP

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

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
			'order' => array('Holiday.holiday_month','Holiday.holiday_day')
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
	
	function edit($id, $country_id) {
		if (!empty($this->data)) {
			if ($this->Holiday->save($this->data)) {
				$this->Session->setFlash('Holiday date has been updated.');
				$this->redirect(array('action' => 'index', $this->data['Holiday']['country_id']));
			}
		}
		else {
			$this->set('holidays', $this->Holiday->find('all', array('conditions'=>array('Holiday.country_id =' => $country_id), 'order'=>array('Holiday.holiday_month','Holiday.holiday_day'))));
			$this->set('countries', $this->Holiday->Country->find('list', array('fields'=>array('Country.country_name'),'order'=>array('Country.country_name'))));
			$this->set('countryid', $country_id);
			$this->set('editmode', $id);
			$this->render('index');
		}
	}
	
	function delete($id,$country_id) {
		$this->Holiday->delete($id);
		$this->redirect(array('action' => 'index', $country_id));
	}
}
?>
