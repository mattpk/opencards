// global vars
var cards, flipped, index;

// an int between min and max, exclusive of max.
function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min)) + min;
}

function onNext() {
	console.log("next");
	index = Math.max(index+1, cards.length-1);
	updateCard();
}

function onBack() {
	console.log("back");
	index = Math.min(0, index-1);
	updateCard();
}

function onFlip() {
	console.log("flip");
	flipped[index] = !flipped[index];
	updateCard();
}

function onCard() {
	console.log("card");
	onFlip();
}

function onShuffle() {
	console.log("shuffle");
	for (var i = 0; i < flipped.length; i++) {
		flipped[i] = false;
	}
	var newCards = [];
	while (cards.length > 0) {
		newCards.push(cards[getRandomInt(0,cards.length)]);
	}
	cards = newCards;
	updateCard();
}

function onEdit() {
	console.log("edit");
}

function updateCard() {
	$("#cardtext").text(cards[index][flipped[index]]);
	$("#page").text(index + "/" + cards.length);
}

if (getExists) {
	// check if deck exists, put deck
	// set up cards and buttons
	$(".container").append("<span id = 'cardtitle'></span>");
	$(".container").append("<div id='cardwrapper'><div id='card'></div></div>");
	$("#card").append("<span id='cardtext'></span>");
	$("#card").append("<div id='page'>1/4</div>");

	$(".container").append("<div class='buttonwrapper'></div>");
	$(".buttonwrapper").append("<div class='btn' id='prev'>back</div>");
	$(".buttonwrapper").append("<div class='btn' id='flip'>flip</div>");
	$(".buttonwrapper").append("<div class='btn' id='next'>next</div>");
	$(".container").append("<div class='buttonwrapper' id='buttonwrapper2'></div>");
	$("#buttonwrapper2").append("<div class='btn' id='edit'>edit this card</div>");
	$("#buttonwrapper2").append("<div class='btn' id='shuffle'>shuffle order</div>");


	console.log("Getexists, now post requesting");

	// defining variables for movement
	flipped = [];
	index = 0;

	$.post("ajaxReq.php", {req: "deck", id: get}).done(function(data) {
		// i expect an array of the id, and then a 2d array of front/back/
		var result = JSON.parse(data);
		var title;
		console.log(result);
		if (result === false) {
			title = "Oops, deck not found!";
		} else {
			title = result[0]+""; 
			console.log(typeof title);
			title = title.replace(/,+$/, "");// removes trailing commas (weird glitch)
		}
		console.log("title: " + title);
		$("#cardtitle").text(title);

		cards = result[1];
		for (var i = 0; i < cards.length; i++) {
			flipped.push(false);
		}
		console.log(JSON.stringify(cards));
		$("#cardtext").text(cards[0][0]);
		
		// initialize cards

		// put listeners for all buttons.
		$("#cardwrapper").click(onCard());
		$("#flip").click(onFlip());
		$("#back").click(onBack());
		$("#next").click(onNext());
		$("#edit").click(onEdit());
		$("#shuffle").click(onShuffle());


	});

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