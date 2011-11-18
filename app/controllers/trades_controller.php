<?php
class TradesController extends AppController {
	var $name = 'Trades';
	var $funds = array();
	
	
	function index() {
		$this->setchoices();
		$conditions=array(
			'Trade.crd >' => strtotime('-1 week'),
			'Trade.act =' => 1
		);
	
		$params=array(
			'conditions' => $conditions, //array of conditions
			'order' => array('Trade.crd DESC') //string or array defining order
		);
		
		$this->set('trades', $this->Trade->find('all', $params));
		$this->set('title_for_layout', 'View Trades');
	}
	
	
	
	function indexFiltered() {		
		$this->setchoices();
		
		$conditions=array(
			'Trade.crd >' => date('Y-m-d', strtotime($this->data['Trade']['daterange'])),
			'Trade.act =' => 1
		);
		
		if (!empty($this->data['Trade']['fundchosen'])) {
			$conditions['Trade.fund_id ='] = $this->data['Trade']['fundchosen'];
		}
		
		if (!empty($this->data['Trade']['brokerchosen'])) {
			$conditions['Trade.broker_id ='] = $this->data['Trade']['brokerchosen'];
		}		
	
		$params=array(
			'conditions' => $conditions, //array of conditions
			//'recursive' => 1, //int
			//'fields' => array('Model.field1', 'DISTINCT Model.field2'), //array of field names
			'order' => array('Trade.crd DESC') //string or array defining order
			//'group' => array('Model.field'), //fields to GROUP BY
			//'limit' => n, //int
			//'page' => n, //int
			//'offset'=>n, //int   
			//'callbacks' => true //other possible values are false, 'before', 'after'
		);
		
		$this->set('trades', $this->Trade->find('all', $params));
		$this->set('title_for_layout', 'View Trades');
	}
	
	
	
	function add() {
		$this->setchoices();
	
		if (!empty($this->data)) {			
			if ($this->Trade->save($this->data)) {
				//Do a second update to the same record to set the oid and act fields
				$id = $this->Trade->id;
				if ($this->Trade->saveField('act',1) && $this->Trade->saveField('oid',$id)) {
					$this->Session->setFlash('Your trade has been saved.');
					//$this->disableCache();	//clear cache for AJAX calls
					$this->redirect(array('action' => 'add'));
				}
			}
			
		}
	}
	
	
	
	function edit($id = null) {
		$this->setchoices();
		$this->Trade->id = $id;
		
		if (empty($this->data)) {
			$this->data = $this->Trade->read();
		} else {
			$id = $this->Trade->id;
			unset($this->data['Trade']['id']);	//remove id so that Cake will create a new model record
			$this->data['Trade']['act'] = 1;
			$this->data['Trade']['crd'] = DboSource::expression('NOW()');	//weird DEFAULT TIMESTAMP not working
			$this->Trade->create();
			
			if ($this->Trade->save($this->data)) {
				$this->Trade->id = $id;
				//Clear the active flag on the trade that was edited
				if ($this->Trade->saveField('act',0)) {
					$this->Session->setFlash('Your trade has been updated.');
					$this->redirect(array('action' => 'index'));
				}
			}
		}
		
	}
	
	
	function view($oid = null) {
		$conditions=array(
			'Trade.oid =' => $oid
		);
	
		$params=array(
			'conditions' => $conditions, //array of conditions
			'limit' => 1,
			'order' => array('Trade.crd DESC') //string or array defining order
		);
			
		$this->paginate = $params;
		$data=$this->paginate('Trade');
		$this->set(compact('data'));
	}
	
	function setchoices() {
		//Could be a lot of securities so cache this list
		if (($secsCACHE = Cache::read('secs')) === false) {
			$secsCACHE = $this->Trade->Sec->find('list', array('fields'=>array('Sec.sec_name'),'order'=>array('Sec.sec_name')));
			Cache::write('secs', $secsCACHE);
		}

		$this->set('secs', $secsCACHE);
		$this->set('funds', $this->Trade->Fund->find('list', array('fields'=>array('Fund.fund_name'),'order'=>array('Fund.fund_name'))));
		$this->set('tradeTypes', $this->Trade->TradeType->find('list', array('fields'=>array('TradeType.trade_type'),'order'=>array('TradeType.trade_type'))));
		$this->set('reasons', $this->Trade->Reason->find('list', array('fields'=>array('Reason.reason_desc'),'order'=>array('Reason.reason_desc'))));
		$this->set('brokers', $this->Trade->Broker->find('list', array('fields'=>array('Broker.broker_name'),'order'=>array('Broker.broker_name'))));
		$this->set('traders', $this->Trade->Trader->find('list', array('fields'=>array('Trader.trader_name'),'order'=>array('Trader.trader_name'))));
		$this->set('currencies', $this->Trade->Currency->find('list', array('fields'=>array('Currency.currency_iso_code'),'order'=>array('Currency.currency_iso_code'))));
	}
	
	
	function ajax_ccydropdown() {
		// Fill select form field after Ajax request.
		//The following caches a [Security Id, Currency] table for use when the user selects from the security dropdown list
		if (($secid_ccyCACHE = Cache::read('secid_ccy')) === false) {
			$sec_ccy = $this->Trade->Sec->find('all', array('fields' => 'Sec.id, Currency.currency_iso_code'));
			$secid_ccyCACHE = array(); 
			foreach($sec_ccy as $c) { 
				$secid_ccyCACHE[$c['Sec']['id']] = $c['Currency']['currency_iso_code']; 
			}
			Cache::write('secid_ccy', $secid_ccyCACHE);
		}
		
		//cache the currency list
		if (($currenciesCACHE = Cache::read('currencies')) === false) {
			$currenciesCACHE = $this->Trade->Currency->find('list', array('fields'=>array('Currency.currency_iso_code'),'order'=>array('Currency.currency_iso_code')));
			Cache::write('currencies', $currenciesCACHE);
		}
		
		//Retrieve the id of the security selected
		$secid = $this->params['url']['data']['Trade']['sec_id'];
		$this->set('selected', $secid_ccyCACHE[$secid]);
		$this->set('options', $currenciesCACHE);
		$this->render('/elements/ajax_dropdown', 'ajax');
	}
	
	//work out the broker's commission
	function ajax_commission() {
		$qty = $this->params['url']['data']['Trade']['quantity'];
		$price = $this->params['url']['data']['Trade']['execution_price'];
		$secid = $this->params['url']['data']['Trade']['sec_id'];
		$brokerid = $this->params['url']['data']['Trade']['broker_id'];
		$valpoint = $this->Trade->Sec->find('first', array('conditions'=> array('Sec.id =' => $secid)));
		$brokercomm = $this->Trade->Broker->find('first', array('conditions'=> array('Broker.id =' => $brokerid)));
		
		$this->set('commission', round(abs($qty) * $price * $valpoint['Sec']['valpoint'] * $brokercomm['Broker']['commission_rate'],4));
		$this->render('/elements/ajax_commission', 'ajax');
	}

	//tax costs, specifically stamp duty on purchases in the UK
	function ajax_tax() {
		$qty = $this->params['url']['data']['Trade']['quantity'];
		$price = $this->params['url']['data']['Trade']['execution_price'];
		$secid = $this->params['url']['data']['Trade']['sec_id'];
		$ccyid = $this->params['url']['data']['Trade']['currency_id'];
		$ttid = $this->params['url']['data']['Trade']['trade_type_id'];
		$valpoint = $this->Trade->Sec->find('first', array('conditions'=> array('Sec.id =' => $secid)));
		$ccy = $this->Trade->Currency->find('first', array('conditions'=> array('Currency.id =' => $ccyid)));
		$tt = $this->Trade->TradeType->find('first', array('conditions'=> array('TradeType.id =' => $ttid)));
		
		if ((strtolower(substr($tt['TradeType']['trade_type'],0,3)) == 'buy') &&
			(strtolower(substr($ccy['Currency']['currency_iso_code'],0,3)) == 'gbp')) {
			$this->set('tax', round(abs($qty) * $price * $valpoint['Sec']['valpoint'] * 0.005,4));
		}
		else {
			$this->set('tax', 0);
		}
		
		$this->render('/elements/ajax_tax', 'ajax');
	}
	
	
	//other costs, most notably the PTM Levy in the UK
	function ajax_othercosts() {
		$qty = $this->params['url']['data']['Trade']['quantity'];
		$price = $this->params['url']['data']['Trade']['execution_price'];
		$secid = $this->params['url']['data']['Trade']['sec_id'];
		$ccyid = $this->params['url']['data']['Trade']['currency_id'];
		$valpoint = $this->Trade->Sec->find('first', array('conditions'=> array('Sec.id =' => $secid)));
		$ccy = $this->Trade->Currency->find('first', array('conditions'=> array('Currency.id =' => $ccyid)));
		
		if ((abs($qty * $price * $valpoint['Sec']['valpoint']) > 10000) &&
			(strtolower(substr($ccy['Currency']['currency_iso_code'],0,3)) == 'gbp')) {
			$this->set('othercosts', 1);
		}
		else {
			$this->set('othercosts', 0);
		}
		
		$this->render('/elements/ajax_othercosts', 'ajax');
	}
	
	//If the trade type is a sell, then make sure that the quantity is a negative number
	function ajax_quantity() {
		$ttid = $this->params['form']['tradetype'];
		$qty = $this->params['form']['quantity'];
		$is_sell = ($this->Trade->TradeType->find('count', array('conditions'=>array('TradeType.id =' => $ttid, 'TradeType.trade_type LIKE' => 'sell%'))) > 0);
	
		if ($is_sell) {
			$quantity = -abs($qty);
		}
		else {
			$quantity = abs($qty);
		}
	
		$this->set('quantity', $quantity);
		$this->render('/elements/ajax_quantity', 'ajax');
	}
	
	
	//Calculate the total consideration figure
	function ajax_consid() {
		$commission = $this->params['data']['Trade']['commission'];
		$tax = $this->params['data']['Trade']['tax'];
		$othercosts = $this->params['data']['Trade']['other_costs'];
		$qty = $this->params['data']['Trade']['quantity'];
		$price = $this->params['data']['Trade']['execution_price'];
		$ttid = $this->params['data']['Trade']['trade_type_id'];
		$secid = $this->params['data']['Trade']['sec_id'];
		
		if (!empty($secid) && !empty($ttid)) {
			//cache valpoints for speed
			if (($valpointCACHE = Cache::read('valpoint')) === false) {
				$readdb = $this->Trade->Sec->find('all', array('fields' => array('Sec.id','Sec.valpoint')));
				$valpointCACHE = array(); 
				foreach($readdb as $c) { 
					$valpointCACHE[$c['Sec']['id']] = $c['Sec']['valpoint']; 
				}
				Cache::write('valpoint', $valpointCACHE);
			}
			$valpoint = $valpointCACHE[$secid];
		
			//Check to see if this trade-type is a credit/debit to the trading cash ledger
			if (($creditCACHE = Cache::read('creditdebit')) === false) {
				$readdb = $this->Trade->TradeType->find('all', array('fields' => array('TradeType.id', 'TradeType.credit_debit')));
				$creditCACHE = array(); 
				foreach($readdb as $c) { 
					$creditCACHE[$c['TradeType']['id']] = $c['TradeType']['credit_debit']; 
				}
				Cache::write('creditdebit', $creditCACHE);
			}
			$credit = $creditCACHE[$ttid];

			//handle cashflow differently for buys and sells
			if ($credit == 'credit') {
				$consid = abs($qty * $price * $valpoint) - $commission - $tax - $othercosts;
			}
			else {
				$consid = -abs($qty * $price * $valpoint) - $commission - $tax - $othercosts;
			}
		
			$consid = round($consid, 4);	
			$this->set('consid', $consid);
			$this->render('/elements/ajax_consid', 'ajax');
		}
		else {
			$this->autoRender=false;
		}
	}
}

?>