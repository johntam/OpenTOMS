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
		$("#order_price").val($("#order_price").val().replace(/(\.)(\d*?)(0+)$/, '$1$20'));
		put_seclink();
		$('#tradedatepicker').datepicker({ dateFormat: 'yy-mm-dd' });
		$('#decisiondatepicker').datepicker({ dateFormat: 'yy-mm-dd' });
		$('#orderdatepicker').datepicker({ dateFormat: 'yy-mm-dd' });
		$('input[type="submit"]').attr('disabled','disabled'); //disable submit button initially
		$("#TradeDecisionTimeTime").val(get_current_time());
		$("#TradeOrderTimeTime").val(get_current_time());
	
		$("#TradeSecId").change(function() {
			$("#sec_link").html("");
			$("#TradeCurrencyId").load("/trades/ajax_ccydropdown?" + (new Date()).getTime() , $("#TradeSecId").serialize());
			$("#TradeQuantity").val("");
			put_seclink();
		});
		
		$("#TradeTradeTypeId").change(function() {
			if ($("#order_qty").val() != '') {
				calc_quantity();
				
				$.when( calc_quantity() )
					.then(function(){
						calc_notional_value();
					})
					.fail(function() {
					// AJAX request failed
					});
			}
		});
		
		$("#order_qty").focusout(function() {
				calc_quantity();
				
				$.when( calc_quantity() )
					.then(function(){
						calc_notional_value();
					})
					.fail(function() {
					// AJAX request failed
					});
			});
			
		$("#order_price").focusout(function() {
			calc_notional_value();
		});
		
		$("#TradeSecId").change(function() {
			if ($("#TradeSecId option:selected").text() != 'Select Security') {
				$('input[type="submit"]').removeAttr('disabled');
			}
			else {
				$('input[type="submit"]').attr('disabled','disabled'); //disable submit button
			}
		});
		
		
	});
	
	
	function calc_notional_value() {
		if (($("#order_qty").val() != '') && ($("#order_price").val() != '')) {
			var ntl = parseFloat($("#order_qty").val().replace(/\,/g,'')) * parseFloat($("#order_price").val().replace(/\,/g,''));
			$("#notional").html(addCommas(ntl.toFixed(0)));
		}
	}
	
	
	
	function calc_quantity() {
		return $.Deferred(function( deferred_obj ){
			$.post("/trades/ajax_quantity?" + (new Date()).getTime(),
				{ quantity : $("#order_qty").val() , tradetype : $("#TradeTradeTypeId").val() },
				function(data) {
					$("#order_qty").val(data);
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
	
	
	function addCommas(nStr) {
		nStr += '';
		x = nStr.split('.');
		x1 = x[0];
		x2 = x.length > 1 ? '.' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + ',' + '$2');
		}
		return x1 + x2;
	}
	
	
	function get_current_time() {
		var currentTime = new Date()
		var hours = currentTime.getHours()
		var minutes = currentTime.getMinutes()

		if (minutes < 10)
			minutes = "0" + minutes

		return(hours + ":" + minutes);
	}
