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

class PortfoliosController extends AppController {
	var $name = 'Portfolios';
	
	function index() {
		$portfolio_data = $this->Session->read('portfolio_data');
		$this->set('portfolio_data', $portfolio_data);
		
		$report_type = $this->params['pass']['0'];
		switch ($report_type) {
			case 'Position':
				$this->render('position');
				break;
			case 'NAV':
				$this->render('nav');
				break;
		}
	}
}
?>
