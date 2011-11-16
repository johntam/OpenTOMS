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
		$trades = $this->Session->read('trades');	
		$this->set('trades', $trades);
	}
}

?>