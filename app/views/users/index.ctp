<!-- File: /app/views/users/index.ctp -->

<h1>Users</h1>

<?php echo $this->Html->link('Add New User', array('controller' => 'users', 'action' => 'add')); ?>

<table>
	<tr>
		<th>Id</th>
		<th>User Name</th>
		<th>Password</th>
		<th>Security Grouop</th>
	</tr>

	<?php foreach ($users as $user): ?>
	<tr>
		<td><?php echo $user['User']['id']; ?></td>
		<td><?php echo $user['User']['username']; ?></td>
		<td><?php echo $user['User']['password']; ?></td>
		<td><?php echo $user['Group']['name']; ?></td>
	</tr>
	<?php endforeach; ?>
</table>
