<!-- File: /app/views/traders/index.ctp -->

<table>
	<tr>
		<td>
			<h1>Traders</h1>
			<?php echo $this->Html->link('Add New Trader', array('controller' => 'traders', 'action' => 'add')); ?>
		</td>
	</tr>
	<tr>
		<th>Id</th>
		<th>Trader Name</th>
		<th>Trader Login</th>
	</tr>

	<!-- Here is where we loop through our $traders array, printing out trader info -->

	<?php foreach ($traders as $trader): ?>
	<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
		<td><?php echo $trader['Trader']['id']; ?></td>
		<td><?php echo $trader['Trader']['trader_name']; ?></td>
		<td><?php echo $trader['Trader']['trader_login']; ?></td>
	</tr>
	<?php endforeach; ?>
</table>
