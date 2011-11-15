<!-- File: /app/views/portfolios/index.ctp -->

<table>
	<tr><td colspan="15"><h4>Showing latest week's trades below</h4></td></tr>
	<tr>
		<th>Id</th>
		<th>Quantity</th>
		<th>Broker Contact</th>
		<th>Trade Date</th>
		<th>Price</th>
		<th>Cancelled Flag</th>
		<th>Executed Flag</th>
	</tr>

	<?php foreach ($trades as $trade): ?>
	<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
		<td><?php echo $trade['Trade']['id']; ?></td>
		<td><?php echo $trade['Trade']['quantity']; ?></td>
		<td><?php echo $trade['Trade']['broker_contact']; ?></td>
		<td style="width: 8%;"><?php echo $trade['Trade']['trade_date']; ?></td>
		<td><?php echo $trade['Trade']['price']; ?></td>
		<td><?php echo $trade['Trade']['cancelled']; ?></td>
		<td><?php echo $trade['Trade']['executed']; ?></td>
	</tr>
	<?php endforeach; ?>
</table>
