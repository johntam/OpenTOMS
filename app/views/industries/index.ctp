<!-- File: /app/views/industries/index.ctp -->

<table style="width: 50%; margin-left: 25%; margin-right: 25%;">
	<tr>
		<td>
		<h1>Industries</h1>
		<?php echo $this->Html->link('Add Industry', array('controller' => 'industries', 'action' => 'add')); ?>
		</td>
	</tr>
	<tr>
		<th>Industry Code</th>
		<th>Industry Name</th>
		<th>Edit</th>
	</tr>

	<?php foreach ($industries as $industry): ?>
	<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
		<td><?php echo $industry['Industry']['industry_code']; ?></td>
		<td><?php echo $industry['Industry']['industry_name']; ?></td>
		<td><?php echo $this->Html->link('Edit', array('action' => 'edit', $industry['Industry']['id']));?></td>
	</tr>
	<?php endforeach; ?>
</table>
