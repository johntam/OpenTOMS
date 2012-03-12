<!-- File: /app/views/custodians/add.ctp -->	

<table style="width: 30%;margin-left:35%;margin-right:35%;">
<tr><td><h1>Add New Custodian</h1></td></tr>

<tr><td><?php echo $this->Form->create('Custodian'); ?></td></tr>
<tr><td><?php echo $this->Form->input('custodian_name'); ?></td></tr>
<tr><td><?php echo $this->Form->input('custodian_long_name'); ?></td></tr>
<tr><td><?php echo $this->Form->end('Save Custodian'); ?></td></tr>

</table>