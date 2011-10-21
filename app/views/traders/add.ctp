<!-- File: /app/views/traders/add.ctp -->	
	
<h1>Add New Trader</h1>
<?php
echo $this->Form->create('Trader');
echo $this->Form->input('trader_name');
echo $this->Form->input('trader_login');
echo $this->Form->end('Save Trader');
?>