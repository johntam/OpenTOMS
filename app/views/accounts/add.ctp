<!-- File: /app/views/accounts/add.ctp -->	

<table style="width: 30%;margin-left:35%;margin-right:35%;">
<tr><td><h1>Add Accounting Book</h1></td></tr>

<?php echo $this->Form->create('Account'); ?>
<tr><td><?php echo $this->Form->input('account_name'); ?></td></tr>
<tr><td><?php echo $this->Form->input('account_type', array('options' => array('Assets'=>'Assets','Liabilities'=>'Liabilities','Owners Equity'=>'Owners Equity','Income'=>'Income','Expenses'=>'Expenses'))); ?></td></tr>
<tr><td><?php echo $this->Form->end('Save'); ?></td></tr>
</table>