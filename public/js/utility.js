var utilities = {
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
	}
};