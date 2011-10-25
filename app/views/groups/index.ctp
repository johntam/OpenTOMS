<!-- File: /app/views/groups/index.ctp -->

<h1>Security Groups</h1>

<?php echo $this->Html->link('Add New Group', array('controller' => 'groups', 'action' => 'add')); ?>

<table>
	<tr>
		<th>Id</th>
		<th>Group Name</th>
	</tr>

	<?php foreach ($groups as $group): ?>
	<tr>
		<td><?php echo $group['Group']['id']; ?></td>
		<td><?php echo $group['Group']['name']; ?></td>
	</tr>
	<?php endforeach; ?>
</table>
