<!-- File: /app/views/reasons/add.ctp -->	

<table style="width: 60%;margin-left:20%;margin-right:20%;">
<tr><td><h1>Add New Reason</h1></td></tr>

<tr><td><?php echo $this->Form->create('Reason'); ?></td></tr>
<tr><td><?php echo $this->Form->input('reason_desc'); ?></td></tr>
<tr><td><?php echo $this->Form->end('Save Trade Reason'); ?></td></tr>

</table>