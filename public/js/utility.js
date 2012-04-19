var utilities = {
	"player": null,
	"_players": [],
	"_constants": {
		"MS_TO_MINUTES": 0.000016666666666666667,
		"MINUTES_TO_MS": 60000,
		"SECONDS_TO_MS": 1000
	},
	"players": function() {
		var players = [];
		for (var pid in this._players) {
			players.push({
				"pid": this._players[pid].pid,
				"name": this._players[pid].name
			});
		}

		players.sort(function(p1, p2) {
			var p1Name = p1.name.toLowerCase();
			var p2Name = p2.name.toLowerCase();

			return p1Name < p2Name ? -1 : p1Name > p2Name ? 1 : 0;
		});

		return players;
	},
	"setPlayer": function(pid) {
		var player = this._players[pid] || false;

		if (player) {
			this.player = player;
			this.dispatch(this.events.PLAYER_CHANGED, this.player);
		}
	},
	"addPlayer": function(pid, name, setActive) {
		setActive = setActive || false;
		this._players[pid] = {
			"pid": pid,
			"name": name
		};

		if (setActive) {
			this.setPlayer(pid);
		}
	},
	"getPlayer": function(pid) {
		return this._players[pid] || false;
	},
	"initPlayers": function() {
		$(".player").each(function() {
			var pid = $(this).find(".id").text();
			var name = $(this).find(".name").text();

			utilities.addPlayer(pid, name);
		});

		$(".player").remove();
	},
	"resetSelect": function(selectMenu, defaultValue) {
		defaultValue = defaultValue || -1;
		selectMenu
			.val(String(defaultValue))
			.selectmenu("refresh", true)
			.selectmenu("close")
	},
	"error": function(message) {
		// there's some sort of race condition when coming from another simpledialog2 where this is closed :(
		setTimeout(function(){
			$("<div>").simpledialog2({
				"mode": "button",
				"headerText": "Error",
				"buttonPrompt": message,
				"buttons": {
					"OK": {
						"click": function() {
							close();
						}
					}
				}
			});
		}, 1000);
	},
	"timerDisplay": function(time) {
		var minutes = Math.floor(time*this._constants.MS_TO_MINUTES);
		var seconds = Math.floor((time - minutes*this._constants.MINUTES_TO_MS) / 1000);
		var ms = time - minutes*this._constants.MINUTES_TO_MS - seconds*this._constants.SECONDS_TO_MS;

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

		return minutesDisplay+":"+secondsDisplay+"."+msDisplay;
	},
	"busy": function(message) {
		$.mobile.showPageLoadingMsg({
			"msgText": message
		});
	},
	"unbusy": function() {
		$.mobile.hidePageLoadingMsg();
	},
	"_events": [],
	"events": {
		"PLAYER_CHANGED": "player changed"
	},
	"subscribe": function(event, callback) {
		this._events[event] = this.events[event] || [];
		this._events[event].push(callback);
	},
	"unsubscribe": function(event, callback) {
		if (this._events[event]) {
			for (var i = 0; i < this._events[event].length; ++i) {
				if (listeners[i] === callback) {
					listeners.splice(i, 1);
					return true;
				}
			}
		}

		return false;
	},
	"dispatch": function(event, args) {
		if (this._events[event]) {
			for (var i = 0; i < this._events[event].length; ++i) {
				this._events[event][i](args);
			}
		}
	}
};