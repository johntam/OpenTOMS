<!-- File: /app/views/custodians/index.ctp -->

<table style="width: 50%;margin-left:25%;margin-right:25%;">
	<tr>
		<td>
			<h1>Custodians</h1>
			<?php echo $this->Html->link('Add New Custodian', array('controller' => 'custodians', 'action' => 'add')); ?>
		</td>
	</tr>
	<tr>
		<th style="width: 5%;">Id</th>
		<th>Custodian Short Name</th>
		<th>Custodian Long Name</th>
		<th>Edit</th>
	</tr>

	<?php foreach ($custodians as $custodian): ?>
	<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
		<td><?php echo $custodian['Custodian']['id']; ?></td>
		<td><?php echo $custodian['Custodian']['custodian_name']; ?></td>
		<td><?php echo $custodian['Custodian']['custodian_long_name']; ?></td>
		<td><?php echo $this->Html->link('Edit', array('action' => 'edit', $custodian['Custodian']['id']));?></td>
	</tr>
	<?php endforeach; ?>
</table>
