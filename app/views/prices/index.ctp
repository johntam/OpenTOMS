<!-- File: /app/views/prices/index.ctp -->

<table>
<tr>
<td><h1>Maintain Prices</h1></td>
</tr>
<tr class="altrow">
<?php echo $this->Form->create(null, array('url' => array('controller' => 'prices', 'action' => 'index')));?>
<td><?php echo $this->Form->input('secfilter',array('type'=>'text','label'=>'Enter a few characters of security name:','maxLength'=>10,'div'=>false));?>
<?php echo $this->Form->end('Filter');?></td>
<td colspan="4"></td>
</tr>
</table>

<table>
	<tr><td colspan="15"><h4>Showing prices between <?php echo $this->params['pass'][0];?> weeks and <?php echo $this->params['pass'][1];?> weeks ago</h4></td></tr>
	<tr>
		<th>Date</th>
		<th>Source</th>
		<th>Security</th>
		<th>Price</th>
		<th>Edit</th>
	</tr>

	<tr>
		<?php echo $this->Form->create(null, array('url' => array('controller' => 'prices', 'action' => 'add')));?>
		<td><?php echo $this->Form->input('price_date'); ?></td>
		<td><?php echo $this->Form->input('price_source'); ?></td>
		<td><?php echo $this->Form->input('sec_id'); ?></td>
		<td><?php echo $this->Form->input('price',array('type'=>'text')); ?></td>
		<td><?php echo $this->Form->end('Add'); ?></td>
	</tr>

	<?php foreach ($prices as $price): ?>
	<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
		<td><?php echo $price['Price']['price_date']; ?></td>
		<td><?php echo $price['Price']['price_source']; ?></td>
		<td><?php echo $price['Sec']['sec_name']; ?></td>
		<td><?php echo $price['Price']['price']; ?></td>
		<td><?php echo $this->Html->link('Edit', array('action' => 'edit', $price['Price']['id']));?></td>
	</tr>
	<?php endforeach; ?>
</table>
