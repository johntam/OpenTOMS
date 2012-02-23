$(document).ready(function() {
	if ($("#missing").html() == "Y") {
		$("#missingmessage").html("Cannot lock because of missing prices/fx rates");
	}
	else {
		$("#missingmessage").html("");
	}
});