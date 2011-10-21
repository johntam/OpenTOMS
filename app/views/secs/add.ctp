<!-- File: /app/views/secs/add.ctp -->	
	
<h1>Add Security</h1>
<?php
echo $this->Form->create('Sec');
echo $this->Form->input('sectype_id');
echo $this->Form->input('sec_name');
echo $this->Form->input('ticker');
echo $this->Form->input('country');
echo $this->Form->input('industry');
echo $this->Form->input('valpoint');
echo $this->Form->input('currency');
echo $this->Form->end('Save Security');
?>