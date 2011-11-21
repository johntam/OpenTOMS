<!-- File: /app/views/portfolios/index.ctp -->

<table style="width: 30%;margin-left:35%;margin-right:35%;">
	<tr><td colspan="15"><h4>Security Positions</h4></td></tr>
	<tr>
		<th>Security Name</th>
		<th>Position</th>		
	</tr>

	<?php foreach ($portfolio_data as $data): ?>
	<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
		<td><?php echo $data['Sec']['sec_name']; ?></td>
		<td><?php echo $data['0']['quantity']; ?></td>
	</tr>
	<?php endforeach; ?>
</table>