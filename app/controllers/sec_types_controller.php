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

class SecTypesController extends AppController {
	var $helpers = array ('Html','Form');
	var $name = 'SecTypes';

	function index() {
		$this->set('sectypes', $this->SecType->find('all'));
	}
	
	function add() {
		if (!empty($this->data)) {
			if ($this->SecType->save($this->data)) {
				$this->Session->setFlash('Security type has been saved.');
				$this->redirect(array('action' => 'index'));
			}
		}
	}
	
	function activate($id) {
		$this->SecType->status($id, 1);
		$this->redirect(array('action' => 'index'));
	}
	
	
	function deactivate($id) {
		$this->SecType->status($id, 0);
		$this->redirect(array('action' => 'index'));
	}
}
?>
