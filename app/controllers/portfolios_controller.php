<?php
class PortfoliosController extends AppController {
	var $name = 'Portfolios';
	
	
	function index() {
		//$trades = array(); 
		//	foreach($this->params as $t) {
		//		if (isset($t['Trade']) && is_array($t['Trade'])) {
		//			array_push($trades, $t);
		//			}
		//	}
		$portfolio_data = $this->Session->read('portfolio_data');	
		$this->set('portfolio_data', $portfolio_data);
	}
	
	
}

?>