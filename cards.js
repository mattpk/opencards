if (getExists) {
	// check if deck exists, put deck

	$(".container").append("<span id = 'cardtitle'></span>");
	$(".container").append("<div id='cardwrapper'><div id='card'></div></div>");
	$("#card").append("<span id ='cardtext'></span>");

	$.post("ajaxReq.php", {req: "deck", id: get}).done(function(data) {
		// i expect an array of the id, and then a 2d array of front/back/
		var result = JSON.parse(data);
		var title;
		if (!deck) {
			title = "Oops, deck not found!";
		} else {
			title = result[0];
		}
		$("#cardtitle").text(title);
	});

	//$('#cardtext').text("You can do it. Finish this by tonight!");
} else {
	// get and write names
	$(".container").append("Select a deck to view:");
	$(".container").append("<ul></ul>");
	$.post("ajaxReq.php", {req: "names"}).done(function(data) {
		var names = JSON.parse(data);
		for (var x = 0; x < names.length; x++) {
			$(".container ul").append("<li><a href = './?deck=" + names[x][0] + "'>" + names[x][1] + "</a></li>");
		}
	});
}