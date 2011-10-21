<!-- File: /app/views/brokers/index.ctp -->

<h1>Brokers</h1>

<?php echo $this->Html->link('Add New Broker', array('controller' => 'brokers', 'action' => 'add')); ?>

<table>
	<tr>
		<th>Id</th>
		<th>Broker Code</th>
		<th>Broker Long Name</th>
		<th>Standard Commission Rate</th>
	</tr>

	<!-- Here is where we loop through our $brokers array, printing out broker info -->

	<?php foreach ($brokers as $broker): ?>
	<tr>
		<td><?php echo $broker['Broker']['id']; ?></td>
		<td><?php echo $broker['Broker']['broker_name']; ?></td>
		<td><?php echo $broker['Broker']['broker_long_name']; ?></td>
		<td><?php echo $broker['Broker']['commission_rate']; ?></td>
	</tr>
	<?php endforeach; ?>
</table>
