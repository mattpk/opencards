if (getExists) {
	// check if deck exists, put deck
	$(".container").append("<div id='cardwrapper'><div id='card'></div></div>");
	$("#card").append("<span id ='cardtext'></span>");
	$('#cardtext').text("You can do it. Finish this by tonight!");
} else {
	// get and write names
	$.post("ajaxReq.php", {req: "names"}).done(function(data) {
		var names = JSON.parse(data);
		for (var x = 0; x < names.length; x++) {
			$(".container").append("<span><a href = './?deck=" + names[x][0] + "'>" + names[x][1] + "</a></span>");
		}
	});
}