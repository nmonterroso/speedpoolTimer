$('#page').live('pageinit', function() {
	var playerSelect = $("#playerSelect");

	playerSelect.change(function() {
		var val = $(this).val();

		if (val == 0) { // creating new
			hidePlayer();
			$("<div>").simpledialog2({
				"mode": "button",
				"headerText": "New Player",
				"headerClose": false,
				"buttonInput": true,
				"buttons": {
					"Add": {
						"click": function() {
							var name = $.trim($.mobile.sdLastInput);
							if (name != "") {
								addNewPlayer(name);
							}

							utilities.resetSelect(playerSelect);
						}
					},
					"Close": {
						"click": function() {
							utilities.resetSelect(playerSelect);
						},
						"icon": "delete",
						"theme": "c"
					}
				}
			});
		} else {
			utilities.setPlayer(val);
			showPlayer();
		}
	});

	var addNewPlayer = function(name) {
		utilities.resetSelect(playerSelect);
		utilities.busy("creating");
		$.ajax("/ajax/newPlayer.php", {
			"type": "post",
			"data": {"name": name},
			"dataType": "json",
			"success": function(json) {
				if (json.error) {
					utilities.error(json.message);
					return;
				}

				var player = {
					"pid": json.pid,
					"name": name
				};

				utilities.addPlayer(player.pid, player.name, true);
				refreshPlayerList(json.pid);
			},
			"complete": function() {
				utilities.unbusy();
			}
		});
	};

	var refreshPlayerList = function(selectPid) {
		selectPid = selectPid || -1;

		playerSelect.find("option:gt(1)").remove();
		$.each(utilities.players(), function(i, player) {
			playerSelect.append("<option value='"+player.pid+"'>"+player.name+"</option>");
		});

		utilities.resetSelect(playerSelect, selectPid);
		if (selectPid != -1) {
			showPlayer();
		}
	};

	var showPlayer = function() {
		$("#play").show();
	};

	var hidePlayer = function() {
		$("#play").hide();
	}

	utilities.initPlayers();
	refreshPlayerList();
});