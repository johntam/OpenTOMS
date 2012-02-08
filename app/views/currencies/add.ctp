<!-- File: /app/views/currencies/add.ctp -->	

<table style="width: 30%;margin-left:35%;margin-right:35%;">
<tr><td><h1>Add Currency</h1></td></tr>

<?php echo $this->Form->create(); ?>
<tr><td><?php echo $this->Form->input('currency_iso_code'); ?></td></tr>
<tr><td><?php echo $this->Form->input('currency_name'); ?></td></tr>
<tr><td><?php echo $this->Form->input('sec_id'); ?></td></tr>
<tr><td><?php echo $this->Form->end('Add Currency'); ?></td></tr>
</table>