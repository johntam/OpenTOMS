<!-- File: /app/views/brokers/add.ctp -->	
	
<h1>Add New Broker</h1>
<?php
echo $this->Form->create('Broker');
echo $this->Form->input('broker_name');
echo $this->Form->input('broker_long_name');
echo $this->Form->input('commission_rate');
echo $this->Form->end('Save Broker');
?>