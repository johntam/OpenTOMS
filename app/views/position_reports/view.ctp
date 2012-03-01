<!-- File: /app/views/position_reports/index.ctp -->

<table style="width: 60%;margin-left:20%;margin-right:20%;">
	<tr>
		<td colspan="2"><h1>Position Reports</h1></td>
	</tr>
	
	<tr class="altrow">
		<td>
			<?php echo $this->Form->create('PositionReport', array('action' => 'index')); ?>
			<?php echo $this->Form->input('fund_id', array('label'=>false, 'options'=>$funds)); ?>
			<?php echo $this->Form->input('pos_date', array('label'=>false,'type'=>'date','default'=> strtotime('-1 day'))); ?>
		</td>
		<td>
			<div class="high">
				Run Position Report
				<?php echo $this->Form->submit('Run', array('name'=>'Submit', 'value' => 'Run'));?>
			</div>
		</td>
		<?php echo $this->Form->end(); ?>
	</tr>
</table>	

<table style="width: 60%;margin-left:20%;margin-right:20%;">	
	<tr>
		<th>Book</th>
		<th>Security</th>
		<th>Quantity</th>
		<th>Price</th>
		<th>Currency</th>
		<th>FX Rate</th>
		<th>Market Val (local)</th>
		<th>Market Val (fund)</th>
	</tr>
	
	<?php if (isset($positions)) { ?>
	
	<?php foreach ($positions as $position): ?>
		<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
			<td>
				<?php echo $position['Account']['account_name']; ?>
			</td>
			<td>
				<?php echo $position['Sec']['sec_name']; ?>
			</td>
			<td>
				<?php echo number_format($position['PositionReport']['quantity'],0); ?>
			</td>
			<td>
				<?php echo number_format($position['PositionReport']['price'],2); ?>
			</td>
			<td>
				<?php echo $position['Currency']['currency_iso_code']; ?>
			</td>
			<td>
				<?php echo number_format($position['PositionReport']['fx_rate'],4); ?>
			</td>
			<td>
				<?php echo number_format($position['PositionReport']['mkt_val_local'],0); ?>
			</td>
			<td>
				<?php echo number_format($position['PositionReport']['mkt_val_fund'],0); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	
	<?php }; ?>
</table>