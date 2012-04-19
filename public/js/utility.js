var utilities = {
	"player": null,
	"setPlayer": function(pid, name) {
		this.player = {
			"pid": pid,
			"name": name
		};
		this.dispatch(this.events.PLAYER_CHANGED, this.player);
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