$(document).ready(function() {
		alter_form_coupon();
		$("#TradeExecutionPrice").val($("#TradeExecutionPrice").val().replace(/(\.)(\d*?)(0+)$/, '$1$20'));
		$("#TradeQuantity").val($("#TradeQuantity").val().replace(/(\.)(\d*?)(0+)$/, '$1$20'));
		$("#commission_busy").hide();
		$("#tax_busy").hide();
		$("#othercosts_busy").hide();
		$("#consideration_busy").hide();
		$("#accrued_busy").hide();
		$("#settdate_busy").hide();
		$("#notional_busy").hide();
		if ($("#create_balance_checkbox").length) {$("#create_balance_checkbox").hide()}
		
		if ($("#execute_button").length) {
			if ($("#TradeExecuted:checked").val() != undefined) {
				if ($("#execute_button").length) {$("#execute_button").show()};
				if ($("#update_button").length) {$("#update_button").hide()};
				
			}
			else {
				if ($("#execute_button").length) {$("#execute_button").hide()};
				if ($("#update_button").length) {$("#update_button").show()};
				clearcosts();
			}
		}
		
		put_seclink();
		$('#tradedatepicker').datepicker({ dateFormat: 'yy-mm-dd' });
		$('#settlementdatepicker').datepicker({ dateFormat: 'yy-mm-dd' });
		$('#decisiondatepicker').datepicker({ dateFormat: 'yy-mm-dd' });
		$('#orderdatepicker').datepicker({ dateFormat: 'yy-mm-dd' });
	
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
			if ($("#TradeOrderQuantity").length) { 
				var newqty = $("#TradeQuantity").val();
				var oldqty = $("#TradeOrderQuantity").val();
				if (Math.abs(newqty) < Math.abs(oldqty)) {
					$("#create_balance_checkbox").show();
				}
				else {
					$("#create_balance_checkbox").hide();
				}
			}
			
			calc_quantity();
			$.when( calc_quantity() )
			   .then(function(){
					recalculate_consideration();
				})
			   .fail(function() {
				  // AJAX request failed
				  alert("Connection to database failed.");
			   });
		});
		
		$("#TradeExecutionPrice").focusout(function() {
			recalculate_consideration();
		});
		
		$("#TradeExecuted").click(function() {
			if ($("#TradeExecuted:checked").val() != undefined) {
				if ($("#execute_button").length) {$("#execute_button").show()};
				if ($("#update_button").length) {$("#update_button").hide()};
				$.when( calc_quantity() )
			   .then(function(){
					recalculate_consideration();
				})
			   .fail(function() {
				  // AJAX request failed
				  alert("Connection to database failed.");
			   });
			}
			else {
				if ($("#execute_button").length) {$("#execute_button").hide()};
				if ($("#update_button").length) {$("#update_button").show()};
				clearcosts();
			}
		});
		
		$("#TradeBrokerId").change(function() {
			recalculate_consideration();
		});
		
		$("#TradeCurrencyId").change(function() {
			recalculate_consideration();
		});
		
		$("#TradeTradeTypeId").change(function() {
			alter_form_coupon();
			calc_quantity();
			
			$.when( calc_quantity() )
			   .then(function(){
					recalculate_consideration();
				})
			   .fail(function() {
				  // AJAX request failed
				  alert("Connection to database failed.");
			   });
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
		
		
		$("#tradedatepicker").change(function() {
			if ($("#TradeSecId option:selected").text() != 'Select Security') {
				$('input[type="submit"]').attr('disabled','disabled'); //disable submit button
				$("#settdate_busy").show();
				check_price();
				calc_settdate();
				check_tradedate();
				
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
				if( $('#TradeExecutionPrice').is(':visible') ) {
					$("#TradeExecutionPrice").hide();
				}
				$("#row4").hide();
				$("#row5").hide();
				$("#row6").hide();
				$("#row7").hide();
				$("#head4").hide();
				$("#head5").hide();
				$("#head6").hide();
				$("#head7").hide();
				$("#TradeExecutionPrice").val("");
			}
			else {
				if( $('#TradeExecutionPrice').is(':hidden') ) {
					$("#TradeExecutionPrice").show();
					$("#TradeExecutionPrice").val($("#stored_price").html().replace(/(\.)(\d*?)(0+)$/, '$1$20'));
				}
				$("#row4").show();
				$("#row5").show();
				$("#row6").show();
				$("#row7").show();
				$("#head4").show();
				$("#head5").show();
				$("#head6").show();
				$("#head7").show();
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
						$("#settlementdatepicker").val(data);
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
		var tt = $("#TradeTradeTypeId option:selected").text();
		if ((tt == "Coupon Income") || (tt == "Dividend Income")) {
			$("#TradeConsideration").val($("#TradeQuantity").val());
			$("#TradeExecutionPrice").val("1");
			$("#TradeExecuted").val("1");
		}
		else if ((tt == "Coupon Expense") || (tt == "Dividend Expense")) {
			$("#TradeConsideration").val("-" + $("#TradeQuantity").val());
			$("#TradeExecutionPrice").val("1");
			$("#TradeExecuted").val("1");
		}
		else {
			var checked = $("#TradeExecuted:checked").val() != undefined;
			checked = checked && ($("#TradeSecId option:selected").text() != 'Select Security');
			checked = checked && ($("#TradeQuantity").val() != '');
			checked = checked && ($("#TradeExecutionPrice").val() != '');
		
			if (checked) {
				$('input[type="submit"]').attr('disabled','disabled'); //disable submit button
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
	
	function check_tradedate() {
		if ($("#TradeSecId option:selected").text() != 'Select Security') {
			$.post("/trades/ajax_checktradedate?" + (new Date()).getTime(),
					{ trade_date : $("#tradedatepicker").val() , sec_id : $("#TradeSecId option:selected").val() },
					function(data) {
						if (data == 1) {
							alert("Warning: trade date is a weekend");
						}
						else if (data == 2) {
							alert("Warning: trade date falls on a holiday");
						}						
					},
					"text"
				);
		}
	}