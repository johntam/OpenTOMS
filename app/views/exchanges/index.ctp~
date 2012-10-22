<!-- File: /app/views/exchanges/index.ctp -->

<table style="width: 50%; margin-left: 25%; margin-right: 25%;">
	<tr>
		<td>
		<h1>Exchanges</h1>
		<?php echo $this->Html->link('Add Exchange', array('controller' => 'exchanges', 'action' => 'add')); ?>
		</td>
	</tr>
	<tr>
		<th>Exchange Code</th>
		<th>Exchange Name</th>
		<th>Edit</th>
	</tr>

	<?php foreach ($exchanges as $exchange): ?>
	<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
		<td><?php echo $exchange['Exchange']['exchange_code']; ?></td>
		<td><?php echo $exchange['Exchange']['exchange_name']; ?></td>
		<td><?php echo $this->Html->link('Edit', array('action' => 'edit', $exchange['Exchange']['id']));?></td>
	</tr>
	<?php endforeach; ?>
</table>
