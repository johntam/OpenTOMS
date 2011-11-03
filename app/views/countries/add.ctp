<!-- File: /app/views/countries/add.ctp -->	

<table style="width: 30%;margin-left:35%;margin-right:35%;">
<tr><td><h1>Add Country</h1></td></tr>

<?php echo $this->Form->create(); ?>
<tr><td><?php echo $this->Form->input('country_code'); ?></td></tr>
<tr><td><?php echo $this->Form->input('country_name'); ?></td></tr>
<tr><td><?php echo $this->Form->end('Add Country'); ?></td></tr>
</table>