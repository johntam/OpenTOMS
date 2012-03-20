$(document).ready(function() {
		alter_form_coupon();
		$("#TradePrice").val($("#TradePrice").val().replace(/(\.)(\d*?)(0+)$/, '$1$20'));
		$("#TradeExecutionPrice").val($("#TradeExecutionPrice").val().replace(/(\.)(\d*?)(0+)$/, '$1$20'));
		handle_execute_checkbox();		
		$("#commission_busy").hide();
		$("#tax_busy").hide();
		$("#othercosts_busy").hide();
		$("#consideration_busy").hide();
		$("#accrued_busy").hide();
		$("#settdate_busy").hide();
		$("#notional_busy").hide();
		put_seclink();
	
		$("#TradeSecId").change(function() {
			$("#sec_link").html("");
			$("#TradeCurrencyId").load("/trades/ajax_ccydropdown?" + (new Date()).getTime() , $("#TradeSecId").serialize());
			$("#TradeQuantity").val("");
			$("#TradeExecutionPrice").val("");
			clearcosts();
			calc_settdate();
			put_seclink();
		});
		
		
		$("#TradeQuantity").focusout(function() {
			recalculate_consideration();
		});
		
		$("#TradeExecutionPrice").focusout(function() {
			recalculate_consideration();
		});
		
		$("#TradeExecuted").click(function() {
			handle_execute_checkbox();
		});
		
		$("#TradeBrokerId").change(function() {
			recalculate_consideration();
		});
		
		$("#TradeCurrencyId").change(function() {
			recalculate_consideration();
		});
		
		$("#TradeTradeTypeId").change(function() {
			alter_form_coupon();
			recalculate_consideration();
		});
		
		$("#TradeCommission").change(function() {
			$('input[type="submit"]').attr('disabled','disabled'); //disable submit button
			$("#consideration_busy").show();
			calc_consideration();
			
			$.when( calc_consideration() )
			   .then(function(){
					$("#consideration_busy").hide();
					$('input[type="submit"]').removeAttr('disabled');
				})
			   .fail(function() {
				  // AJAX request failed
				  alert("Connection to database failed.");
			   });
		});
		
		$("#TradeTax").change(function() {
			$('input[type="submit"]').attr('disabled','disabled'); //disable submit button
			$("#consideration_busy").show();
			calc_consideration();
			
			$.when( calc_consideration() )
			   .then(function(){
					$("#consideration_busy").hide();
					$('input[type="submit"]').removeAttr('disabled');
				})
			   .fail(function() {
				  // AJAX request failed
				  alert("Connection to database failed.");
			   });
		});
		
		$("#TradeOtherCosts").change(function() {
			$('input[type="submit"]').attr('disabled','disabled'); //disable submit button
			$("#consideration_busy").show();
			calc_consideration();
			
			$.when( calc_consideration() )
			   .then(function(){
					$("#consideration_busy").hide();
					$('input[type="submit"]').removeAttr('disabled');
				})
			   .fail(function() {
				  // AJAX request failed
				  alert("Connection to database failed.");
			   });
		});
		
		
		$("#TradeAccrued").change(function() {
			$('input[type="submit"]').attr('disabled','disabled'); //disable submit button
			$("#consideration_busy").show();
			calc_consideration();
			
			$.when( calc_consideration() )
			   .then(function(){
					$("#consideration_busy").hide();
					$('input[type="submit"]').removeAttr('disabled');
				})
			   .fail(function() {
				  // AJAX request failed
				  alert("Connection to database failed.");
			   });
		});
		
		
		$("select[id^=TradeTradeDate]").change(function() {
			if ($("#TradeSecId option:selected").text() != 'Select Security') {
				$('input[type="submit"]').attr('disabled','disabled'); //disable submit button
				$("#settdate_busy").show();
				check_price();
				calc_settdate();
				
				$.when( calc_settdate() )
					.then(function(){
						$('input[type="submit"]').removeAttr('disabled');
						recalculate_consideration();
				});
			}
		});
		
		
		$("select[id^=TradeSettlementDate]").change(function() {
			recalculate_consideration();
		});
		
		
		$("#TradeExecutionPrice").focusout(function() {
			check_price();
		});
		
	});
	
	
	function alter_form_coupon() {
		var tt = $("#TradeTradeTypeId option:selected").text();
			if ((tt.substr(0,6) == "Coupon") || (tt.substr(0,8) == "Dividend")) {
				$("#TradeExecutionPrice").hide();
				$("#TradePrice").hide();
				$("#row4").hide();
				$("#row5").hide();
				$("#row6").hide();
				$("#head4").hide();
				$("#head5").hide();
				$("#head6").hide();
				$("#TradeExecutionPrice").val('');
			}
			else {
				$("#TradeExecutionPrice").show();
				$("#TradePrice").show();
				$("#row4").show();
				$("#row5").show();
				$("#row6").show();
				$("#head4").show();
				$("#head5").show();
				$("#head6").show();
			}
	}
	
	
	function handle_execute_checkbox() {
		var checked = $("#TradeExecuted:checked").val() != undefined;
		if (!checked) {
			$("#TradeExecutionPrice").val("");
			$("#TradeExecutionPrice").attr("readonly", "readonly");
			$("#TradeExecutionPrice").css("background-color","silver");
			clearcosts();
		}
		else {
			$("#TradeExecutionPrice").removeAttr("readonly");
			$("#TradeExecutionPrice").css("background-color","white");
		}
	}
	
	
	function clearcosts() {
		$("#TradeCommission").val("");
		$("#TradeTax").val("");
		$("#TradeOtherCosts").val("");
		$("#TradeConsideration").val("");
		$("#TradeNotionalValue").val("");
		$("#TradeAccrued").val("");
		$("#accrued_error").hide();
	}
	
	
	function calc_settdate() {
		return $.Deferred(function( deferred_obj ){
			$.post("/trades/ajax_settdate?" + (new Date()).getTime(),
				$("#TradeInputForm").serialize(),
				function(data) {
					if (data.indexOf("-") > 0) {
						var myDate = data.split("-");
						$("#TradeSettlementDateMonth").val(myDate[1]);
						$("#TradeSettlementDateDay").val(myDate[2]);
						$("#TradeSettlementDateYear").val(myDate[0]);
						$("#settdate_busy").hide();
					}
					
					deferred_obj.resolve();
				},
				"text"
			);
		}).promise();
	}
	
	
	function check_price() {
		$.post("/trades/ajax_checkprice?" + (new Date()).getTime(),
			$("#TradeInputForm").serialize(),
			function(data) {
				if (data.length > 0) {
					alert(data);
				}
			},
			"text"
		);
	}
	
	function calc_consideration() {
		return $.Deferred(function( deferred_obj ){
			$.post("/trades/ajax_consid?" + (new Date()).getTime(),
				$("#TradeInputForm").serialize(),
				function(data) {
					if (data.length > 0) {
						var parts = data.split("|");
						$("#TradeConsideration").val(parts[0]);
						if (parts[1] != 0) {
							$("#TradeNotionalValue").val(parts[1]);
						}
						$("#consideration_busy").hide();
						$("#notional_busy").hide();
					}
					
					deferred_obj.resolve();
				},
				"text"
			);
		}).promise();
	}
	
	
	function recalculate_consideration() {
		var checked = $("#TradeExecuted:checked").val() != undefined;
		
		checked = checked && ($("#TradeSecId option:selected").text() != 'Select Security');
		checked = checked && ($("#TradeQuantity").val() != '');
		checked = checked && ($("#TradeExecutionPrice").val() != '');
	
		if (checked) {
			$('input[type="submit"]').attr('disabled','disabled'); //disable submit button
			calc_quantity();
			calc_commission();
			calc_tax();
			calc_othercosts();
			calc_accrued();
			
			$.when( calc_quantity(), calc_commission(), calc_tax(), calc_othercosts(), calc_accrued() )
			   .then(function(){
			   
				  calc_consideration();
				  $.when( calc_consideration() )
					.then(function() {
						//activate submit button
						$('input[type="submit"]').removeAttr('disabled');
					})
					.fail(function() {
					  // AJAX request failed
					  alert("Connection to database failed.");
					});
			   })
			   .fail(function() {
				  // AJAX request failed
				  alert("Connection to database failed.");
			   });
		}
	}
	
	function calc_quantity() {
		return $.Deferred(function( deferred_obj ){
			$.post("/trades/ajax_quantity?" + (new Date()).getTime(),
				{ quantity : $("#TradeQuantity").val() , tradetype : $("#TradeTradeTypeId").val() },
				function(data) {
					$("#TradeQuantity").val(data);
					deferred_obj.resolve();
				},
				"text"
			);
		}).promise();
	}
	
	function calc_commission() {
		return $.Deferred(function( deferred_obj ){
			$("#commission_busy").show();
			$.post("/trades/ajax_commission?" + (new Date()).getTime() , 
							$("#TradeInputForm").serialize(), 
							function(data) { 
								$("#commission_busy").hide(); 
								$("#consideration_busy").show();
								$("#notional_busy").show();
								$("#TradeCommission").val(data);
								deferred_obj.resolve();
							},
							"text"
					);
		}).promise();
	}
	
	function calc_tax() {
		return $.Deferred(function( deferred_obj ){
			$("#tax_busy").show();
			$.post("/trades/ajax_tax?" + (new Date()).getTime() , 
						$("#TradeInputForm").serialize(), 
						function(data) { 
							$("#tax_busy").hide(); 
							$("#consideration_busy").show();
							$("#notional_busy").show();
							$("#TradeTax").val(data);
							deferred_obj.resolve(); 
						},
						"text"
					);
		}).promise();
	}
	
	
	function calc_othercosts() {
		return $.Deferred(function( deferred_obj ){
			$("#othercosts_busy").show();
			$.post("/trades/ajax_othercosts?" + (new Date()).getTime() , 
						$("#TradeInputForm").serialize(), 
						function(data) { 
							$("#othercosts_busy").hide(); 
							$("#consideration_busy").show();
							$("#notional_busy").show();
							$("#TradeOtherCosts").val(data);
							deferred_obj.resolve();
						},
						"text"
					);
		}).promise();
	}
	
	function calc_accrued() {
		return $.Deferred(function( deferred_obj ){
			$("#accrued_busy").show();
			$.post("/trades/ajax_accrued?" + (new Date()).getTime() , 
							$("#TradeInputForm").serialize(), 
							function(data) { 
								$("#accrued_busy").hide(); 
								$("#consideration_busy").show();
								$("#notional_busy").show();
								
								if (data.substr(0,5) == "error") {
									$("#accrued_error").html(data.substr(6));
									$("#accrued_error").show();
								}
								else {
									$("#TradeAccrued").val(data);
									$("#accrued_error").hide();
								}
								deferred_obj.resolve();
							},
							"text"
					);
		}).promise();
	}
	
	function put_seclink() {
		if ($("#TradeSecId option:selected").text() != 'Select Security') {
				$.post("/trades/ajax_seclink?" + (new Date()).getTime() , 
						$("#TradeInputForm").serialize(), 
						function(data) { 
							$("#sec_link").html(data);
						},
						"text"
					);
			}
	}