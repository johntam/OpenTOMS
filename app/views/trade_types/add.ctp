<!-- File: /app/views/trade_types/add.ctp -->	
	
<h1>Add Trade Type</h1>
<?php
echo $this->Form->create('TradeType');
echo $this->Form->input('trade_type');
echo $this->Form->input('category');
echo $this->Form->input('credit_debit');
echo $this->Form->end('Save Trade Type');
?>