<!--
	OpenTOMS - Open Trade Order Management System
	Copyright (C) 2012  JOHN TAM, LPR CONSULTING LLP

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
-->	

<table style="width: 50%; margin-left: 25%; margin-right: 25%;">
	<tr>
		<td>
			<h1>Settlement Rules</h1>
			<?php 
				echo $this->Form->create('Settlement');
				echo $this->Form->input('sec_type_id',array('label'=>false, 'default'=>$sectype_id));
				echo $this->Form->end('Choose Sec Type'); 
			?>
		</td>
		<td></td>
		<td></td>
	</tr>
	
	<tr>
		<th>Country</th>
		<th>Settlement Days</th>
		<th></th>
	</tr>
	
	<tr>
		<?php echo $this->Form->create('Settlement', array('action' => 'add')); ?>
		<td style="width: 20%;">	
			<?php echo $this->Form->input('country_id', array('label'=>false)); ?>
		</td>
		<td style="width: 5%;">
			<?php echo $this->Form->input('settlement_days', array('label'=>false,'options' => array(0=>'T+0', 1=>'T+1',2=>'T+2',3=>'T+3',4=>'T+4',5=>'T+5'))); ?>
		</td>
		<td style="width: 8%;">
			<?php echo $this->Form->input('sec_type_id', array('type'=>'hidden', 'default'=>$sectype_id)); ?>
			<?php echo $this->Form->end('Add'); ?>
		</td>
		<td></td>
	</tr>

	<tr>
		<td colspan="3">
			<font color='red'>
				The default settlement period for the security type above is T+
				<?php echo $default_settlement;?>
				. This can by be overidden on a country by country basis below.
			</font>
		</td>
	</tr>
	
	<?php foreach ($settlements as $settlement): ?>
		<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
			<td>
				<?php
					echo $settlement['Country']['country_name'];
				?>
			</td>
			<td>
				<?php
					echo 'T+'.$settlement['Settlement']['settlement_days'];
				?>
			</td>
			<td>
				<?php
					echo $this->Html->link('Del', '/settlements/delete/'.$settlement['Settlement']['id'].'/'.$sectype_id);
				?>
			</td>
		</tr>
	<?php endforeach; ?>

</table>
