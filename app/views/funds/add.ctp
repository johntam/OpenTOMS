<!-- File: /app/views/funds/add.ctp -->	
	
<h1>Add Fund</h1>
<?php
echo $this->Form->create('Fund');
echo $this->Form->input('fund_name');
echo $this->Form->input('fund_currency');
echo $this->Form->input('management_fee');
echo $this->Form->end('Save Fund');
?>