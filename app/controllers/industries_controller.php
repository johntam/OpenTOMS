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
