<?php
class TradesController extends AppController {
	var $name = 'Trades';
	var $funds = array();
	
	
	function index() {
		$this->setchoices();
		$conditions=array(
			'Trade.crd >' => date('Y-m-d', strtotime('-1 week')),
			'Trade.act =' => 1
		);
	
		$params=array(
			'conditions' => $conditions, //array of conditions
			'fields' => array('Trade.id','Trade.oid','Fund.fund_name','Sec.sec_name','TradeType.trade_type','Reason.reason_desc','Broker.broker_name',
								'Trader.trader_name','Currency.currency_iso_code','Trade.quantity','Trade.broker_contact','Trade.trade_date','Trade.price',
								'Trade.cancelled','Trade.executed'),
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
			'fields' => array('Trade.id','Trade.oid','Fund.fund_name','Sec.sec_name','TradeType.trade_type','Reason.reason_desc','Broker.broker_name',
								'Trader.trader_name','Currency.currency_iso_code','Trade.quantity','Trade.broker_contact','Trade.trade_date','Trade.price',
								'Trade.cancelled','Trade.executed'),
			//'recursive' => 1, //int
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
					$this->update_report_table();
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
					$this->update_report_table();
					$this->Session->setFlash('Your trade has been updated.');
					$this->redirect(array('action' => 'index'));
				}
			}
		}
		
	}
	
	//If a trade has been added or changed, then deactivate any reports which have a run_date on or after the trade date of this trade.
	//This is to make sure that any future run reports do not depend on these saved reports which could now be invalid.
	function update_report_table() {
		App::import('model','Report');
		$report = new Report();
		$report->run_date = date('Y-m-d',mktime(0,0,0,$this->data['Trade']['trade_date']['month'],$this->data['Trade']['trade_date']['day'],$this->data['Trade']['trade_date']['year']));
		$report->fund_id = $this->data['Trade']['fund_id'];
		$report->deactivate();
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
		$this->set('tradeTypes', $this->Trade->TradeType->find('list', array('fields'=>array('TradeType.trade_type'),'order'=>array('TradeType.id'))));
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
	
	//calculate settlement date
	function ajax_settdate() {
		$td_year = $this->params['data']['Trade']['trade_date']['year'];
		$td_month = $this->params['data']['Trade']['trade_date']['month'];
		$td_day = $this->params['data']['Trade']['trade_date']['day'];
		$td = mktime(0,0,0,$td_month,$td_day,$td_year);
		$sec_id = $this->params['data']['Trade']['sec_id'];
	
		if (!empty($sec_id)) {
			//Cache data needed from the secs table to improve speed
			if (($settdateCACHE = Cache::read('settdate')) === false) {
				$sett = $this->Trade->Sec->find('all', array('fields' => 'Sec.id, Sec.sec_type_id, Sec.country_id'));
				$settdateCACHE = array(); 
				foreach($sett as $s) { 
					$settdateCACHE[$s['Sec']['id']]['sec_type_id'] = $s['Sec']['sec_type_id'];
					$settdateCACHE[$s['Sec']['id']]['country_id'] = $s['Sec']['country_id'];
				}
				Cache::write('settdate', $settdateCACHE);
			}
			$sec_sectype_id =  $settdateCACHE[$sec_id]['sec_type_id'];
			$sec_country_id = $settdateCACHE[$sec_id]['country_id'];
		
			//Look up the settlement date for this $sec_sectype and $sec_country (from the Settlement model).
			App::import('model','Settlement');
			$settle = new Settlement();
			
			//Find the default settlement rule for this sec type
			$default_settle = $settle->SecType->default_settlement($sec_sectype_id);
			
			//Find any exceptions in the settlements table
			$params=array(
				'conditions' => array(  'Settlement.sec_type_id =' => $sec_sectype_id, 
										'Settlement.country_id =' => $sec_country_id),
				'fields' => array('Settlement.settlement_days')
			);
			$sett_days_find = $settle->find('all', $params);
			if (empty($sett_days_find)) {
				$sett_days = $default_settle;
			}
			else {
				$sett_days = $sett_days_find['0']['Settlement']['settlement_days'];
			}
			
			//Find all holiday dates relevant to the security country
			App::import('model','Holiday');
			$hol = new Holiday();
			$hols = $hol->find('all', array('conditions'=>array('Holiday.country_id =' => $sec_country_id), 'fields'=>array('Holiday.holiday_day','Holiday.holiday_month')));
			$holidays = array();
			foreach ($hols as $h) {
				$holidays[$h['Holiday']['holiday_month']][$h['Holiday']['holiday_day']] = 1;
			}
			
			//Loop through each day starting from the trade date for the required settlement period, skipping any weekends and holidays.
			$final_settle_date = $td;
			while ($sett_days > 0) {
				$final_settle_date = strtotime(date("Y-m-d", $final_settle_date) . " +1 day");
				
				if ((date('l', $final_settle_date) == 'Saturday') || (date('l', $final_settle_date) == 'Sunday')) {
					$sett_days++;
				}
				
				if (isset($holidays[date('m', $final_settle_date)][date('d', $final_settle_date)])) {
					$sett_days++;
				}
				$sett_days--;
			}
			
			
			$this->set('settdate', date('Y-m-d', $final_settle_date));
			
		}
		else {
			$this->set('settdate', null);
		}
		
		$this->render('/elements/ajax_settdate', 'ajax');
	}
	
	//Check price entered is not too far away from a price in the price history table
	function ajax_checkprice() {
		$exec_price = $this->params['data']['Trade']['execution_price'];
		$trade_date = $this->params['data']['Trade']['trade_date'];
		$sec_id = $this->params['data']['Trade']['sec_id'];
		$this->set('checkprice', null);
		
		if (!empty($exec_price) && !empty($sec_id)) {
			//Check price using Price Model
			App::import('model','Price');
			$price = new Price();
			$stored_price = $price->get_price($sec_id, $trade_date);
			
			if ($stored_price) {
				//Check to see if the entered price is more than 10% away from the stored price.
				if (abs(($stored_price-$exec_price)/$stored_price) >0.1) {
					$this->set('checkprice', 'Warning: The execution price entered is more than 10% away from the price history table.');
				}
			}
		}
		
		$this->render('/elements/ajax_checkprice', 'ajax');
	}
	
}

?>