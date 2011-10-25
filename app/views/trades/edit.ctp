<!-- File: /app/views/trades/edit.ctp -->
	
<h1>Edit Trade</h1>
<?php
	echo $this->Form->create('Trade', array('action' => 'edit'));
	echo $this->Form->input('fund_id');
	echo $this->Form->input('sec_id');
	echo $this->Form->input('trade_type_id');
	echo $this->Form->input('reason_id');
	echo $this->Form->input('broker_id');
	echo $this->Form->input('trader_id');
	echo $this->Form->input('quantity');
	echo $this->Form->input('broker_contact');
	echo $this->Form->input('trade_date');
	echo $this->Form->input('price');
	echo $this->Form->input('cancelled');
	echo $this->Form->input('executed');
	echo $this->Form->input('id', array('type' => 'hidden')); 
	echo $this->Form->end('Update Trade');
?>
