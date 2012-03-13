<!-- File: /app/views/position_reports/show.ctp -->

<table style="width: 60%;margin-left:20%;margin-right:20%;">
	<tr>
		<td><h1>Position Report</h1></td>
	</tr>
	
	<tr class="high2">
		<td><b><?php echo $positions[0]['Fund']['fund_name']; ?></b></td>
		<td><b>Report Date: <?php echo $positions[0]['PositionReport']['pos_date']; ?></b></td>
		<td>
			<?php echo $this->Form->create('PositionReport', array('action' => 'index/'.$positions[0]['PositionReport']['fund_id'])); ?>
			<?php echo $this->Form->submit('Back To List', array('name'=>'Submit', 'value' => 'Back'));?>
			<?php echo $this->Form->end(); ?>
		</td>
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
	
	<?php if (isset($positions)) { $totmvusd = 0; ?>
	
	<?php foreach ($positions as $position): ?>
		<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
			<td>
				<?php echo $position['Sec']['sec_name']; ?>
			</td>
			<td style="text-align: right;">
				<?php echo number_format($position['PositionReport']['quantity'],2); ?>
			</td>
			<td style="text-align: right;">
				<?php if ($position['Sec']['sec_type_id'] > 2) {
						echo number_format($position['PositionReport']['price'],5);
				}?>
			</td>
			<td style="text-align: right;">
				<?php echo number_format($position['PositionReport']['mkt_val_local'],2); ?>
			</td>
			<td style="text-align: right;">
				<?php echo $position['Currency']['currency_iso_code']; ?>
			</td>
			<td style="text-align: right;">
				<?php echo number_format($position['PositionReport']['fx_rate'],4); ?>
			</td>
			<td style="text-align: right;">
				<?php echo number_format($position['PositionReport']['mkt_val_usd'],2); 
					$totmvusd += $position['PositionReport']['mkt_val_usd']; ?>
			</td>
		</tr>
	<?php endforeach; ?>
		<tr class="total">
			<td colspan="6"></td>
			<td style="text-align: right;"><?php echo number_format($totmvusd,2); ?></td>
		</tr>
	<?php }; ?>
</table>