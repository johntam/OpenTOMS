<!-- File: /app/views/brokers/index.ctp -->

<table>
	<tr>
		<td>
			<h1>Brokers</h1>
			<?php echo $this->Html->link('Add New Broker', array('controller' => 'brokers', 'action' => 'add')); ?>
		</td>
	</tr>
	<tr>
		<th>Id</th>
		<th>Broker Code</th>
		<th>Broker Long Name</th>
		<th>Standard Commission Rate</th>
		<th>Edit</th>
	</tr>

	<!-- Here is where we loop through our $brokers array, printing out broker info -->

	<?php foreach ($brokers as $broker): ?>
	<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
		<td><?php echo $broker['Broker']['id']; ?></td>
		<td><?php echo $broker['Broker']['broker_name']; ?></td>
		<td><?php echo $broker['Broker']['broker_long_name']; ?></td>
		<td><?php echo $broker['Broker']['commission_rate']; ?></td>
		<td><?php echo $this->Html->link('Edit', array('action' => 'edit', $broker['Broker']['id']));?></td>
	</tr>
	<?php endforeach; ?>
</table>
