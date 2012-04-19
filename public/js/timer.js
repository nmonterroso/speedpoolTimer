$('#page').live('pageinit', function() {
	var STATE_STOPPED = 0;
	var STATE_STARTED = 1;
	var FADE_TIME = 200;

	var state = STATE_STOPPED;
	var interval;
	var currentRunTime = 0;
	var timer;
	var toggle = $("#timerToggle");
	var reset = $("#timerReset");
	var penalty = $("#timerPenalty");
	var penaltiesContainer = $("#penaltyContainer");
	var penaltyTime = 10;
	var penalties = [];
	var timerDisplay = $("#timer");

	toggle.click(function() {
		switch (state) {
			case STATE_STOPPED:
				startTimer();
				break;
			case STATE_STARTED:
				stopTimer();
				promptSave();
				break;
		}
	});

	reset.click(function() {
		stopTimer();
		currentRunTime = 0;
		timerDisplay.html("00:00.000");
		state = STATE_STOPPED;
		resetPenalties();
	});

	penalty.click(function() {
		if (state == STATE_STOPPED) {
			return;
		}

		addPenalty({
			"time": getRuntime(),
			"penaltyAmount": penaltyTime
		});

		timer.setTime(timer.getTime() - penaltyTime*utilities._constants.SECONDS_TO_MS);
	});

	var addPenalty = function(penalty) {
		penalties.push(penalty);

		var display = utilities.timerDisplay(penalty.time);
		penaltiesContainer.append("<tr><td>"+display+"</td><td>"+penalty.penaltyAmount+"</td></tr>");
		if (!penaltiesContainer.is(":visible")) {
			penaltiesContainer.fadeIn(FADE_TIME);
		}
	};

	var resetPenalties = function() {
		penalties = [];

		if (penaltiesContainer.is(":visible")) {
			penaltiesContainer.fadeOut(FADE_TIME, function() {
				penaltiesContainer.find("tr:gt(0)").remove();
			});
		}
	};

	var startTimer = function() {
		timer = new Date();
		timer.setTime(timer.getTime() - currentRunTime);
		state = STATE_STARTED;
		interval = setInterval(refreshTimerDisplay, 50);
		toggle
			.text("Stop")
			.button("refresh", true);
	};

	var stopTimer = function() {
		clearInterval(interval);
		state = STATE_STOPPED;
		refreshTimerDisplay();
		toggle
			.text("Start")
			.button("refresh", true);
	};

	var getRuntime = function() {
		if (timer == null) {
			return 0;
		}

		return new Date().getTime() - timer.getTime();
	};

	var refreshTimerDisplay = function() {
		currentRunTime = getRuntime();
		timerDisplay.html(utilities.timerDisplay(currentRunTime));
	};

	var promptSave = function() {
		var player = utilities.player;
		if (player == null) {
			return;
		}

		$("<div>").simpledialog2({
			"mode": "button",
			"headerText": "Save?",
			"buttonPrompt": "Save time "+timerDisplay.html()+" for "+player.name+"?",
			"buttons": {
				"Yes": {
					"click": function() {
						save(player, currentRunTime, penalties);
						close();
					}
				},
				"No": {
					"click": function() {
						close();
					},
					"icon": "delete",
					"theme": "c"
				}
			}
		});
	};

	var save = function(player, runtime, penalties) {
		utilities.busy("saving");

		$.ajax("/ajax/savePlayerTime.php", {
			"type": "post",
			"data": {
				"data": JSON.stringify({
					"player": player,
					"time": runtime,
					"penalties": penalties
				})
			},
			"dataType": "json",
			"success": function(json) {
				if (json.error) {
					utilities.error(json.message);
					return;
				}
			},
			"complete": function() {
				utilities.unbusy();
			}
		});
	};

	utilities.subscribe(utilities.events.PLAYER_CHANGED, function(player) {
		reset.click();
	});
});