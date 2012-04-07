$('#page').live('pageinit', function() {
	var STATE_STOPPED = 0;
	var STATE_STARTED = 1;
	var MS_TO_MINUTES = 0.000016666666666666667;
	var MINUTES_TO_MS = 60000;
	var SECONDS_TO_MS = 1000;

	var state = STATE_STOPPED;
	var interval;
	var startTime = 0;
	var currentRunTime = 0;
	var timer;
	var toggle = $("#timerToggle");
	var reset = $("#timerReset");
	var penalty = $("#timerPenalty");
	var penaltyTime = 10;
	var penalties = 0;
	var timerDisplay = $("#timer");

	toggle.click(function() {
		switch (state) {
			case STATE_STOPPED:
				startTimer();
				break;
			case STATE_STARTED:
				stopTimer();
				break;
		}
	});

	reset.click(function() {
		stopTimer();
		currentRunTime = 0;
		timerDisplay.html("00:00.000");
		state = STATE_STOPPED;
		penalties = 0;
	});

	penalty.click(function() {
		if (state == STATE_STOPPED) {
			return;
		}

		++penalties;
		timer.setTime(timer.getTime() - penaltyTime*SECONDS_TO_MS);
	});

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
		promptSave();
	};

	var getRuntime = function() {
		return new Date().getTime() - timer.getTime();
	};

	var refreshTimerDisplay = function() {
		currentRunTime = getRuntime();
		var minutes = Math.floor(currentRunTime*MS_TO_MINUTES);
		var seconds = Math.floor((currentRunTime - minutes*MINUTES_TO_MS) / 1000);
		var ms = currentRunTime - minutes*MINUTES_TO_MS - seconds*SECONDS_TO_MS;

		var minutesDisplay = minutes < 10 ? "0"+String(minutes) : String(minutes);
		var secondsDisplay = seconds < 10 ? "0"+String(seconds) : String(seconds);

		var msDisplay;
		if (ms < 10) {
			msDisplay = "00"+String(ms);
		} else if (ms < 100) {
			msDisplay = "0"+String(ms);
		} else {
			msDisplay = String(ms);
		}

		var display = minutesDisplay+":"+secondsDisplay+"."+msDisplay;
		timerDisplay.html(display);
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
						save(player);
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

	var save = function(player) {

	};
});