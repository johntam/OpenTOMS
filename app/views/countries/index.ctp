<!-- File: /app/views/countries/index.ctp -->

<table style="width: 50%; margin-left: 25%; margin-right: 25%;">
	<tr>
		<td>
		<h1>Countries</h1>
		<?php echo $this->Html->link('Add Country', array('controller' => 'countries', 'action' => 'add')); ?>
		</td>
	</tr>
	<tr>
		<th>County Code</th>
		<th>Country Name</th>
		<th>Edit</th>
	</tr>

	<?php foreach ($countries as $country): ?>
	<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
		<td><?php echo $country['Country']['country_code']; ?></td>
		<td><?php echo $country['Country']['country_name']; ?></td>
		<td><?php echo $this->Html->link('Edit', array('action' => 'edit', $country['Country']['id']));?></td>
	</tr>
	<?php endforeach; ?>
</table>
