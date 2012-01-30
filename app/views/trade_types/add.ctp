<!-- File: /app/views/trade_types/add.ctp -->	

<table style="width: 30%;margin-left:35%;margin-right:35%;">

<tr><td><h1>Add Trade Type</h1></td></tr>

<tr><td><?php echo $this->Form->create('TradeType'); ?></td></tr>
<tr><td><?php echo $this->Form->input('trade_type'); ?></td></tr>
<tr><td><?php echo $this->Form->input('debit_account_id', array('options'=>$accountlist)); ?></td></tr>
<tr><td><?php echo $this->Form->input('credit_account_id', array('options'=>$accountlist)); ?></td></tr>
<tr><td><?php echo $this->Form->end('Save Trade Type'); ?></td></tr>

</table>