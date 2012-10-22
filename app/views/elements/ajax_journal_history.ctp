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
	<tr>
		<th>Date</th>
		<th>Type</th>
		<th>Custodian</th>
		<th>Amount</th>
		<th>Currency</th>
		<th>Notes</th>
		<th>Action</th>
	</tr>
<?php if (!empty($journals)) { ?>	
	<?php foreach ($journals as $journal): ?>
		<tr<?php echo $cycle->cycle('', ' class="altrow"');?>>
			
			<td>
				<div id="dispdate"><?php echo $journal['Journal']['trade_date']; ?></div>
				<input type="text" class="editdate" value="<?php echo $journal['Journal']['trade_date']; ?>" size="15" />
			</td>
			<td>
				<?php echo $journal['TradeType']['trade_type']; ?>
			</td>
			<td>
				<?php echo $journal['Custodian']['custodian_name']; ?>
			</td>
			<td style="text-align: right;">
				<div id="dispqty"><?php echo preg_replace('/(\.)(\d*?)(0+)$/', '${1}${2}0',$journal['Journal']['quantity']); ?></div>
				<input type="text" class="editquantity" value="<?php echo $journal['Journal']['quantity']; ?>" />
			</td>
			<td>
				<?php echo $journal['Currency']['currency_iso_code']; ?>
			</td>
			<td>
				<div id="dispnotes"><?php echo $journal['Journal']['notes']; ?></div>
				<input type="text" class="editnotes" value="<?php echo $journal['Journal']['notes']; ?>" />
			</td>
			<td style="text-align: center;">
				<?php 
				if (strtotime($journal['Journal']['trade_date']) > strtotime($lockeddate)) { 
					echo $this->Html->image('edit.png', array('class'=>'editbutton', 'id' => 'edit_'.$journal['Journal']['id']));
					echo $this->Html->image('delete.png', array('class'=>'deletebutton', 'id' => 'delete_'.$journal['Journal']['id']));
					echo $this->Html->image('save.png', array('class'=>'savebutton', 'type'=>'hidden', 'id' => 'save_'.$journal['Journal']['id']));
					echo $this->Html->image('cancel.png', array('class'=>'cancelbutton', 'type'=>'hidden', 'id' => 'cancel_'.$journal['Journal']['id']));
				}
				else {
					echo $this->Html->image('padlock.gif', array('alt'=>'This journal is before the latest locked balance date'));
				} ?>
				<input type="text" style="display: none;" class="tradeid" value="<?php echo $journal['Journal']['id']; ?>" />
			</td>
		</tr>
<?php endforeach; } ?>

<script type="text/javascript">
	$(document).ready(function() {
		$('.editdate').datepicker({ dateFormat: 'yy-mm-dd' });
		$('.editdate').hide();
		$('.editquantity').hide();
		$('.editnotes').hide();
		$('.savebutton').hide();
		$('.cancelbutton').hide();
		
		$('.editbutton').click(function() {
			var top = $(this).closest('tr');
			$(top).find('.editdate').show();
			$(top).find('.editquantity').show();
			$(top).find('.editnotes').show();
			$(top).find('.savebutton').show();
			$(top).find('.cancelbutton').show();
			$(top).find('.editbutton').hide();
			$(top).find('.deletebutton').hide();
			$(top).find('#dispdate').hide();
			$(top).find('#dispqty').hide();
			$(top).find('#dispnotes').hide();
		});
		
		$('.savebutton').click(function() {
			var top = $(this).closest('tr');
			var dt = $(top).find('.editdate').val();
			var qty = $(top).find('.editquantity').val();
			var notes = $(top).find('.editnotes').val();
			var tradeid = $(top).find('.tradeid').val();
			$(top).addClass('highred');
			
			//send this to database
			$.post("/journals/edit?" + (new Date()).getTime(),
			{ date : dt , quantity : qty , id : tradeid , notes : notes},
			function(data) {
				if (data.length > 0) {					
					if (data == "Y") {
						//saved ok
						$(top).find('#dispdate').html(dt);
						$(top).find('#dispqty').html(qty);
						$(top).find('#dispnotes').html(notes);
						$(top).find('.editdate').hide();
						$(top).find('.editquantity').hide();
						$(top).find('.editnotes').hide();
						$(top).find('.savebutton').hide();
						$(top).find('.cancelbutton').hide();
						$(top).find('.editbutton').show();
						$(top).find('.deletebutton').show();
						$(top).find('#dispdate').show();
						$(top).find('#dispqty').show();
						$(top).find('#dispnotes').show();
					}
					else {
						alert(data);
					}
				}
				$(top).removeClass('highred');
			},
			"text"
			);
		});
		
		$('.cancelbutton').click(function() {
			var top = $(this).closest('tr');
			var dt = $(top).find('.editdate').val();
			var qty = $(top).find('.editquantity').val();
			var tradeid = $(top).find('.tradeid').val();	
			
			//cancel edit
			$(top).find('.editdate').hide();
			$(top).find('.editquantity').hide();
			$(top).find('.savebutton').hide();
			$(top).find('.cancelbutton').hide();
			$(top).find('.editbutton').show();
			$(top).find('.deletebutton').show();
			$(top).find('#dispdate').show();
			$(top).find('#dispqty').show();
		});
		
		$('.deletebutton').click(function() {
			var top = $(this).closest('tr');
			var dt = $(top).find('.editdate').val();
			var qty = $(top).find('.editquantity').val();
			var tradeid = $(top).find('.tradeid').val();	
			$(top).addClass('highred');
			
			//delete this record from database
			$.post("/journals/delete?" + (new Date()).getTime(),
			{ id : tradeid },
			function(data) {
				if (data.length > 0) {
					if (data == "Y") {
						//deleted ok
						$(top).html('');
					}
					else {
						alert('Problem deleting journal, please try again');
					}
				}
				$(top).removeClass('highred');
			},
			"text"
			);
		});
	});
</script>
