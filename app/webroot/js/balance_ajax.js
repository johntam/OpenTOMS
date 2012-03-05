$(document).ready(function() {
	$("#RefreshButtonID").hide();
	
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
						$('input[id="' + $(_input).attr('id') + '"]').css("color","black");
						$('input[id="' + $(_input).attr('id') + '"]').attr('disabled','disabled');
						//$(_input).val(data);
						//$(_input).css("color","black");
						//$(_input).attr('disabled','disabled');
						$("#RefreshButtonID").show();
					}
				},
				"text"
			);
	});
});