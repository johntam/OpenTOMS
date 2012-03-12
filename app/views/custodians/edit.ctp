<!-- File: /app/views/custodians/edit.ctp -->

<table>
	<tr>
		<td colspan="3">
			<h1>Edit Custodian</h1>		
		</td>
	</tr>
	
	<tr class="highlight">
		<td>Custodian Short Name</td>
		<td>Custodian Long Name</td>
	</tr>
	
		<tr>
			<?php echo $this->Form->create(null, array('action' => 'edit')); ?>
			<td><?php echo $this->Form->input('custodian_name', array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('custodian_long_name', array('label'=>false)); ?></td>
			<td><?php
				echo $this->Form->input('id', array('type' => 'hidden')); 
				echo $this->Form->end('Update Custodian');
			?></td>
		</tr>
</table>