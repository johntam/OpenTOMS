<!-- File: /app/views/reasons/add.ctp -->	
	
<h1>Add New Reason</h1>
<?php
echo $this->Form->create('Reason');
echo $this->Form->input('reason_desc');
echo $this->Form->end('Save Trade Reason');
?>