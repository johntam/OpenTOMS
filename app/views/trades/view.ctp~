<!-- File: /app/views/trades/view.ctp -->
<script type="text/javascript">
	$(document).ready(function() {
		var tt = $("#tradetype").html();
			if ((tt.substr(0,6) == "Coupon") || (tt.substr(0,8) == "Dividend")) {
				$("#TradeExecutionPrice").hide();
				$("#TradePrice").hide();
				$("#row4").hide();
				$("#row5").hide();
				$("#row6").hide();
				$("#head4").hide();
				$("#head5").hide();
				$("#head6").hide();
				$("#TradeExecutionPrice").val('');
			}
			else {
				$("#TradeExecutionPrice").show();
				$("#TradePrice").show();
				$("#row4").show();
				$("#row5").show();
				$("#row6").show();
				$("#head4").show();
				$("#head5").show();
				$("#head6").show();
			}
	});
</script>

<table <?php if (($data['0']['Trade']['cancelled'] == 1) || ($data['0']['Trade']['act'] == 0)) {echo 'class="cancelled"';} ?>>	
	<tr>
		<td>
			<h2>View Trade (Orig ID: <?php echo $data['0']['Trade']['oid']; ?>)</h2>
			<?php foreach($data as $trade): ?>
		</td>
	</tr>
	
	<tr class="highlight">
		<td>Fund</td>
		<td>Security Name</td>
		<td>Notes</td>
		<td>Trade Currency</td>
	</tr>
	
		<tr class="altrow">
			<td><?php echo $trade['Fund']['fund_name']; ?></td>
			<td><?php echo $trade['Sec']['sec_name']; ?></td>
			<td><?php echo $trade['Trade']['notes']; ?></td>
			<td><?php echo $trade['Currency']['currency_iso_code']; ?></td>
		</tr>
	
	<tr class="highlight">
		<td>Trade Type</td>
		<td>Quantity</td>
		<td>Order Price</td>
		<td>Execution Price</td>
	</tr>
	
		<tr class="altrow">
			<td id="tradetype"><?php echo $trade['TradeType']['trade_type']; ?></td>
			<td><?php echo number_format($trade['Trade']['quantity']); ?></td>
			<td><?php echo number_format($trade['Trade']['price'],4); ?></td>
			<td><?php echo number_format($trade['Trade']['execution_price'],4); ?></td>
		</tr>
	
	<tr class="highlight">
		<td>Custodian</td>
		<td>Trade Date</td>
		<td>Settlement Date</td>
		<td>Trader</td>
	</tr>
	
		<tr class="altrow">
			<td><?php echo $trade['Custodian']['custodian_name']; ?></td>
			<td><?php echo $trade['Trade']['trade_date']; ?></td>
			<td><?php echo $trade['Trade']['settlement_date']; ?></td>
			<td><?php echo $trade['Trader']['trader_name']; ?></td>
		</tr>
		
	<tr class="highlight" id="head4">
		<td>Broker</td>
		<td>Broker Contact</td>
		<td></td>
		<td>Trade Reason</td>
	</tr>
	
		<tr class="altrow" id="row4">
			<td><?php echo $trade['Broker']['broker_name']; ?></td>
			<td><?php echo $trade['Trade']['broker_contact']; ?></td>
			<td></td>
			<td><?php echo $trade['Reason']['reason_desc']; ?></td>
		</tr>

	<tr class="highlight" id="head5">
		<td>Commission</td>
		<td>Tax</td>
		<td>Other Costs</td>
		<td>Total Consideration</td>
	</tr>

		<tr class="altrow" id="row5">
			<td><?php echo number_format($trade['Trade']['commission'],2); ?></td>
			<td><?php echo number_format($trade['Trade']['tax'],2); ?></td>
			<td><?php echo number_format($trade['Trade']['other_costs'],2); ?></td>
			<td><?php echo number_format($trade['Trade']['consideration'],2); ?></td>
		</tr>

	<tr class="highlight" id="head6">
		<td>Decision Time</td>
		<td>Order Time</td>
		<td>Accrued Interest</td>
		<td>Notional Value</td>
	</tr>

		<tr class="altrow" id="row6">
			<td><?php echo $trade['Trade']['decision_time']; ?></td>
			<td><?php echo $trade['Trade']['order_time']; ?></td>
			<td><?php echo number_format($trade['Trade']['accrued'],2); ?></td>
			<td><?php echo number_format($trade['Trade']['notional_value'],2); ?></td>
		</tr>
		
	<tr class="highlight" id="head7">
		<td>Executed</td>
		<td>Cancelled</td>
		<td></td>
		<td></td>
	</tr>

		<tr class="altrow" id="row7">
			<td><?php if ($trade['Trade']['executed'] == 0) {echo 'No';} else {echo 'Yes';} ?></td>
			<td><?php if ($trade['Trade']['cancelled'] == 0) {echo 'No';} else {echo 'Yes';} ?></td>
			<td></td>
			<td></td>
		</tr>
	
</table>
</br>

<div style="text-align: center; background: #73E364; color: #000000; height: 1.5em;">
<?php echo $paginator->prev('<<< '.__('prev', true), null, null, array('class' => 'disabled')); ?>

<!-- Shows the next and previous links -->
<?php echo $this->Paginator->counter(array('format' => '(Trade %page% of %pages%)'));  ?>

<?php echo $paginator->next('>>> '.__('next', true), null, null, array('class' => 'disabled')); ?>

<?php endforeach; ?>
</div>