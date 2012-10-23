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
		<td colspan="3">
			<h1>Edit Currency</h1>		
		</td>
	</tr>
	
	<tr class="highlight">
		<td>Currency Code</td>
		<td>Currency Name</td>
		<td>Security</td>
	</tr>
	
		<tr>
			<?php echo $this->Form->create(null, array('action' => 'edit')); ?>
			<td><?php echo $this->Form->input('currency_iso_code', array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('currency_name', array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('sec_id', array('label'=>false)); ?></td>
			<td><?php
				echo $this->Form->input('id', array('type' => 'hidden')); 
				echo $this->Form->end('Update Currency');
			?></td>
		</tr>
</table>

