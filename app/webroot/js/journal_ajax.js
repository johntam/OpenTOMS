/*
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
*/	

$(document).ready(function() {
	var lockeddate = $('#lockeddate').val();
	$('#dateinput').datepicker({ dateFormat: 'yy-mm-dd', minDate: new Date(lockeddate) });
		
	showHistory();
	
	$('#fundpicker').change(function() {
		$('#journal_history').html('');
		showHistory();
	});
	
	$('#ttpicker').change(function() {
		$('#journal_history').html('');
		showHistory();
	});
	
	function showHistory() {
		$.post("/journals/ajax_history?" + (new Date()).getTime(),
			$("#JournalIndexForm").serialize(),
			function(data) {
				if (data.length > 0) {
					$('#journal_history').html(data);
				}
			},
			"text"
		);
	}	
});
