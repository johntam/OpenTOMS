$(document).ready(function() {
	var uploader = new qq.FileUploader({
			element: document.getElementById('file-uploader'),
			action: '/fileuploader.php',
			params: {
				host:		$("#host").html(),
				username:	$("#username").html(),
				password:	$("#password").html(),
				database:	$("#database").html()
			},
			debug: true
		});
		
	$('#datefilter').datepicker({ dateFormat: 'yy-mm-dd' });
	$('#pricedatepicker').datepicker({ dateFormat: 'yy-mm-dd' });
	
	$('#datefilter').change(function() {
		$('#fund_price_table').html('');
		showFundPrices();
	});
	
	$('#secfilter').focusout(function() {
		$('#fund_price_table').html('');
		showFundPrices();
	});
	
	function showFundPrices() {
		$.post("/prices/ajax_fundprices?" + (new Date()).getTime(),
			{ sec_filter : $("#secfilter").val() , date_filter : $("#datefilter").val() },
			function(data) {
				if (data.length > 0) {
					$('#fund_price_table').html(data);
				}
			},
			"text"
		);
	}	
	
	showFundPrices();
});