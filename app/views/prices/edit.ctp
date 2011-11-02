<!-- File: /app/views/prices/edit.ctp -->

<table>
	<tr>
		<td colspan="5">
			<h1>Edit Price</h1>
			<?php echo $this->Form->create(null, array('action' => 'edit')); ?>
		</td>
	</tr>
	
	<tr class="highlight">
		<td>Date</td>
		<td>Source</td>
		<td>Security Name</td>
		<td>Price</td>
	</tr>
	
		<tr>
			<td><?php echo $this->data['Price']['price_date']; ?></td>
			<td><?php echo $this->data['Price']['price_source']; ?></td>
			<td><?php echo $this->data['Sec']['sec_name']; ?></td>
			<td><?php echo $this->Form->input('price', array('label'=>false)); ?></td>
			<td><?php
				echo $this->Form->input('id', array('type' => 'hidden')); 
				echo $this->Form->end('Update Price');
			?></td>
		</tr>
</table>

