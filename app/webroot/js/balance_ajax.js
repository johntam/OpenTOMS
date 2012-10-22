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
	if ($("#missing").html() == "Y") {
		$("#LockButtonID").hide();
		$("#missingmessage").html("Cannot lock because of missing prices/fx rates");
	}
	else {
		$("#LockButtonID").show();
		$("#missingmessage").html("");
	}
	
	$(".missingprices").focusin(function() {
		$(this).val("");
	});
	
	$(".missingprices").change(function() {
		var sec_id;
		var fx_rate;
		var price;
		var pid = $(this).attr('id');
		var p = pid.split("_");
		sec_id = p[1];
		
		if (p[0] == "pr") {
			price = $(this).val();
			fx_rate = 0;
		}
		else {
			fx_rate = $(this).val();
			price = 1;
		}
		
		var _input = this;
		
		$.post("/balances/ajax_enterprice?" + (new Date()).getTime(),
				{ sec_id : sec_id , price_date : $("#dateinput").val() , price : price, fx_rate : fx_rate},
				function(data) {
					if (data.length > 0) {
						$('input[id="' + $(_input).attr('id') + '"]').val(data);
						if (data != "Error") {
							$('input[id="' + $(_input).attr('id') + '"]').css("color","black");
							$('input[id="' + $(_input).attr('id') + '"]').attr('disabled','disabled');
						}
						
						$.post("/balances/ajax_checkfinished?" + (new Date()).getTime(),
							$("#maintable").find('input').serialize(),
							function(data) {
								if (data == "yes") {
									$("#LockButtonID").show();
									$("#missingmessage").html("");
								}
							},
							"text"
						);
					}
				},
				"text"
			);
	});
	
	function isNumber(n) {
	  return !isNaN(parseFloat(n)) && isFinite(n);
	}
});
