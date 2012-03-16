$(document).ready(function() {
	$(document).ready(function() {
		$('#dateinput').datepicker({ dateFormat: 'yy-mm-dd' });
		
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