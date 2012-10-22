<!-- File: /app/views/groups/index.ctp -->

<table style="width: 50%;margin-left:25%;margin-right:25%;">
	<tr>
		<td>
			<h1>Security Groups</h1>
			<?php echo $this->Html->link('Add New Group', array('controller' => 'groups', 'action' => 'add')); ?>
		</td>
	</tr>
	<tr>
		<th style="width: 15%;">Id</th>
		<th>Group Name</th>
	</tr>

	<?php foreach ($groups as $group): ?>
	<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
		<td><?php echo $group['Group']['id']; ?></td>
		<td><?php echo $group['Group']['name']; ?></td>
	</tr>
	<?php endforeach; ?>
</table>
