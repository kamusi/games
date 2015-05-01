var min_length = 4;

var default_value = '✎ I can write the winning definition for this idea!';
var translation_default_value = '✎ Trong tiếng Việt từ này có nghĩa là ...';

function InlineEditorController($scope){
	$scope.showtooltip = false;
	$scope.value = default_value;

	$scope.hideTooltip = function(){
		$scope.showtooltip = false;
		if ($scope.value == '') {
			$scope.value = default_value;
			document.getElementById('user_definition').className = "inactive_definition";
		}
	}

	$scope.toggleTooltip = function(e){
		e.stopPropagation();
		document.getElementById("input_tool_box").focus();
		document.getElementById("input_tool_box").select();
		document.getElementById("input_tool_box").focus();
		document.getElementById("input_tool_box").select();

		$scope.showtooltip = !$scope.showtooltip;
		
		if($scope.showtooltip) {
			remove_active();
			document.getElementById("user_definition").className = "active_definition";
			if($scope.value == default_value) {
				$scope.value = '';
			}

			document.getElementById("input_tool_box").focus();
			document.getElementById("input_tool_box").select();
		}
		else {
			if ($scope.value == '') {
				$scope.value = default_value;
				document.getElementById("user_definition").className = "inactive_definition";
			}
		}
	}

	$scope.clear = function(e) {
		e.stopPropagation();
		$scope.value = default_value;
	}

	$scope.searchEnter = function(e) {
		if (e.keyCode == 13) {
			$scope.submitGame1();
		}
	}

	$scope.submitGame1 = function() {
			$scope.hideTooltip();
			playClick();vote();get_ranked();
			$scope.value = default_value;	
	}
}

function focusToolbox() {
	document.getElementById("input_tool_box").focus();
	document.getElementById("input_tool_box").select();
}

//THIS IS REALLY UGLY - TO BE REWORKED
function InlineEditorController2($scope){

	console.log("in this thing")

	$scope.showtooltip2 = false;
	$scope.translation = translation_default_value;

	$scope.hideTooltip2 = function(){
		$scope.showtooltip2 = false;
		if ($scope.translation == '') {
			$scope.translation = translation_default_value;
			document.getElementById('user_translation').className = "inactive_definition";
		}
	}

	$scope.toggleTooltip2 = function(e){
		e.stopPropagation();

		$scope.showtooltip2 = !$scope.showtooltip2;
		
		if($scope.showtooltip2) {
			remove_active_translations();
			document.getElementById("user_translation").className = "active_definition";
			if($scope.translation == translation_default_value) {
				$scope.translation = '';
			}
   //      	document.getElementById("translation_input_tool_box").focus();
			// document.getElementById("translation_input_tool_box").select();
		}
		else {
			if ($scope.translation == '') {
				$scope.translation = translation_default_value;
				document.getElementById("user_translation").className = "inactive_definition";
			}
		}
	}

	$scope.clear2 = function(e) {
		e.stopPropagation();
		$scope.translation = translation_default_value;
	}

	$scope.searchEnter2 = function(e) {
		if (e.keyCode == 13) {
			$scope.submitGame2();
		}
	}

	$scope.submitGame2 = function() {
			get_ranked_mode_2();
			$scope.hideTooltip2();
			playClick();
			$scope.translation = translation_default_value;	
	}
}

// function secondEnter(e) {
// 	if (e.keyCode == 13) {
// 		var gamezone1 = document.getElementById("gamezone1");
// 		var gamezone2 = document.getElementById("gamezone2");
// 		var definition = document.getElementById("user_translation").className;
// 		var translation = document.getElementById("user_translation").className;

// 		if(gamezone1.style.display == "inline-block" && definition == "active_definition") {

// 		}
// 		else if(gamezone1.style.display == "inline-block" && translation == "active_definition") {

// 		}
// 	}
// }

function initialise(userID) {
	
	set_avatar(userID);

	console.log("INITLAIZINGGGGGGG")

	get_user_stats();
	get_user_trophies();
	add_translation_dunno('? Tôi không biết...');

}

function enter_game1() {
	game = 1;
	get_ranked();
	document.getElementById("welcome").style.display = "none";
	document.getElementById("game").style.display = "inline-block";
	document.getElementById("gamezone1").style.display = "inline-block";
	document.getElementById("gamezone2").style.display = "none";
	document.getElementById("gamezone3").style.display = "none";
	// document.getElementById("gamezone-main1").style.display = "inline-block";
	// document.getElementById("gamezone-main2").style.display = "none";
	// document.getElementById("footer-next1").style.display = "inline-block";
	// document.getElementById("footer-next2").style.display = "none";
	pause_animation();
}

function enter_game2() {
	game = 2;
	get_ranked_mode_2();
	document.getElementById("welcome").style.display = "none";
	document.getElementById("game").style.display = "inline-block";
	document.getElementById("gamezone1").style.display = "none";
	document.getElementById("gamezone2").style.display = "inline-block";
	document.getElementById("gamezone3").style.display = "none";
	// document.getElementById("gamezone-main1").style.display = "none";
	// document.getElementById("gamezone-main2").style.display = "inline-block";
	// document.getElementById("footer-next1").style.display = "none";
	// document.getElementById("footer-next2").style.display = "inline-block";
	pause_animation();
}

function enter_game3() {
	game = 3;
	get_randomForTweets();
	document.getElementById("welcome").style.display = "none";
	document.getElementById("game").style.display = "inline-block";
	document.getElementById("gamezone1").style.display = "none";
	document.getElementById("gamezone2").style.display = "none";
	document.getElementById("gamezone3").style.display = "inline-block";
	// document.getElementById("gamezone-main1").style.display = "none";
	// document.getElementById("gamezone-main2").style.display = "inline-block";
	// document.getElementById("footer-next1").style.display = "none";
	// document.getElementById("footer-next2").style.display = "inline-block";
	pause_animation();


}

function display_settings() {
	document.getElementById("profile").style.display = "none";
	document.getElementById("settings").style.display = "inline-block";
}

function display_leaderboard() {
	document.getElementById("profile").style.display = "none";
	document.getElementById("leaderboard").style.display = "inline-block";

	
}

function display_about() {
	document.getElementById("game").style.display = "none";
	document.getElementById("about").style.display = "inline-block";
}

function display_profile() {
	getGameScore();
	document.getElementById("settings").style.display = "none";
	document.getElementById("leaderboard").style.display = "none";
	document.getElementById("welcome").style.display = "none";
	document.getElementById("game").style.display = "none";

	document.getElementById("profile").style.display = "inline-block";
}

function return_to_game() {
	document.getElementById("about").style.display = "none";
	document.getElementById("profile").style.display = "none";
	document.getElementById("game").style.display = "inline-block";
}

function display_welcome() {
	document.getElementById("game").style.display = "none";
	document.getElementById("welcome").style.display = "inline-block";
	document.getElementById("leaderboard").style.display = "none";
	continue_animation();
}

function animate_logo() {

	document.getElementById("logo").classList.add("animatelogo");
	document.getElementById("enter1").classList.remove("shaded_enter");
	document.getElementById("enter1").classList.add("animateenter");
	document.getElementById("enter2").classList.remove("shaded_enter");
	document.getElementById("enter2").classList.add("animateenter");
	document.getElementById("enter3").classList.remove("shaded_enter");
	document.getElementById("enter3").classList.add("animateenter");

}

function changeColorOnClick(tweetDisplay,newInput){
    if(newInput.checked){
     tweetDisplay.style.color = "blue";
    }
    else {
    tweetDisplay.style.color = "#af0800";

    }
}

function animate_logo_firstTime(){
	

	document.getElementById("logo").classList.add("animatelogo");
	document.getElementById("enter0").classList.remove("shaded_enter");
	document.getElementById("enter0").classList.add("animateenter");
}

function animate_logo_login(){
	document.getElementById("logo").classList.add("animatelogo");
	document.getElementById("enterLogin").classList.remove("shaded_enterLogin");
	document.getElementById("enterLogin").classList.add("animateenterLogin");
}


// function set_consensus_word(word, pos, definition) {
// 	document.getElementById("word").innerHTML = word;
// 	document.getElementById("pos").innerHTML = pos;
// 	document.getElementById("consensus").style.display = "inline-block";
// 	document.getElementById("consensus").innerHTML = " : " + definition;
// }

function set_consensus(definition) {
	document.getElementById("consensus").innerHTML = "General Sense: " + definition;
}

function set_word(word, pos) {
	document.getElementById("word").innerHTML = word;
	document.getElementById("pos").innerHTML = pos;
}

function set_avatar(userID) {
	document.getElementById("avatar").src = "https://graph.facebook.com/" + userID + "/picture";
	document.getElementById("profile_avatar").src = "https://graph.facebook.com/" + userID + "/picture??width=200&height=200";
}

function set_greeting(userName) {
	document.getElementById("greeting").innerHTML = "Write or vote for a definition, " + userName.split(" ")[0] + "!";
	document.getElementById("greeting2").innerHTML = "Chào " + userName.split(" ")[0] + "!";
	document.getElementById("profile_name").innerHTML = userName;
}

function set_profile_data(points, pendingPoints, ratio) {
	document.getElementById("profile_name").innerHTML = userName;
	document.getElementById("profile_points").innerHTML = points;
	document.getElementById("pending_points").innerHTML = pendingPoints;
	document.getElementById("profile_attempts").innerHTML = ratio.toPrecision(10);
	
/*
	if(notify == 1) {
		document.getElementById("gamezone2").style.display = "none";
		display_profile();
		complete_notification();
	}
	*/
}

function remove_active_translations() {
	var ul = document.getElementById("translations");
	var li =  ul.getElementsByTagName("li");

	for(var i = 0; i < li.length; i++) {
		li[i].className = "inactive_definition";
	}
}

function remove_active() {
	var ul = document.getElementById("definitions");
	var li =  ul.getElementsByTagName("li");

	for(var i = 0; i < li.length; i++) {
		li[i].className = "inactive_definition";
	}
}

function clear_definitions() {
	var ul = document.getElementById("definitions");
	var li =  ul.getElementsByTagName("li");

	for(var i = li.length - 1; i > 0; i = i - 1) {
		ul.removeChild(li[i]);
	}
}

function add_translation_dunno(definition) {
	var ul = document.getElementById('translations');
	var li = document.createElement("li");
	li.setAttribute("id", "translation_dunno");
	li.classList.add("inactive_definition");
	li.innerHTML = definition;

	li.onmousedown = (function() {
		return function () {
			document.getElementById('user_translation').className = 'inactive_definition';
			this.className = "active_definition";
			playClick();
			get_ranked_mode_2();
		};
	})();

	ul.appendChild(li);
}



function add_definition(id, definition, spam) {

	var ul = document.getElementById('definitions');
	var li = document.createElement("li");
	li.classList.add("inactive_definition");
	var div_footer = document.createElement("div");
	div_footer.classList.add("button_div_footer");
	li.innerHTML = definition;
	var img1 = document.createElement("img");
	img1.src = 'media/exclamation.png';
	img1.classList.add('vote_button');
	img1.id = 'vote_button';

	img1.title = "Report spam";
	div_footer.appendChild(img1);

	li.onmousedown = (function(id_num) {
		return function () {

			remove_active();
			this.className = "active_definition";
			definitionID = id_num;
			playClick();
			vote();
			get_ranked();
		};
	})(id);

	img1.onmousedown = (function(id_num) {
		return function () {
			definitionID = id_num;
			report_spam(definitionID);
		};
	})(id);

	 //li.appendChild(div_main);
	 //li.appendChild(div_footer);
	 if(spam){
	 	li.appendChild(img1);
	 }

	 ul.appendChild(li);
	}

	function add_trophy(word, definition) {
		var table = document.getElementById("profile_trophies");
		var row = table.insertRow(0);
		var cell1 = row.insertCell(0);
		var cell2 = row.insertCell(1);
		var cell3 = row.insertCell(2);
	// cell1.classList.add("left_cell");
	// cell2.classList.add("right_cell");
	var img = document.createElement("img");
	img.classList.add('trophy');
	img.src = 'media/medal.png';
	cell1.appendChild(img);
	cell2.innerHTML = word;
	cell3.innerHTML = definition;
	// img1.classList.add('vote_button');
	// var img2 = document.createElement("img");
	// img2.src = 'media/down.png';
	// img2.classList.add('vote_button');
	// //NOTE THIS PROBABLY CAUSES A MEMORY LEAK - TO BE REVIEWED
	// img1.onclick = (function(definition_id) { return function() { vote(definition_id, 1); playClick(); }; })(id);
	// img2.onclick = (function(definition_id) { return function() { vote(definition_id, -1); playClick(); }; })(id);
	// cell2.appendChild(img1);
	// cell2.appendChild(img2);
}

function vote() {
	var user_definition = document.getElementById("input_tool_box").value;
	if(definitionID != -1) {
		console.log("Submitting Vote for definitionID : " + definitionID)
		submit_vote(definitionID, 1);
	}
	else if(user_definition != default_value && user_definition.length >= min_length) {
		console.log("Submitting new definition : " + user_definition)
		submit_definition(user_definition);
	}
}

function soumettre_traduction() {
	var user_translation = document.getElementById("translation_input_tool_box").value;
	var class_name = document.getElementById("user_translation").className;
	if(class_name == "active_definition" && user_translation != translation_default_value) {
		submit_translation(user_translation);
	}
}



