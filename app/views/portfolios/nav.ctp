<!-- File: /app/views/portfolios/position.ctp -->

<table style="width: 30%;margin-left:35%;margin-right:35%;">
	<tr><td colspan="15"><h4>NAV Report</h4></td></tr>
	<tr>
		<th>Security Name</th>
		<th>Quantity</th>
		<th>CCY</th>
		<th>Price</th>
		<th>Market Val (Local)</th>
		<th>Market Val (Fund)</th>
	</tr>

	<?php foreach ($portfolio_data as $data): ?>
	<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
		<td><?php echo $data['sec_name']; ?></td>
		<td><?php echo $data['position']; ?></td>
		<td><?php echo $data['currency']; ?></td>
		<td><?php echo $data['price']; ?></td>
		<td><?php echo $data['mkt_val_local']; ?></td>
		<td><?php echo $data['mkt_val_fund']; ?></td>
	</tr>
	<?php endforeach; ?>
</table>