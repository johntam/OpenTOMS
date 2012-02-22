$(document).ready(function() {
	if ($("#missing").html() == "Y") {
		$("#LockButtonID").hide();
		$("#missingmessage").html("Cannot lock because of missing prices/fx rates");
	}
	else {
		$("#LockButtonID").show();
		$("#missingmessage").html("");
	}
});