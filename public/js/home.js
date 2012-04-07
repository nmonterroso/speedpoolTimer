$('#page').live('pageinit', function() {
	var playerSelect = $("#playerSelect");
	var players = [];

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
			utilities.player = {
				"pid": val,
				"name": $(this).find("option:selected").text()
			};
			showPlayer();
		}
	});

	// get player list from DOM
	var initPlayers = function() {
		$(".player").each(function() {
			var pid = $(this).find(".id").text();
			var name = $(this).find(".name").text();

			players.push({
				"pid": pid,
				"name": name
			});
		});

		$(".player").remove();
		refreshPlayerList();
	};

	var addNewPlayer = function(name) {
		utilities.resetSelect(playerSelect);
		$.ajax("/ajax/newPlayer.php", {
			"type": "post",
			"data": {"name": name},
			"dataType": "json",
			"success": function(json) {
				if (json.error) {
					utilities.error(json.message);
					return;
				}

				players.push({
					"pid": json.pid,
					"name": name
				});

				refreshPlayerList(json.pid);
			}
		});
	};

	var refreshPlayerList = function(selectPid) {
		selectPid = selectPid || -1;
		players.sort(function(p1, p2) {
			var p1Name = p1.name.toLowerCase();
			var p2Name = p2.name.toLowerCase();

			return p1Name < p2Name ? -1 : p1Name > p2Name ? 1 : 0;
		});

		playerSelect.find("option:gt(1)").remove();
		$.each(players, function(i, e) {
			playerSelect.append("<option value='"+e.pid+"'>"+e.name+"</option>");
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

	initPlayers();
});