<!-- File: /app/views/groups/add.ctp -->	
	
<h1>Add New Group</h1>
<?php
echo $this->Form->create('Group');
echo $this->Form->input('name');
echo $this->Form->end('Save Group');
?>