<!-- File: /app/views/reports/index.ctp -->

<table>
	<tr>
		<td>
			<h1>Run Reports</h1>
		</td>
	</tr>

	<tr><td><?php echo $this->Form->create('Report'); ?></td></tr>
	<tr><td><?php echo $this->Form->input('fund_id'); ?></td></tr>
	<tr><td><?php echo $this->Form->input('report_type', array('options'=>array('Position'=>'Position'))); ?></td></tr>
	<tr><td><?php echo $this->Form->input('run_date'); ?></td></tr>
	<tr><td><?php echo $this->Form->end('Run Report'); ?></td></tr>
	
</table>