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

class AccountsController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'Accounts';

	function index() {
		$this->set('accounts', $this->Account->find('all'));
	}
	
	function add() {
		if (!empty($this->data)) {
			if ($this->Account->save($this->data)) {
				$this->Session->setFlash('Accounts info has been saved.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
	
	function edit($id = null) {
		if (empty($this->data)) {
			$this->data = $this->Account->read();
		} 
		else {	
			if ($this->Account->save($this->data)) {
				$this->Session->setFlash('Accounts info has been updated.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
	
}
?>
