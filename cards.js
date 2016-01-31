if (getExists) {
	$(".container").append("<div id='cardwrapper'><div id='card'></div></div>");
	$("#card").append("<div id ='cardtext'></div>");
	$('#cardtext').text("You can do it. Finish this by tonight!");
} else {
	alert("ok");
	// get and write names
	$.post("ajaxReq.php", {req: "names"}).done(function(data) {
		var names = JSON.parse(data);
		for (var x = 0; x < names.length; x++) {
			$(".container").append("<p>" + (x+1) + ": " + names[x] + "</p>");
		}
	});
}