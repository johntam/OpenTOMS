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

<table>
	<tr>
		<td>
			<h1>Trade Reasons</h1>
			<?php echo $this->Html->link('Add New Reason', array('controller' => 'reasons', 'action' => 'add')); ?>
		</td>
	</tr>
	<tr>
		<th>Id</th>
		<th>Trade Reason</th>
	</tr>

	<!-- Here is where we loop through our $reasons array, printing out reason info -->

	<?php foreach ($reasons as $reason): ?>
	<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
		<td style="width: 10%;"><?php echo $reason['Reason']['id']; ?></td>
		<td><?php echo $reason['Reason']['reason_desc']; ?></td>
	</tr>
	<?php endforeach; ?>
</table>
