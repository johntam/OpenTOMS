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
			<h1>Holiday Dates</h1>
			<?php 
				echo $this->Form->create('Holiday');
				echo $this->Form->input('country_id',array('label'=>false, 'default'=>$countryid));
				echo $this->Form->end('Choose Country'); 
			?>
		</td>
		<td>
		</td>
	</tr>
	<tr>
		<th>Country</th>
		<th>Day</th>
		<th>Month</th>
		<th>Description</th>
		<th></th>
	</tr>
	
	<tr>
		<?php echo $this->Form->create('Holiday', array('action' => 'add')); ?>
		<td style="width: 20%;">	
			<?php echo $this->Form->input('country_id', array('label'=>false, 'default'=>$countryid)); ?>
		</td>
		<td style="width: 5%;">
			<?php echo $this->Form->input('holiday_day', array('label'=>false)); ?>
		</td>
		<td style="width: 5%;">
			<?php echo $this->Form->input('holiday_month', array('label'=>false, 'options' => array(1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10,11=>11,12=>12))); ?>
		</td>
		<td>
			<?php echo $this->Form->input('holiday_desc', array('label'=>false)); ?>
		</td>
		<td style="width: 8%;">
			<?php echo $this->Form->end('Add'); ?>
		</td>
	</tr>

	<?php foreach ($holidays as $holiday): ?>
	<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
		<?php 
			if (isset($editmode)) {
				if ($editmode == $holiday['Holiday']['id']) {
					echo $this->Form->create('Holiday', array('action' => 'edit'));
				}
			}
		?>
		<td>
			<?php
				if (isset($editmode)) {
					if ($editmode == $holiday['Holiday']['id']) {
						 echo $this->Form->input('country_id', array('label'=>false, 'default'=>$countryid));
					}
					else {
						echo $holiday['Country']['country_name'];
					}
				}
				else {
					echo $holiday['Country']['country_name'];
				}
				
			?>
		</td>
		<td>
			<?php
				if (isset($editmode)) {
					if ($editmode == $holiday['Holiday']['id']) {
						echo $this->Form->input('holiday_day', array('label'=>false, 'default'=>$holiday['Holiday']['holiday_day']));
					}
					else {
						echo $holiday['Holiday']['holiday_day'];
					}
				}
				else {
					echo $holiday['Holiday']['holiday_day'];
				}
				
			?>
		</td>
		<td>
			<?php
				if (isset($editmode)) {
					if ($editmode == $holiday['Holiday']['id']) {
						echo $this->Form->input('holiday_month', array('label'=>false, 'options' => array(1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10,11=>11,12=>12),'default'=>$holiday['Holiday']['holiday_month']));
					}
					else {
						echo $holiday['Holiday']['holiday_month'];
					}
				}
				else {
					echo $holiday['Holiday']['holiday_month'];
				}
				
			?>
		</td>
		<td>
			<?php
				if (isset($editmode)) {
					if ($editmode == $holiday['Holiday']['id']) {
						echo $this->Form->input('holiday_desc', array('label'=>false, 'default'=>$holiday['Holiday']['holiday_desc']));
					}
					else {
						echo $holiday['Holiday']['holiday_desc'];
					}
				}
				else {
					echo $holiday['Holiday']['holiday_desc'];
				}
			?>
		</td>
		<td>
			<?php
				if (isset($editmode)) {
					if ($editmode == $holiday['Holiday']['id']) {
						echo $this->Form->input('id', array('type'=>'hidden', 'default'=>$holiday['Holiday']['id']));
						echo $this->Form->end('Submit');
					}
					else {
						echo $this->Html->link('Edit', '/holidays/edit/'.$holiday['Holiday']['id'].'/'.$countryid);
						echo '&nbsp';
						echo $this->Html->link('Del', '/holidays/delete/'.$holiday['Holiday']['id'].'/'.$countryid);
					}
				}
				else {
					echo $this->Html->link('Edit', '/holidays/edit/'.$holiday['Holiday']['id'].'/'.$countryid);
					echo '&nbsp';
					echo $this->Html->link('Del', '/holidays/delete/'.$holiday['Holiday']['id'].'/'.$countryid);
				}
			?>
		</td>
	</tr>
	<?php endforeach; ?>

</table>
