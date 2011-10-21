<!-- File: /app/views/trades/index.ctp -->

<h1>Trades</h1>

<?php echo $this->Html->link('Add Trade', array('controller' => 'trades', 'action' => 'add')); ?>

<table>
	<tr>
		<th>Id</th>
		<th>Fund Id</th>
		<th>Security Id</th>
		<th>Trade Type Id</th>
		<th>Reason Id</th>
		<th>Broker Id</th>
		<th>Trader Id</th>
		<th>Quantity</th>
		<th>Broker Contact</th>
		<th>Trade Date</th>
		<th>Price</th>
		<th>Cancelled Flag</th>
		<th>Executed Flag</th>
	</tr>

	<!-- Here is where we loop through our $trades array, printing out trade info -->

	<?php foreach ($trades as $trade): ?>
	<tr>
		<td><?php echo $trade['Trade']['id']; ?></td>
		<td><?php echo $trade['Trade']['fund_id']; ?></td>
		<td><?php echo $trade['Trade']['sec_id']; ?></td>
		<td><?php echo $trade['Trade']['trade_type_id']; ?></td>
		<td><?php echo $trade['Trade']['reason_id']; ?></td>
		<td><?php echo $trade['Trade']['broker_id']; ?></td>
		<td><?php echo $trade['Trade']['trader_id']; ?></td>
		<td><?php echo $trade['Trade']['quantity']; ?></td>
		<td><?php echo $trade['Trade']['broker_contact']; ?></td>
		<td><?php echo $trade['Trade']['trade_date']; ?></td>
		<td><?php echo $trade['Trade']['price']; ?></td>
		<td><?php echo $trade['Trade']['cancelled']; ?></td>
		<td><?php echo $trade['Trade']['executed']; ?></td>
	</tr>
	<?php endforeach; ?>
</table>
