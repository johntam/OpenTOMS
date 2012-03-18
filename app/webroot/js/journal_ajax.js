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