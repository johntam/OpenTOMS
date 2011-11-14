<!-- File: /app/views/trades/add.ctp -->	

<?php echo $this->Form->create('Trade'); ?>

<table>	
	<tr><td>
		<h1>Add Trade</h1>
	</td>
	</tr>
	<tr class="highlight">
		<td>Fund</td>
		<td>Security Name</td>
		<td>Notes</td>
		<td>Trade Currency</td>
	</tr>
	
		<tr class="altrow">
			<td><?php echo $this->Form->input('fund_id',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('sec_id',array('label'=>false, 'empty'=>'Select Security')); ?></td>
			<td><?php echo $this->Form->input('notes',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('currency_id',array('label'=>false, 'empty'=>' ')); ?></td>
		</tr>
	
	<tr class="highlight">
		<td>Trade Type</td>
		<td>Quantity</td>
		<td>Order Price</td>
		<td>Execution Price</td>
	</tr>
	
		<tr class="altrow">
			<td><?php echo $this->Form->input('trade_type_id',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('quantity',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('price',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('execution_price',array('label'=>false)); ?></td>
		</tr>
	
	<tr class="highlight">
		<td>Decision Time</td>
		<td>Trade Date</td>
		<td>Settlement Date</td>
		<td>Trader</td>
	</tr>
	
		<tr class="altrow">
			<td><?php echo $this->Form->input('decision_time',array('label'=>false,'empty'=>' ')); ?></td>
			<td><?php echo $this->Form->input('trade_date',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('settlement_date',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('trader_id',array('label'=>false)); ?></td>
		</tr>
		
	<tr class="highlight">
		<td>Broker</td>
		<td>Broker Contact</td>
		<td>Order Time</td>
		<td>Trade Reason</td>
	</tr>
	
		<tr class="altrow">
			<td><?php echo $this->Form->input('broker_id',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('broker_contact',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('order_time',array('label'=>false,'empty'=>' ')); ?></td>
			<td><?php echo $this->Form->input('reason_id',array('label'=>false)); ?></td>
		</tr>
	

	<tr class="highlight">
		<td>Commission</td>
		<td>Tax</td>
		<td>Other Costs</td>
		<td>Total Consideration</td>
	</tr>

		<tr class="altrow">
			<td><?php echo $this->Form->input('commission',array('label'=>false, 'div'=>array('id'=>'TradeCommId'))); ?></td>
			<td><?php echo $this->Form->input('tax',array('label'=>false, 'div'=>array('id'=>'TradeTaxId'))); ?></td>
			<td><?php echo $this->Form->input('other_costs',array('label'=>false, 'div'=>array('id'=>'TradeOtherCostsId'))); ?></td>
			<td><?php echo $this->Form->input('consideration',array('label'=>false, 'div'=>array('id'=>'TradeConsiderationId'))); ?></td>
		</tr>
	
	
	<tr class="highlight">
		<td>Executed</td>
		<td>Cancelled</td>
		<td></td>
		<td></td>
	</tr>

		<tr class="altrow">
			<td><?php echo $this->Form->input('executed',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('cancelled',array('label'=>false)); ?></td>
			<td></td>
			<td></td>
		</tr>
		
	
	<?php 
		//$this->Js->get('#TradeSecId')->event(
		//	'change',
		//	$this->Js->request(
		//		array('controller'=>'trades','action'=>'ajax_ccydropdown'),
		//		array('update' => '#TradeCurrencyId', 'dataExpression' => true, 'data' => '$("#TradeSecId").serialize()')
		//	)
		//);
		
		//$this->Js->get('#TradeQuantity')->event(
		//	'change',
		//	$this->Js->request(
		//		array('controller'=>'trades','action'=>'ajax_commission'),
		//		array('update' => '#TradeCommId', 'dataExpression' => true, 'data' => '$("#TradeAddForm").serialize()')
		//	)
		//);
		
		//$this->Js->get('#TradeExecutionPrice')->event(
		//	'change',
		//	$this->Js->request(
		//		array('controller'=>'trades','action'=>'ajax_commission'),
		//		array('update' => '#TradeCommId', 'dataExpression' => true, 'data' => '$("#TradeAddForm").serialize()')
		//	)
		//);
	?>
	
	<tr><td colspan="4" style="text-align: center;"><?php echo $this->Form->end('Save Trade'); ?></td></tr>
</table>

<script type="text/javascript">
	setInterval(function() { 
	$(document).ready(function() {
			var comm = parseFloat($("#TradeCommission").val());
			var tax = parseFloat($("#TradeTax").val());
			var othercosts = parseFloat($("#TradeOtherCosts").val());
			var totconsid = comm + tax + othercosts;
			if (!isNaN(totconsid)) {
				$("#TradeConsideration").val(totconsid);
			}
		}); 
	}, 500);
	
	$(document).ready(function() {
		$("#TradeExecutionPrice").attr("readonly", "readonly");
		$("#TradeExecutionPrice").css("background-color","silver");
	
		$("#TradeSecId").change(function() {
			$("#TradeCurrencyId").load("/trades/ajax_ccydropdown?" + (new Date()).getTime() , $("#TradeSecId").serialize());
			$("#TradeQuantity").val("");
			$("#TradeExecutionPrice").val("");
			clearcosts();
		});
		
		$("#TradeQuantity").change(function() {
			var checked = $("#TradeExecuted:checked").val() != undefined;
			if (checked) {
				$("#TradeCommId").load("/trades/ajax_commission?" + (new Date()).getTime() , $("#TradeAddForm").serialize());
				$("#TradeTaxId").load("/trades/ajax_tax?" + (new Date()).getTime() , $("#TradeAddForm").serialize());
				$("#TradeOtherCostsId").load("/trades/ajax_othercosts?" + (new Date()).getTime() , $("#TradeAddForm").serialize());
			}
		});
		
		$("#TradeExecutionPrice").change(function() {
			var checked = $("#TradeExecuted:checked").val() != undefined;
			if (checked) {
				$("#TradeCommId").load("/trades/ajax_commission?" + (new Date()).getTime() , $("#TradeAddForm").serialize());
				$("#TradeTaxId").load("/trades/ajax_tax?" + (new Date()).getTime() , $("#TradeAddForm").serialize());
				$("#TradeOtherCostsId").load("/trades/ajax_othercosts?" + (new Date()).getTime() , $("#TradeAddForm").serialize());
			}
		});
		
		$("#TradeExecuted").click(function() {
			var checked = $("#TradeExecuted:checked").val() != undefined;
			if (!checked) {
				$("#TradeExecutionPrice").val("");
				$("#TradeExecutionPrice").attr("readonly", "readonly");
				$("#TradeExecutionPrice").css("background-color","silver");
				clearcosts();
			}
			else {
				$("#TradeExecutionPrice").removeAttr("readonly");
				$("#TradeExecutionPrice").css("background-color","white");
			}
		});
		
		$("#TradeBrokerId").change(function() {
			var checked = $("#TradeExecuted:checked").val() != undefined;
			if (checked) {
				$("#TradeCommId").load("/trades/ajax_commission?" + (new Date()).getTime() , $("#TradeAddForm").serialize());
				$("#TradeTaxId").load("/trades/ajax_tax?" + (new Date()).getTime() , $("#TradeAddForm").serialize());
			}
		});
		
		$("#TradeCurrencyId").change(function() {
			var checked = $("#TradeExecuted:checked").val() != undefined;
			if (checked) {
				$("#TradeTaxId").load("/trades/ajax_tax?" + (new Date()).getTime() , $("#TradeAddForm").serialize());
				$("#TradeOtherCostsId").load("/trades/ajax_othercosts?" + (new Date()).getTime() , $("#TradeAddForm").serialize());
			}
		});
		
		$("#TradeTradeTypeId").change(function() {
			var checked = $("#TradeExecuted:checked").val() != undefined;
			if (checked) {
				$("#TradeTaxId").load("/trades/ajax_tax?" + (new Date()).getTime() , $("#TradeAddForm").serialize());
			}
		});
		
	});
	
	function clearcosts() {
		$("#TradeCommission").val("");
		$("#TradeTax").val("");
		$("#TradeOtherCosts").val("");
		$("#TradeConsideration").val("");
	}
	
</script>
