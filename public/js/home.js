$('#page').live('pageinit', function() {
	$("#playerSelect").change(function() {
		var val = $(this).val();

		if (val == 0) { // creating new
			$("<div>").simpledialog2({
				"mode": "blank",
				"headerText": "New Player",
				"headerClose": true,
				"blankContent":
					"<form id='newPlayerPrompt' data-ajax='false'>"+
						"<label for='newPlayerName'>Player Name</label>"+
						"<input type='text' name='newPlayerName' id='newPlayerName' data-mini='true' />"+
						"<input type='submit' value='Add' data-mini='true' />"+
					"</form>"
			});
		}
	});

	$("#newPlayerPrompt").live("submit", function() {
		var add = $(this).find("input[type='submit']");
		add.attr("disabled", "disabled");

		/*
		$.ajax({
			// do some stuff here
		});*/

		return false;
	});
});