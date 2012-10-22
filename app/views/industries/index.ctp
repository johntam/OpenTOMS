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
		<h1>Industries</h1>
		<?php echo $this->Html->link('Add Industry', array('controller' => 'industries', 'action' => 'add')); ?>
		</td>
	</tr>
	<tr>
		<th>Industry Code</th>
		<th>Industry Name</th>
		<th>Edit</th>
	</tr>

	<?php foreach ($industries as $industry): ?>
	<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
		<td><?php echo $industry['Industry']['industry_code']; ?></td>
		<td><?php echo $industry['Industry']['industry_name']; ?></td>
		<td><?php echo $this->Html->link('Edit', array('action' => 'edit', $industry['Industry']['id']));?></td>
	</tr>
	<?php endforeach; ?>
</table>
