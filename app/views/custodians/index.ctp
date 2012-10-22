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
		<td>
			<h1>Custodians</h1>
			<?php echo $this->Html->link('Add New Custodian', array('controller' => 'custodians', 'action' => 'add')); ?>
		</td>
	</tr>
	<tr>
		<th>Custodian Short Name</th>
		<th>Custodian Long Name</th>
		<th>Edit</th>
	</tr>

	<?php foreach ($custodians as $custodian): ?>
	<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
		<td><?php echo $custodian['Custodian']['custodian_name']; ?></td>
		<td><?php echo $custodian['Custodian']['custodian_long_name']; ?></td>
		<td><?php echo $this->Html->link('Edit', array('action' => 'edit', $custodian['Custodian']['id']));?></td>
	</tr>
	<?php endforeach; ?>
</table>
