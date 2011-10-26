<!-- File: /app/views/trades/view.ctp -->

<h2>View Trade (Orig ID: <?php echo $data['0']['Trade']['oid']; ?>)</h2>

<?php foreach($data as $trade): ?>

<table>	
	
	<tr>
		<td>Fund</td>
		<td>Security Name</td>
		<td>Security Type</td>
		<td>Ticker</td>
	</tr>
	
		<tr>
			<td><?php echo $trade['Fund']['fund_name']; ?></td>
			<td><?php echo $trade['Sec']['sec_name']; ?></td>
			<td>...</td>
			<td><?php echo $trade['Sec']['ticker']; ?></td>
		</tr>
	
	<tr>
		<td>Trade Type</td>
		<td>Quantity</td>
		<td>Order Price</td>
		<td>Trade Currency</td>
	</tr>
	
		<tr>
			<td><?php echo $trade['TradeType']['trade_type']; ?></td>
			<td><?php echo $trade['Trade']['quantity']; ?></td>
			<td><?php echo $trade['Trade']['price']; ?></td>
			<td><?php echo $trade['Trade']['currency']; ?></td>
		</tr>
	
	<tr>
		<td>Decision Time</td>
		<td>Trade Date</td>
		<td>Settlement Date</td>
		<td>Trader</td>
	</tr>
	
		<tr>
			<td><?php echo $trade['Trade']['decision_time']; ?></td>
			<td><?php echo $trade['Trade']['trade_date']; ?></td>
			<td><?php echo $trade['Trade']['settlement_date']; ?></td>
			<td><?php echo $trade['Trader']['trader_name']; ?></td>
		</tr>
		
	<tr>
		<td>Broker</td>
		<td>Broker Contact</td>
		<td>Order Time</td>
		<td>Commission</td>
	</tr>
	
		<tr>
			<td><?php echo $trade['Broker']['broker_name']; ?></td>
			<td><?php echo $trade['Trade']['broker_contact']; ?></td>
			<td><?php echo $trade['Trade']['order_time']; ?></td>
			<td><?php echo $trade['Trade']['commission']; ?></td>
		</tr>
	

	<tr>
		<td>Trade Reason</td>
		<td>Executed</td>
		<td>Cancelled</td>
		<td></td>
	</tr>

		<tr>
			<td><?php echo $trade['Reason']['reason_desc']; ?></td>
			<td><?php echo $trade['Trade']['executed']; ?></td>
			<td><?php echo $trade['Trade']['cancelled']; ?></td>
			<td></td>
		</tr>
	
</table>

<?php echo $paginator->prev('<<< '.__('prev', true), null, null, array('class' => 'disabled')); ?>

<!-- Shows the next and previous links -->
<?php echo $this->Paginator->counter(array('format' => '(Trade %page% of %pages%)'));  ?>

<?php echo $paginator->next('>>> '.__('next', true), null, null, array('class' => 'disabled')); ?>

<?php endforeach; ?>
