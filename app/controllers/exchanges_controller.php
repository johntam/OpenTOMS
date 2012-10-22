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

class ExchangesController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Exchanges';

	function index() {
		$this->set('exchanges', $this->Exchange->find('all'));
	}
	
	function add() {
		if (!empty($this->data)) {
			if ($this->check_unique()) {
				if ($this->Exchange->save($this->data)) {
					$this->Session->setFlash('Exchange info has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}
			else {
				$this->Session->setFlash('Sorry, there is already a duplicate exchange in the table');
			}
		}
	}
	
	function edit($id = null) {
		if (empty($this->data)) {
			$this->data = $this->Exchange->read();
		} else {
			if ($this->check_unique($id)) {
				if ($this->Exchange->save($this->data)) {
					$this->Session->setFlash('Exchange info has been updated.');
					$this->redirect(array('action' => 'index'));
				}
			}
			else {
				$this->Session->setFlash('Sorry, there is already a duplicate exchange in the table');
			}
		}
	}
	
	//Check to see that the data entered is unique for this model
	function check_unique($id = null) {		
		$exchange_code = $this->data['Exchange']['exchange_code'];
		$exchange_name = $this->data['Exchange']['exchange_name'];
		
		$conditions=array(
			'OR' => array(	
						'Exchange.exchange_code =' => $exchange_code,
						'Exchange.exchange_name =' => $exchange_name
					),
			'Exchange.id <>' => $id,
		);
	
		$params=array(
			'conditions' => $conditions, 
		);
		
		$num = $this->Exchange->find('count', $params);
		if ($num > 0) { 
			return false;
		} 
		else {
			return true;
		}
	}
}
?>
