<!-- File: /app/views/accounts/index.ctp -->

<div style="width: 50%; margin-left: 25%; margin-right: 25%;">
	<h1>Chart of Accounting Books</h1>
</div>

<table style="width: 50%; margin-left: 25%; margin-right: 25%;">
	<tr>
		<td>
			<div class="high">
				<?php echo $this->Html->link('Add Account', array('controller' => 'accounts', 'action' => 'add')); ?>
			</div>
		</td>
	</tr>
	<tr>
		<th style="width: 20%">Edit</th>
		<th>Book Name</th>
		<th>Book Type</th>
	</tr>
	<?php foreach ($accounts as $account): ?>
		<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
			<td><?php echo $this->Html->link('Edit', array('action' => 'edit', $account['Account']['id']));?></td>
			<td><?php echo $account['Account']['account_name']; ?></td>
			<td><?php echo $account['Account']['account_type']; ?></td>
		</tr>
	<?php endforeach; ?>
</table>