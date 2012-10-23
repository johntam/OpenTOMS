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

<table style="width: 40%;margin-left:30%;margin-right:30%;">
	<tr>
		<td colspan="3">
			<h1>Edit Custodian</h1>		
		</td>
	</tr>
	
	<tr class="highlight">
		<td>Custodian Short Name</td>
		<td>Custodian Long Name</td>
	</tr>
	
	<tr>
		<?php echo $this->Form->create(null, array('action' => 'edit')); ?>
		<td style="width: 15%;"><?php echo $this->Form->input('custodian_name', array('label'=>false)); ?></td>
		<td><?php echo $this->Form->input('custodian_long_name', array('label'=>false,'size'=>50)); ?></td>
	</tr>
	
	<tr>
		<td colspan="2"><?php
			echo $this->Form->input('id', array('type' => 'hidden')); 
			echo $this->Form->end('Update Custodian');
		?></td>
	</tr>
</table>
