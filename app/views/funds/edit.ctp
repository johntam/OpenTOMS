<!-- File: /app/views/funds/edit.ctp -->

<table>
	<tr>
		<td colspan="3">
			<h1>Edit Fund</h1>		
		</td>
	</tr>
	
	<tr class="highlight">
		<td>Fund Name</td>
		<td>Fund Currency</td>
		<td>Management Fee</td>
	</tr>
	
		<tr>
			<?php echo $this->Form->create(null, array('action' => 'edit')); ?>
			<td><?php echo $this->Form->input('fund_name', array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('currency_id', array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('management_fee', array('label'=>false)); ?></td>
			<td><?php
				echo $this->Form->input('id', array('type' => 'hidden')); 
				echo $this->Form->end('Update Fund');
			?></td>
		</tr>
</table>

