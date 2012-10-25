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
			<h1>Security Types</h1>
			<?php echo $this->Html->link('Add Security Type', array('controller' => 'sec_types', 'action' => 'add')); ?>
		</td>
	</tr>
	<tr>
		<th>Id</th>
		<th>Sec Type Name</th>
		<th>Bond</th>
		<th>Deriv</th>
		<th>Exchrate</th>
		<th>CFD</th>
		<th>Equity</th>
		<th>FX</th>
		<th>OTC</th>
		<th>Bloomberg Yellow Key</th>
		<th>Active</th>
	</tr>

	<?php foreach ($sectypes as $sectype): ?>
		<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
			<td style="width: 5%;"><?php echo $sectype['SecType']['id']; ?></td>
			<td><?php echo $sectype['SecType']['sec_type_name']; ?></td>
			<td><?php echo $sectype['SecType']['bond']; ?></td>
			<td><?php echo $sectype['SecType']['deriv']; ?></td>
			<td><?php echo $sectype['SecType']['exchrate']; ?></td>
			<td><?php echo $sectype['SecType']['cfd']; ?></td>
			<td><?php echo $sectype['SecType']['equity']; ?></td>
			<td><?php echo $sectype['SecType']['fx']; ?></td>
			<td><?php echo $sectype['SecType']['otc']; ?></td>
			<td><?php echo $sectype['SecType']['yellow_key']; ?></td>
			<td><?php 
					
					if ($sectype['SecType']['act']==0) {
						echo $this->Html->link('Activate', array('action' => 'activate', $sectype['SecType']['id']));
					} 
					else {
						echo $this->Html->link('Deactivate', array('action' => 'deactivate', $sectype['SecType']['id']));
					}
					  
				?></td>
		</tr>
	<?php endforeach; ?>
</table>
