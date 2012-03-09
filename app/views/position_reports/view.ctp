<!-- File: /app/views/position_reports/view.ctp -->

<table style="width: 60%;margin-left:20%;margin-right:20%;">
	<tr>
		<td colspan="2"><h1>Position Reports</h1></td>
	</tr>
	
	<tr class="altrow">
		<td width="40%">
			<?php echo $this->Form->create('PositionReport', array('action' => 'index')); ?>
			<?php echo $this->Form->input('fund_id', array('label'=>false, 'options'=>$funds)); ?>
			<?php echo $this->Form->submit('View', array('name'=>'Submit', 'value' => 'View', 'style'=>'float:left;')); ?>
		</td>
		<td width="20%">
			<div class="high">
				Run Position Report
				<?php if (!empty($run_dates)) {
					echo $this->Form->input('run_date', array('label'=>false, 'options'=>$run_dates, 'style'=>'float:left;'));
					echo $this->Form->submit('Run', array('name'=>'Submit', 'value' => 'Run', 'style'=>'float:left;'));
				}
				else {
					echo '<div style="color: red;">No balance calculations have been found</div>';
				}
				?>
			</div>
		</td>
		<td></td>
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