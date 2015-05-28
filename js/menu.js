var min_length = 4;

var default_value = '✎' + ICanWrite;
var translation_default_value = '✎ ' + ICanTranslate;

var autoUpdateIntervalJobID

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
		soumettre_traduction();	get_ranked_mode_2();
		$scope.hideTooltip2();
		playClick();
		$scope.translation = translation_default_value;	
	}
}



function enter_game1() {
	pause_animation();
	game = 1;
	get_ranked();
	document.getElementById("welcome").style.display = "none";
	document.getElementById("game").style.display = "inline-block";
	document.getElementById("gamezone1").style.display = "inline-block";
	document.getElementById("gamezone2").style.display = "none";
	document.getElementById("gamezone3").style.display = "none";
	document.getElementById("gamezone4").style.display = "none";

	// document.getElementById("gamezone-main1").style.display = "inline-block";
	// document.getElementById("gamezone-main2").style.display = "none";
	// document.getElementById("footer-next1").style.display = "inline-block";
	// document.getElementById("footer-next2").style.display = "none";

}

function enter_game2() {
	pause_animation()
	game = 2;
	get_ranked_mode_2();
	document.getElementById("welcome").style.display = "none";
	document.getElementById("game").style.display = "inline-block";
	document.getElementById("gamezone1").style.display = "none";
	document.getElementById("gamezone2").style.display = "inline-block";
	document.getElementById("gamezone3").style.display = "none";
	document.getElementById("gamezone4").style.display = "none";

	// document.getElementById("gamezone-main1").style.display = "none";
	// document.getElementById("gamezone-main2").style.display = "inline-block";
	// document.getElementById("footer-next1").style.display = "none";
	// document.getElementById("footer-next2").style.display = "inline-block";
	;
}

function enter_game3() {
	pause_animation();

	game = 3;
	getRankedForTweets();
	document.getElementById("welcome").style.display = "none";
	document.getElementById("game").style.display = "inline-block";
	document.getElementById("gamezone1").style.display = "none";
	document.getElementById("gamezone2").style.display = "none";
	document.getElementById("gamezone3").style.display = "inline-block";
	document.getElementById("gamezone4").style.display = "none";



}

function enter_game4() {
	game = 4;
	
	document.getElementById("welcome").style.display = "none";
	document.getElementById("game").style.display = "inline-block";
	document.getElementById("gamezone1").style.display = "none";
	document.getElementById("gamezone2").style.display = "none";
	document.getElementById("gamezone3").style.display = "none";
	document.getElementById("gamezone4").style.display = "inline-block";


	pause_animation();
	getRankedForSwahili();


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
	stopAutoUpdateOfLeaderboard();
	document.getElementById("settings").style.display = "none";
	document.getElementById("leaderboard").style.display = "none";
	document.getElementById("changeMenuLanguage").style.display = "none";	
	document.getElementById("welcome").style.display = "none";
	document.getElementById("game").style.display = "none";

	document.getElementById("profile").style.display = "inline-block";
}

function display_changeLanguage() {
		document.getElementById("welcome").style.display = "none";
		document.getElementById("profile").style.display = "none";
			document.getElementById("settings").style.display = "none";
	document.getElementById("changeMenuLanguage").style.display = "inline-block";	
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
	document.getElementById("changeMenuLanguage").style.display = "none";
//display only the games that are avilable in the currentlanguage
		console.log(implementedGames)
		console.log(gameLanguage)
	for( i = 1; i < 5; i++){
		if(  $.inArray(gameLanguage, implementedGames[i]) == -1) {
			document.getElementById("enter"+i).style.display = "none";
		}
		else {
			document.getElementById("enter"+i).style.display = "inline-block";			
		}
	}

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
	document.getElementById("enter4").classList.remove("shaded_enter");
	document.getElementById("enter4").classList.add("animateenter");

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


function set_consensus(definition) {
	document.getElementById("consensus").innerHTML = generalSense + definition;
}

function set_word(word, pos) {
	document.getElementById("word").innerHTML = word;
	document.getElementById("pos").innerHTML = pos;
}

function set_avatar() {
	document.getElementById("avatar").src = "https://graph.facebook.com/" + userID + "/picture";
	document.getElementById("profile_avatar").src = "https://graph.facebook.com/" + userID + "/picture??width=200&height=200";
}

function set_profile_data(points, pendingPoints, ratio) {
	document.getElementById("profile_name").innerHTML = userName;
	document.getElementById("profile_points").innerHTML = points;
	document.getElementById("pending_points").innerHTML = pendingPoints;
	document.getElementById("profile_attempts").innerHTML = ratio.toPrecision(10);

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
//	if(class_name == "active_definition" && user_translation != translation_default_value) {
		submit_translation(user_translation);
//	}
}

function startAutoUpdateOfLeaderboard() {


	languageSelect = document.getElementById("scoreLanguage");
	scoreLanguage = languageSelect.selectedIndex;
	gameSelect = document.getElementById("scoreGame");
	scoreGame= gameSelect.selectedIndex;
	timePeriodSelect = document.getElementById("scoretimePeriod");
	scoretimePeriod = timePeriodSelect.selectedIndex;
	metricSelect = document.getElementById("scoreMetric")
	scoreMetric = metricSelect.selectedIndex;

	var whichSliderToChange = 0;


	autoUpdateIntervalJobID= setInterval(function () {
		if(document.getElementById("autoloop").checked) {
		var whatTochange = languageSelect;

		switch(whichSliderToChange) {
			case 0:
			whatTochange = languageSelect;
			break;
			case 1:
			whatTochange = gameSelect;
			break;
			case 2:
			whatTochange = timePeriodSelect;
			break;
			case 3:
			whatTochange = metricSelect;
			break;
			default:
			console.log("PEROGVJEöRKFJ")
			break;        
		}
		whatTochange.selectedIndex = (whatTochange.selectedIndex + 1)  % (whatTochange.length) ;
		whichSliderToChange= (whichSliderToChange +1) % 4;
		console.log("INTERBVAAAAAAAAAAL" + whichSliderToChange)
		updateLeaderboard();
	}


	}, 3000);

}

function stopAutoUpdateOfLeaderboard() {
	clearInterval(autoUpdateIntervalJobID)
}



