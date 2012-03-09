<!-- File: /app/views/position_reports/show.ctp -->

<table style="width: 60%;margin-left:20%;margin-right:20%;">
	<tr>
		<td><h1>Position Report</h1></td>
	</tr>
	
	<tr class="altrow">
		<td>Fund Name</td>
		<td>Report Date</td>
	</tr>
</table>	

<table style="width: 60%;margin-left:20%;margin-right:20%;">	
	<tr>
		<th>Security</th>
		<th>Quantity</th>
		<th>Price</th>
		<th>Market Val (Local)</th>
		<th>Currency</th>
		<th>FX Rate</th>
		<th>Market Val (USD)</th>
	</tr>
	
	<?php if (isset($positions)) { ?>
	
	<?php foreach ($positions as $position): ?>
		<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
			<td>
				<?php echo $position['Sec']['sec_name']; ?>
			</td>
			<td>
				<?php echo $position['PositionReport']['quantity']; ?>
			</td>
			<td>
				<?php echo $position['PositionReport']['price']; ?>
			</td>
			<td>
				<?php echo $position['PositionReport']['mkt_val_local']; ?>
			</td>
			<td>
				<?php echo $position['Currency']['currency_iso_code']; ?>
			</td>
			<td>
				<?php echo $position['PositionReport']['fx_rate']; ?>
			</td>
			<td>
				<?php echo $position['PositionReport']['mkt_val_usd']; ?>
			</td>
		</tr>
	<?php endforeach; ?>
	
	<?php }; ?>
</table>