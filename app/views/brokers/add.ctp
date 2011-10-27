<!-- File: /app/views/brokers/add.ctp -->	

<table style="width: 30%;margin-left:35%;margin-right:35%;">
<tr><td><h1>Add New Broker</h1></td></tr>

<tr><td><?php echo $this->Form->create('Broker'); ?></td></tr>
<tr><td><?php echo $this->Form->input('broker_name'); ?></td></tr>
<tr><td><?php echo $this->Form->input('broker_long_name'); ?></td></tr>
<tr><td><?php echo $this->Form->input('commission_rate'); ?></td></tr>
<tr><td><?php echo $this->Form->end('Save Broker'); ?></td></tr>

</table>