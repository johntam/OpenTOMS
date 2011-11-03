<!-- File: /app/views/prices/index.ctp -->

<table>
<tr>
<td><h1>Maintain Prices</h1></td>
</tr>
<tr class="altrow">

<td style="width: 30%;">
	<?php echo $this->Form->create('Price', array('action' => 'index')); ?>
	<?php echo $this->Form->input('secfilter',array('type'=>'text','label'=>'Enter a few characters of security name:','maxLength'=>10,'div'=>false));?>
	<?php echo $this->Form->end('Filter'); ?>
</td>
<td style="width: 10%;"></td>
<td style="width: 30%;">
	<?php echo $this->Form->create('Price', array('action' => 'index')); ?>
	<?php echo $this->Form->input('datefilter', array('label'=>'Enter a date to filter on','type'=>'date','default'=> strtotime('-1 day'))); ?>
	<?php echo $this->Form->end('Filter'); ?>
</td>

<td colspan="2"></td>
</tr>
</table>

<table>
	<tr>
		<td colspan="5">
			<?php
				if (isset($datefiltered)) {
					echo '<i>Filtering on date: </i><b>'.$datefiltered.'</b>';
				}
				elseif (isset($secnamefiltered)) {
					echo '<i>Filtering on characters: </i><b>'.str_replace('%','',$secnamefiltered).'</b>';
				}
				else {
					echo '<i>Showing prices between '.$todate.' weeks and '.$fromdate.' weeks ago</i> (';
					if ($todate == 0) {
						echo 'Earlier';
					}
					else {
						echo $this->Html->link('Earlier', array('action' => 'index', $todate-1, $fromdate-1));
					}
					echo '|'.$this->Html->link('Later', array('action' => 'index', $todate+1, $fromdate+1)).')';
				}
			?>	
		</td>
	</tr>
</table>

<table>	
	<tr>
		<th>Date</th>
		<th>Source</th>
		<th>Security</th>
		<th>Price</th>
		<th>Edit</th>
	</tr>

	<tr class="high2">
		<?php echo $this->Form->create(null, array('url' => array('controller' => 'prices', 'action' => 'add')));?>
		<td><?php echo $this->Form->input('price_date',array('label'=>false,'default'=> strtotime('-1 day'))); ?></td>
		<td><?php echo $this->Form->input('price_source',array('label'=>false, 'options' => array('DFLT'=>'DFLT','USER'=>'USER'))); ?></td>
		<td><?php echo $this->Form->input('sec_id',array('label'=>false)); ?></td>
		<td><?php echo $this->Form->input('price',array('type'=>'text','label'=>false)); ?></td>
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
