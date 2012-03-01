<!-- File: /app/views/position_reports/index.ctp -->

<table style="width: 60%;margin-left:20%;margin-right:20%;">
	<tr>
		<td colspan="2"><h1>Position Reports</h1></td>
	</tr>
	
	<tr class="altrow">
		<td>
			<?php echo $this->Form->create('PositionReport', array('action' => 'run')); ?>
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
		<th>Status</th>
		<th>Fund</th>
		<th>Date</th>
	</tr>
	
	<?php if (isset($reports)) { ?>
	
	<?php foreach ($reports as $report): ?>
		<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
			<td>
				<?php 
					if ($report['PositionReport']['final'] == 1) {
						echo 'Final';
					}
					else {
						echo 'Estimate';
					}
				?>
			</td>
			<td>
				<?php echo $report['Fund']['fund_name']; ?>
			</td>
			<td>
				<?php echo $report['PositionReport']['pos_date']; ?>
			</td>
		</tr>
	<?php endforeach; ?>
	
	<?php }; ?>
</table>