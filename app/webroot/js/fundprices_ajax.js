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
	$('#datefilter').datepicker({ dateFormat: 'yy-mm-dd' });
	$('#pricedatepicker').datepicker({ dateFormat: 'yy-mm-dd' });
	
	var uploader = new qq.FileUploader({
			element: document.getElementById('file-uploader'),
			action: '/fileuploader.php',
			params: {
				host:		'<?php echo $host; ?>',
				username:	'<?php echo $username; ?>',
				password:	'<?php echo $password; ?>',
				database:	'<?php echo $database; ?>'
			},
			debug: true
		});
		
	$('#secidpicker').change(function() {
		showHideButtons();
		
		uploader.setParams({
				f_table:	'sec',
				f_id: $('#secidpicker  option:selected').val(),
				f_date:	$('#pricedatepicker').val()
			});
	});
	
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
	
	function showHideButtons() {
		if ($('#secidpicker  option:selected').val() != 0) {
			$("#file-uploader").show();
			$("#AddFundPriceButton").show();
		}
		else {
			$("#file-uploader").hide();
			$("#AddFundPriceButton").hide();
		}
	}
	
	$("#file-uploader").hide();
	$("#AddFundPriceButton").hide();
	showHideButtons();
	showFundPrices();
});
