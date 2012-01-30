<!-- File: /app/views/accounts/edit.ctp -->

<table style="width: 50%; margin-left: 25%; margin-right: 25%;">
	<tr>
		<td colspan="3">
			<h1>Edit Accounting Book</h1>		
		</td>
	</tr>
	
	<tr class="highlight">
		<td>Book Name</td>
		<td>Book Type</td>
	</tr>
	
	<tr>
		<?php echo $this->Form->create(null, array('action' => 'edit')); ?>
		<td><?php echo $this->Form->input('account_name', array('label'=>false)); ?></td>
		<td><?php echo $this->Form->input('account_type', array('label'=>false,'options' => array('Assets'=>'Assets','Liabilities'=>'Liabilities','Owners Equity'=>'Owners Equity','Income'=>'Income','Expenses'=>'Expenses'))); ?></td>
		<td><?php
			echo $this->Form->input('id', array('type' => 'hidden')); 
			echo $this->Form->end('Update Account');
		?></td>
	</tr>
</table>
