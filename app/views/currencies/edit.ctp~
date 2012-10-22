<!-- File: /app/views/currencies/edit.ctp -->

<table style="width: 50%; margin-left: 25%; margin-right: 25%;">
	<tr>
		<td colspan="3">
			<h1>Edit Currency</h1>		
		</td>
	</tr>
	
	<tr class="highlight">
		<td>Currency Code</td>
		<td>Currency Name</td>
		<td>Security</td>
	</tr>
	
		<tr>
			<?php echo $this->Form->create(null, array('action' => 'edit')); ?>
			<td><?php echo $this->Form->input('currency_iso_code', array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('currency_name', array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('sec_id', array('label'=>false)); ?></td>
			<td><?php
				echo $this->Form->input('id', array('type' => 'hidden')); 
				echo $this->Form->end('Update Currency');
			?></td>
		</tr>
</table>

