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

<table style="width: 30%;margin-left:35%;margin-right:35%;">
	<tr>
		<td>
			<h1>Run Reports</h1>
		</td>
	</tr>

	<tr><td><?php echo $this->Form->create('Report'); ?></td></tr>
	<tr><td><?php echo $this->Form->input('fund_id'); ?></td></tr>
	<tr><td><?php echo $this->Form->input('report_type', array('options'=>array('Position'=>'Position','NAV'=>'NAV'))); ?></td></tr>
	<tr><td><?php echo $this->Form->input('run_date', array('type'=>'date')); ?></td></tr>
	<tr><td><?php echo $this->Form->end('Run Report'); ?></td></tr>
	
</table>
