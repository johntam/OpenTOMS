<!-- File: /app/views/trades/add.ctp -->	
	
<h1>Add Trade</h1>
<?php
echo $this->Form->create('Trade');
//echo $this->Form->input('Trade.Fund',array('type'=>'select','options'=>$funds));
//echo $this->Form->input('fund_id', array('type'=>'select', 'options'=>$funds));

print_r($tradetypes);

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

$options=array('N'=>'No','Y'=>'Yes');
$attributes=array('value'=>'Y','separator'=>'</br>');

echo $this->Form->checkbox('cancelled', array('label'=>true)); 
echo $this->Form->checkbox('executed'); 


//echo $this->Form->radio('cancelled',$options,$attributes);
//echo $this->Form->radio('executed',$options,$attributes);


//echo $this->Form->input('cancelled');
//echo $this->Form->input('executed');
echo $this->Form->end('Save Trade');
?>