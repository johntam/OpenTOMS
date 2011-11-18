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
		<td><div>Commission<img src="/img/ajax-busy.gif" id="commission_busy"/></div></td>
		<td><div>Tax<img src="/img/ajax-busy.gif" id="tax_busy"/></div></td>
		<td><div>Other Costs<img src="/img/ajax-busy.gif" id="othercosts_busy"/></div></td>
		<td><div>Total Consideration<img src="/img/ajax-busy.gif" id="consideration_busy"/></div></td>
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
	<tr><td colspan="4" style="text-align: center;"><?php echo $this->Form->end('Save Trade'); ?></td></tr>
</table>



<script type="text/javascript">
	setInterval(function() { 
	$(document).ready(function() {
			calc_consideration();
		}); 
	}, 2500);
	
	$(document).ready(function() {
		handle_execute_checkbox();
		$("#commission_busy").hide();
		$("#tax_busy").hide();
		$("#othercosts_busy").hide();
		$("#consideration_busy").hide();
	
		$("#TradeSecId").change(function() {
			$("#TradeCurrencyId").load("/trades/ajax_ccydropdown?" + (new Date()).getTime() , $("#TradeSecId").serialize());
			$("#TradeQuantity").val("");
			$("#TradeExecutionPrice").val("");
			clearcosts();
		});
		
		$("#TradeQuantity").change(function() {
			var checked = $("#TradeExecuted:checked").val() != undefined;
			if (checked) {
				calc_commission();
				calc_tax();
				calc_othercosts();
				calc_quantity();
			}
		});
		
		$("#TradeExecutionPrice").change(function() {
			var checked = $("#TradeExecuted:checked").val() != undefined;
			if (checked) {
				calc_commission();
				calc_tax();
				calc_othercosts();
			}
		});
		
		$("#TradeExecuted").click(function() {
			handle_execute_checkbox();
		});
		
		$("#TradeBrokerId").change(function() {
			var checked = $("#TradeExecuted:checked").val() != undefined;
			if (checked) {
				calc_commission();
				calc_tax();
			}
		});
		
		$("#TradeCurrencyId").change(function() {
			var checked = $("#TradeExecuted:checked").val() != undefined;
			if (checked) {
				calc_tax();
				calc_othercosts();
			}
		});
		
		$("#TradeTradeTypeId").change(function() {
			var checked = $("#TradeExecuted:checked").val() != undefined;
			if (checked) {
				calc_tax();
				calc_quantity();
			}
		});
	});
	
	function handle_execute_checkbox() {
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
	}
	
	
	function clearcosts() {
		$("#TradeCommission").val("");
		$("#TradeTax").val("");
		$("#TradeOtherCosts").val("");
		$("#TradeConsideration").val("");
	}
	
	function calc_quantity() {
		$.post("/trades/ajax_quantity?" + (new Date()).getTime(),
				{ quantity : $("#TradeQuantity").val() , tradetype : $("#TradeTradeTypeId").val() },
				function(data) {
					$("#TradeQuantity").val(data);
				},
				"text"
		);
	}
	
	function calc_consideration() {
		$.post("/trades/ajax_consid?" + (new Date()).getTime(),
			$("#TradeAddForm").serialize(),
			function(data) {
				if (data.length > 0) {
					$("#TradeConsideration").val(data);
					$("#consideration_busy").hide();
				}
			},
			"text"
		);
	}
	
	function calc_commission() {
		$("#commission_busy").show();
		$("#TradeCommId").load("/trades/ajax_commission?" + (new Date()).getTime() , $("#TradeAddForm").serialize(), function() { $("#commission_busy").hide(); $("#consideration_busy").show();});
	}
	
	function calc_tax() {
		$("#tax_busy").show();
		$("#TradeTaxId").load("/trades/ajax_tax?" + (new Date()).getTime() , $("#TradeAddForm").serialize(), function() { $("#tax_busy").hide(); $("#consideration_busy").show();});
	}
	
	function calc_othercosts() {
		$("#othercosts_busy").show();
		$("#TradeOtherCostsId").load("/trades/ajax_othercosts?" + (new Date()).getTime() , $("#TradeAddForm").serialize(), function() { $("#othercosts_busy").hide(); $("#consideration_busy").show();});
	}
</script>
