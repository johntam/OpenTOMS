<!-- File: /app/views/exchanges/add.ctp -->	

<table style="width: 30%;margin-left:35%;margin-right:35%;">
<tr><td><h1>Add Exchange</h1></td></tr>

<?php echo $this->Form->create(); ?>
<tr><td><?php echo $this->Form->input('exchange_code'); ?></td></tr>
<tr><td><?php echo $this->Form->input('exchange_name'); ?></td></tr>
<tr><td><?php echo $this->Form->end('Add Exchange'); ?></td></tr>
</table>