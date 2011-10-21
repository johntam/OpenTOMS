<!-- File: /app/views/reasons/index.ctp -->

<h1>Trade Reasons</h1>

<?php echo $this->Html->link('Add New Reason', array('controller' => 'reasons', 'action' => 'add')); ?>

<table>
	<tr>
		<th>Id</th>
		<th>Trade Reason</th>
	</tr>

	<!-- Here is where we loop through our $reasons array, printing out reason info -->

	<?php foreach ($reasons as $reason): ?>
	<tr>
		<td><?php echo $reason['Reason']['id']; ?></td>
		<td><?php echo $reason['Reason']['reason_desc']; ?></td>
	</tr>
	<?php endforeach; ?>
</table>
