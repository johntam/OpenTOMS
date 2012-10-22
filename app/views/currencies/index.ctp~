<!-- File: /app/views/currencies/index.ctp -->

<table style="width: 50%; margin-left: 25%; margin-right: 25%;">
	<tr>
		<td>
		<h1>Currencies</h1>
		<?php echo $this->Html->link('Add Currency', array('controller' => 'currencies', 'action' => 'add')); ?>
		</td>
	</tr>
	<tr>
		<th>Currency Code</th>
		<th>Currency Name</th>
		<th>Edit</th>
	</tr>

	<?php foreach ($currencies as $currency): ?>
	<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
		<td><?php echo $currency['Currency']['currency_iso_code']; ?></td>
		<td><?php echo $currency['Currency']['currency_name']; ?></td>
		<td><?php echo $this->Html->link('Edit', array('action' => 'edit', $currency['Currency']['id']));?></td>
	</tr>
	<?php endforeach; ?>
</table>
