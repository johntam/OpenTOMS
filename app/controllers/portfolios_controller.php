<?php
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