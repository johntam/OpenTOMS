<!-- File: /app/views/funds/add.ctp -->	

<table style="width: 30%;margin-left:35%;margin-right:35%;">
<tr><td><h1>Add Fund</h1></td></tr>

<?php echo $this->Form->create('Fund'); ?>
<tr><td><?php echo $this->Form->input('fund_name'); ?></td></tr>
<tr><td><?php echo $this->Form->input('fund_currency'); ?></td></tr>
<tr><td><?php echo $this->Form->input('management_fee'); ?></td></tr>
<tr><td><?php echo $this->Form->end('Save Fund'); ?></td></tr>
</table>