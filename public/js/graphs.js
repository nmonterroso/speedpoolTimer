$(document).ready(function() {
	var dates = $("#timeSince, #timeUntil").datepicker({
		"maxDate": "+0",
		"defaultDate": "+0",
		"changeMonth": true,
		"numberOfMonths": 1,
		"onSelect": function(selectedDate) {
			var picker = this.id == "timeSince" ? "minDate" : "maxDate";
			var instance = $(this).data("datepicker");
			var date = $.datepicker.parseDate(
				instance.settings.dateFormat || $.datepicker._defaults.dateFormat,
				selectedDate, instance.settings
			);
			dates.not(this).datepicker("option", picker, date);
		}
	});

	$("#viewGraphs").click(function() {
		var since = getUnixEpoch($("#timeSince").datepicker("getDate"));
		var until = getUnixEpoch($("#timeUntil").datepicker("getDate"));
		var players = $("#players").val();

		if (players == null || players.length == 0) {
			return;
		}

		$.ajax("/ajax/graphData.php", {
			"type": "post",
			"data": {
				"data": JSON.stringify({
					"players": players,
					"since": since,
					"until": until
				})
			},
			"dataType": "json",
			"success": function(json) {
				if (json.error) {
					alert("an error occurred :(");
					return;
				}

				updateGraphs(json.graphs);
			}
		});

		return false;
	});

	var updateGraphs = function(data) {

	};

	var getUnixEpoch = function(date) {
		if (date == null) {
			return null;
		}

		return Math.floor(date.getTime() / 1000);
	};
});