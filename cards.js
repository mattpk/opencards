// global vars
var cards, flipped, index;
var currentFlipped = false;
var editing = false;
var oldtext = "";

// an int between min and max, exclusive of max.
function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min)) + min;
}

function onNext() {
	console.log("next");
	index = Math.min(index+1, cards.length-1);
	updateCard();
}

function onBack() {
	console.log("back");
	index = Math.max(0, index-1);
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
		newCards.push(cards.splice(getRandomInt(0,cards.length), 1)[0]);
	}
	cards = newCards;
	updateCard();
}

function onNew() {
	console.log("new");
	$.post("ajaxReq.php", {req: "new", id: get}).done(function(data){ 
		var card = JSON.parse(data);
		cards.push(card);
		flipped.push(false);
		index = flipped.length -1;
		updateCard();
		onEdit();
	});
}

function onEdit() {
	console.log("edit");

	editing = true;
	oldtext = $('#cardtext').text();
	// make text editable
	$('#cardtext').attr('contenteditable','true');

	// add editable class
	$('#cardtext').addClass('cardedit');

	// change edit button to save
	$('#edit').replaceWith("<div class='btn' id ='save'>save</div>");
	$("#save").mouseup(onSave);

	// shift etner new line, enter key submits
	$("#cardtext").keydown(function(e){
	    // Enter was pressed without shift key
	    if (e.keyCode == 13 && !e.shiftKey)
	    {
	        e.preventDefault();
	        onSave();
	    }
	});

	// make card unclickable
	$('.cardwrapper, .backwrapper').off('mouseup');


	// set focus
	$('#cardtext').selectText();

}

function onSave() {
	var newtext = $('#cardtext').text().substring(0,255); // strips characters too long.
	if (oldtext !== newtext) {
		// save locally
		cards[index][flipped[index]? 1 : 0] = newtext;
		// do ajax req for saving

		var params = {req: "edit", id: get, cardid: cards[index][2], flipped: flipped[index], text: newtext};
		$.post("ajaxReq.php", params).done(function(data){ 
			console.log("yo" + data + "_");
		});
	}

	if (cards[index][0] === '' && cards[index][1] === '') {
		if (index === 0) {
			deleteDeck();
		} else {
			deleteCard();
		}
	}

	updateCard();
}

//deletes card.
function deleteCard() {
	console.log("deleting");

	// delete on db first
	$.post("ajaxReq.php", {req: "delete", id: get, cardid: cards[index][2]}).done(function(data) {
		console.log(data);
	});

	cards.splice(index, 1);
	flipped.splice(index, 1);
	if (index == cards.length) {
		index--;
	}
}

// deletes deck.

function updateCard() {
	// the following is to fix an unfinished save
	// make sure it's save
	if (editing) {
		$('#save').replaceWith("<div class='btn' id ='edit'>edit</div>");
		$("#edit").mouseup(onEdit); // readd listener

		$('#cardtext').replaceWith("<span id='cardtext'></span>");
		$('#cardtext').attr('contenteditable','false');

		$(".cardwrapper, .backwrapper").mouseup(onCard);
		editing = false;
	}


	// flip card if needed
	if (currentFlipped !== flipped[index]) {
		$(".cardwrapper, .backwrapper").toggleClass("cardwrapper backwrapper");
		$(".page, .pageback").toggleClass("page pageback");
		currentFlipped = !currentFlipped;
	}
	console.log("updating with index: " + index + ", flipped: " + (flipped[index]? 1 : 0) + "."); 
	$("#cardtext").text(cards[index][flipped[index]? 1 : 0]);
	$(".page, .pageback").text((index+1) + "/" + cards.length);
}

if (getExists) {
	// check if deck exists, put deck
	// set up cards and buttons
	$(".container").append("<span id = 'cardtitle'></span>");
	$(".container").append("<div class='cardwrapper'><div id='card'></div></div>");
	$("#card").append("<span id='cardtext'></span>");
	$("#card").append("<div class='page'></div>");

	$(".container").append("<div class='buttonwrapper'></div>");
	$(".buttonwrapper").append("<div class='btn' id='back'>back</div>");
	$(".buttonwrapper").append("<div class='btn' id='flip'>flip</div>");
	$(".buttonwrapper").append("<div class='btn' id='next'>next</div>");
	$(".container").append("<div class='buttonwrapper' id='buttonwrapper2'></div>");
	$("#buttonwrapper2").append("<div class='btn' id='edit'>edit</div>");
	$("#buttonwrapper2").append("<div class='btn' id='new'>new</div>");
	$("#buttonwrapper2").append("<div class='btn' id='shuffle'>shuffle</div>");

	// defining variables for movement
	flipped = [];
	index = 0;

	$.post("ajaxReq.php", {req: "deck", id: get}).done(function(data) {
		// i expect an array of the id, and then a 2d array of front/back/id,
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
		// initializing flipped array
		cards = result[1];
		for (var i = 0; i < cards.length; i++) {
			flipped.push(false);
		}
		
		// on ready, update first card and add keyboard listeners
		$(document).ready(function() {
			updateCard();

			$(document).keydown(function(event){ 
			    var key = event.which;
			    if (editing) return; // make sure we aren't editing
			    if (key == 37) { // left arrow
			    	onBack();
			    } else if (key == 39) { // right arrow
			    	onNext();
			    } else if (key == 38 || key == 40 || key == 32) { // up, down arrow and space bar
			    	onFlip();
			    } else if (key == 69) { // e
			    	onEdit();
			    } else if (key == 83) { // s
			    	onShuffle();
			    } else if (key == 78) { // n
			    	onNew();
			    }
			});

		});
		// initialize cards

		// put listeners for all buttons.
		$(".cardwrapper, .backwrapper").mouseup(onCard);
		$("#flip").mouseup(onFlip);
		$("#back").mouseup(onBack);
		$("#next").mouseup(onNext);
		$("#edit").mouseup(onEdit);
		$("#shuffle").mouseup(onShuffle);
		$("#new").mouseup(onNew);

	});

} else { // get doesn't exist ##############################
	// get and write names
	$(".container").append("Select a deck to view:");
	$(".container").append("<ul></ul>");
	$.post("ajaxReq.php", {req: "names"}).done(function(data) {
		var names = JSON.parse(data);
		for (var x = 0; x < names.length; x++) {
			$(".container ul").append("<li><a href = './?deck=" + names[x][0] + "'>" + names[x][1] + "</a></li>");
		}
	});

	// put the new deck button
	$(".container").append("<div class='add'>Add a new deck</div>");
	// make clickable
	$(document).ready(function() {
		$(".add").click(function() {
			$('.add').replaceWith("<div id ='instructblock'><span id='instruct'>Choose a name for your deck: </span>" +
				"<input type='text' maxlength='63' /><input type='button' value='Create' onclick='tryName()'/></div>");
		});
	});
}

function tryName() {
	var name = $("input:text").val().substring(0,63);
	$.post("ajaxReq.php", {req:"taken", name: name}).done(function(data) {
		var taken = JSON.parse(data);
		if (!taken) {
			$.post("ajaxReq.php", {req: "add", name: name}).done(function(data) {
				console.log(data);
				// create new in list.
				$(".container ul").append("<li><a href = './?deck=" + JSON.parse(data) + "'>" + name + "</a></li>");
				$('#instructblock').replaceWith("<div>Deck created successfully!</div>");
			});
		} else {
			$('#instruct').text("Name taken. Choose another name:");
		}
	});
}

//selects text
jQuery.fn.selectText = function() {
  var range, selection;
  return this.each(function() {
    if (document.body.createTextRange) {
      range = document.body.createTextRange();
      range.moveToElementText(this);
      range.select();
    } else if (window.getSelection) {
      selection = window.getSelection();
      range = document.createRange();
      range.selectNodeContents(this);
      selection.removeAllRanges();
      selection.addRange(range);
    }
  });
};