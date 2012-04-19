$(document).ready(function() {
	var container = $("#graphContainer");
	var graphs = $("#graphData");

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
		var until = getUnixEpoch($("#timeUntil").datepicker("getDate"), true);
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

	var updateGraphs = function(graphData) {
		graphs.html("");

		$.each(graphData, function(pid, data) {
			var player = utilities.getPlayer(pid);
			if (!player) {
				return;
			}

			var html = "<h4>"+player.name+"</h4>" +
				"games: "+data.numGames+"<br />"+
				"average: "+utilities.timerDisplay(data.average)+"<br />"+
				"penalties: "+data.numPenalties;

			graphs.append(html);

		});

		if (!container.is(":visible")) {
			container.show();
		}
	};

	var getUnixEpoch = function(date, increment) {
		if (date == null) {
			return null;
		}

		increment = increment || false;
		if (increment) {
			date.setTime(date.getTime()+60*60*24*1000);
		}

		return Math.floor(date.getTime() / 1000);
	};

	utilities.initPlayers();
	$.each(utilities.players(), function(i, player) {
		$("#players").append("<option value='"+player.pid+"'>"+player.name+"</option>");
	});
});