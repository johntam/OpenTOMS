<!-- File: /app/views/reasons/index.ctp -->

<table>
	<tr>
		<td>
			<h1>Trade Reasons</h1>
			<?php echo $this->Html->link('Add New Reason', array('controller' => 'reasons', 'action' => 'add')); ?>
		</td>
	</tr>
	<tr>
		<th>Id</th>
		<th>Trade Reason</th>
	</tr>

	<!-- Here is where we loop through our $reasons array, printing out reason info -->

	<?php foreach ($reasons as $reason): ?>
	<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
		<td style="width: 10%;"><?php echo $reason['Reason']['id']; ?></td>
		<td><?php echo $reason['Reason']['reason_desc']; ?></td>
	</tr>
	<?php endforeach; ?>
</table>
