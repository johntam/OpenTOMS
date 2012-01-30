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
		<td>Settlement Date<img src="/img/ajax-busy.gif" id="settdate_busy"/></td>
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
		<td>Notional Value</td>
	</tr>

		<tr class="altrow">
			<td><?php echo $this->Form->input('executed',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('cancelled',array('label'=>false)); ?></td>
			<td></td>
			<td><?php echo $this->Form->input('notional_value',array('label'=>false)); ?></td>
		</tr>
		
	<tr><td colspan="4" style="text-align: center;"><?php echo $this->Form->end('Save Trade'); ?></td></tr>
</table>

<script type="text/javascript">
	
	$(document).ready(function() {
		handle_execute_checkbox();		
		$("#commission_busy").hide();
		$("#tax_busy").hide();
		$("#othercosts_busy").hide();
		$("#consideration_busy").hide();
		$("#settdate_busy").hide();
	
		$("#TradeSecId").change(function() {
			$("#TradeCurrencyId").load("/trades/ajax_ccydropdown?" + (new Date()).getTime() , $("#TradeSecId").serialize());
			$("#TradeQuantity").val("");
			$("#TradeExecutionPrice").val("");
			clearcosts();
			calc_settdate();
		});
		
		
		$("#TradeQuantity").focusout(function() {
			recalculate_consideration();
		});
		
		$("#TradeExecutionPrice").focusout(function() {
			recalculate_consideration();
		});
		
		$("#TradeExecuted").click(function() {
			handle_execute_checkbox();
		});
		
		$("#TradeBrokerId").change(function() {
			recalculate_consideration();
		});
		
		$("#TradeCurrencyId").change(function() {
			recalculate_consideration();
		});
		
		$("#TradeTradeTypeId").change(function() {
			recalculate_consideration();
		});		
		
		$("#TradeTradeDateMonth").change(function() {
			if ($("#TradeSecId option:selected").text() != 'Select Security') {
				$('input[type="submit"]').attr('disabled','disabled'); //disable submit button
				$("#settdate_busy").show();
				check_price();
				calc_settdate();
				
				$.when( calc_settdate() )
					.then(function(){
						$('input[type="submit"]').removeAttr('disabled');
				});
			}
		});
		
		
		$("#TradeTradeDateDay").change(function() {
			if ($("#TradeSecId option:selected").text() != 'Select Security') {
				$('input[type="submit"]').attr('disabled','disabled'); //disable submit button
				$("#settdate_busy").show();
				check_price();
				calc_settdate();
				
				$.when( calc_settdate() )
					.then(function(){
						$('input[type="submit"]').removeAttr('disabled');
				});
			}	
		});
		
		
		$("#TradeTradeDateYear").change(function() {
			if ($("#TradeSecId option:selected").text() != 'Select Security') {
				$('input[type="submit"]').attr('disabled','disabled'); //disable submit button
				$("#settdate_busy").show();
				check_price();
				calc_settdate();
				
				$.when( calc_settdate() )
					.then(function(){
						$('input[type="submit"]').removeAttr('disabled');
				});
			}
		});
		
		
		$("#TradeExecutionPrice").focusout(function() {
			check_price();
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
		$("#TradeNotionalValue").val("");
	}
	
	
	function calc_settdate() {
		return $.Deferred(function( deferred_obj ){
			$.post("/trades/ajax_settdate?" + (new Date()).getTime(),
				$("#TradeAddForm").serialize(),
				function(data) {
					if (data.indexOf("-") > 0) {
						var myDate = data.split("-");
						$("#TradeSettlementDateMonth").val(myDate[1]);
						$("#TradeSettlementDateDay").val(myDate[2]);
						$("#TradeSettlementDateYear").val(myDate[0]);
						$("#settdate_busy").hide();
					}
					
					deferred_obj.resolve();
				},
				"text"
			);
		}).promise();
	}
	
	
	function check_price() {
		$.post("/trades/ajax_checkprice?" + (new Date()).getTime(),
			$("#TradeAddForm").serialize(),
			function(data) {
				if (data.length > 0) {
					alert(data);
				}
			},
			"text"
		);
	}
	
	function calc_consideration() {
		return $.Deferred(function( deferred_obj ){
			$.post("/trades/ajax_consid?" + (new Date()).getTime(),
				$("#TradeAddForm").serialize(),
				function(data) {
					if (data.length > 0) {
						var parts = data.split("|");
						$("#TradeConsideration").val(parts[0]);
						if (parts[1] != 0) {
							$("#TradeNotionalValue").val(parts[1]);
						}
						$("#consideration_busy").hide();
					}
					
					deferred_obj.resolve();
				},
				"text"
			);
		}).promise();
	}
	
	
	function recalculate_consideration() {
		var checked = $("#TradeExecuted:checked").val() != undefined;
		
		checked = checked && ($("#TradeSecId option:selected").text() != 'Select Security');
		checked = checked && ($("#TradeQuantity").val() != '');
		checked = checked && ($("#TradeExecutionPrice").val() != '');
	
		if (checked) {
			$('input[type="submit"]').attr('disabled','disabled'); //disable submit button
			calc_quantity();
			calc_commission();
			calc_tax();
			calc_othercosts();
			
			$.when( calc_quantity(), calc_commission(), calc_tax(), calc_othercosts() )
			   .then(function(){
			   
				  calc_consideration();
				  $.when( calc_consideration() )
					.then(function() {
						//activate submit button
						$('input[type="submit"]').removeAttr('disabled');
					})
					.fail(function() {
					  // AJAX request failed
					  alert("Connection to database failed.");
					});
			   })
			   .fail(function() {
				  // AJAX request failed
				  alert("Connection to database failed.");
			   });
		}
	}
	
	function calc_quantity() {
		return $.Deferred(function( deferred_obj ){
			$.post("/trades/ajax_quantity?" + (new Date()).getTime(),
				{ quantity : $("#TradeQuantity").val() , tradetype : $("#TradeTradeTypeId").val() },
				function(data) {
					$("#TradeQuantity").val(data);
					deferred_obj.resolve();
				},
				"text"
			);
		}).promise();
	}
	
	function calc_commission() {
		return $.Deferred(function( deferred_obj ){
			$("#commission_busy").show();
			$("#TradeCommId").load("/trades/ajax_commission?" + (new Date()).getTime() , $("#TradeAddForm").serialize(), function() { $("#commission_busy").hide(); $("#consideration_busy").show(); deferred_obj.resolve();});
		}).promise();
	}
	
	function calc_tax() {
		return $.Deferred(function( deferred_obj ){
			$("#tax_busy").show();
			$("#TradeTaxId").load("/trades/ajax_tax?" + (new Date()).getTime() , $("#TradeAddForm").serialize(), function() { $("#tax_busy").hide(); $("#consideration_busy").show(); deferred_obj.resolve(); });
		}).promise();
	}
	
	
	function calc_othercosts() {
		return $.Deferred(function( deferred_obj ){
			$("#othercosts_busy").show();
			$("#TradeOtherCostsId").load("/trades/ajax_othercosts?" + (new Date()).getTime() , $("#TradeAddForm").serialize(), function() { $("#othercosts_busy").hide(); $("#consideration_busy").show(); deferred_obj.resolve();});
		}).promise();
	}
</script>
