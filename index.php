<?php

session_start();
function generateToken() {
	// generate token from random value
	$token = md5(rand(pow(2, 32), pow(2, 33)));  

	// Store token in session superglobal
	$_SESSION['token'] = $token; 

	return $token;
}

?>

<!DOCTYPE>
<html>
<head>
	<title>Kamusi GAME</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="css/style.css"/>
	<link rel="stylesheet" type="text/css" media="only screen and (max-device-width: 480px)" href="css/mstyle.css"/>
	<!-- <link rel="stylesheet" type="text/css" media="only screen and (-moz-min-device-pixel-ratio: 2), only screen and (-o-min-device-pixel-ratio: 2/1), only screen and (-webkit-min-device-pixel-ratio: 2), only screen and (min-device-pixel-ratio: 2)" href="styles/mstyle.css"/> -->
</head>

<?php
$newToken = generateToken();
?>

<body> <!-- onkeypress="secondEnter(event)" -->
	<div id="main" >
		<div id="portal">
			<div id="welcome">
				<canvas width="930" height="550" id="animation">Your browser doesn't support HTML5.</canvas>
				<img id="logo" src="media/logo.png" onmousedown="isNewUser();">
				<img title="Better English" id="enter1" class="shaded_enter" src="media/uk.png" onmousedown="playClick();enter_game1();">
				<img title="Từ này là" id="enter2" class="shaded_enter" src="media/vn.png" onmousedown="playClick();enter_game2();">
				<img title="TweetGame" id="enter3" class="shaded_enter" src="media/xbox.png" onmousedown="playClick();enter_game3();">
				<img title="ChooseLanguage" id="enter0" class="shaded_enter" src="media/language_selector.png" onmousedown="playClick();enter_game1();display_profile();display_settings();">
				<fb:login-button scope="public_profile,email" id="enterLogin" class="shaded_enterLogin" onlogin="checkLoginStateAfterFirstLogin();"></fb:login-button>

			</div>
			<div id="game" ng-app>
				<div id="controls">
					<div id="controlheader">
						<img id="controltitle" src="media/banner.png">
					</div>
					<img title="Profile" id="user" class="control" src="media/user.png" onclick="playClick();display_profile();">
					<img title="Invite" id="shield" class="control" src="media/invite.png" onclick="playClick();request();">
					<img title="Share" id="gossip" class="control" src="media/balloon.png" onclick="playClick();share();">
					<img title="Info" id="information" class="control" src="media/info.png" onclick="playClick();display_about();">
					<img title="Home" id="auction" class="control" src="media/home.png" onclick="playClick();display_welcome();">
				</div>
				<div id="gamezone1" ng-controller="InlineEditorController" ng-click="hideTooltip();">
					<div id="gamezone-main1" >
						<div id="entry">
							<p id="instructions1">Write or vote for a definition!  </p>

							<p id="word"></p>
							<p id="pos"></p>
							<p id="consensus"></p>
						</div>
						<div id="definitions_wrapper">
							<div class="input_tool" ng-click="$event.stopPropagation()" ng-show="showtooltip">
								<input id="input_tool_box" type="text" ng-model="value" ng-keypress="searchEnter($event);" onFocus="this.select()"/>
								<img title="Submit" id="SubmitDef" ng-click="submit($event);" class="controlSmall" src="media/rightarrowSmall.png" onclick="">

							</div>							
							<table id="definitions">

								<tr><td>


									<li ng-click="toggleTooltip($event)" id="user_definition" class="inactive_definition">{{value}}</li>
								</td></tr>
							</table>
						</div>
					</div>
					<div id="gamezone-footer1">
						<div id="footer-greeting">
							<a class="tooltip">
								<p id="greeting"></p>
								<span><img id="avatar" src="" width="50"></span>
							</a>
							<span id="login_button">
								<fb:login-button scope="public_profile,email" onlogin="checkLoginState();"></fb:login-button>
							</span>
						</div>
					</div>
				</div>
				<div id="gamezone2" ng-controller="InlineEditorController2" ng-click="hideTooltip2();">
					<div id="gamezone-main2">
						<div id="translation_entry">
							<p id="translation_word"></p>
							<p id="translation_pos"></p>
							<p id="translation_definition"></p>
						</div>
						<div id="translations_wrapper">
							<div class="input_tool" ng-click="$event.stopPropagation()" ng-show="showtooltip2">

								<input id="translation_input_tool_box" type="text" ng-model="translation" ng-keypress="searchEnter2($event);" onFocus="this.select()"/>
								<img title="Submit" id="SubmitTrans" ng-click="clear($event)" class="controlSmall" src="media/rightarrowSmall.png" onclick="playClick();;soumettre_traduction();get_ranked_mode_2();">

							</div>
							<table id="translations">
								<tr><td>
								</div>	
								<li ng-click="toggleTooltip2($event)" id="user_translation" class="inactive_definition">{{translation}}</li>
							</td></tr>
						</table>
					</div>
					<div id="hunt_wrapper">
						<div id="hunt">
							<a id="wiktionary" target="_blank">Wiktionary</a>
							<a>•</a>
							<a id="dictionary" target="_blank">Dictionary.com</a>
							<a>•</a>
							<a id="wordnik" target="_blank">Wordnik</a>
						</div>
					</div>
				</div>
			</div>

			<div id="gamezone3" ng-controller="InlineEditorController" ng-click="hideTooltip();">
				<div id="gamezone-main3" >
					<div id="entry">
						<p id="instructions">Check ONLY the tweets that are excellent examples of THIS meaning:  </p>
						<p id="word3"></p>		
						<p id="pos3"></p>					
						<p id="def3"></p>
						<div id="definitions_wrapper">
							<p id="twitterWords"></p>
						</div>
					</div>
				</div>
				<div id="gamezone-footer3">
					<div id="footer-greeting">
						<a class="tooltip">
							<p id="greeting"></p>
							<span><img id="avatar" src="" width="50"></span>
						</a>
						<span id="login_button">
							<fb:login-button scope="public_profile,email" onlogin="checkLoginState();"></fb:login-button>
						</span>

					</div>
					<div id="footer-next3">
						<img title="Next" id="next1" ng-click="clear($event)" class="control" src="media/rightarrow.png" onclick="submitTweets(); setTimeout(get_randomForTweets, 500);">
					</div>
				</div>
			</div>
		</div>
		<div id="about">
			<div id="about-main">
				<div id="about-data">
					<h2>About</h2>
					<p>
						The Kamusi Project (<a href="http://kamusi.org/" target="_blank">http://kamusi.org/</a>) is a participatory international effort dedicated to improving knowledge of the world's languages. Our long term mission is to produce dictionaries and other language resources for every language, and to make those resources available for free to everyone.
					</p>
				</br>
				<h2>How to write a great definition</h2>
				<p>
					Definitions are explanations of what a word means. They are not single words (those are synonyms). You can usually use a definition instead of the actual word. Stick to these rules:
				</p>
				<ul>
					<li> Short and sweet: A definition should be as brief as possible to explain the concept, but long enough to describe it fully. If the same word has different meanings, those are different concepts with different definitions- a definition in Kamusi only explains *one* concept.
					</li>
					<li> Easy does it: Use the simplest words you can -definitions should not force readers to jump around the dictionary more, unless technical terms are absolutely necessary.
					</li>
					<li> No circles: Definitions should NOT contain the word that is being defined, nor its close relatives. "Happiness" is "A feeling of joy.", not "The feeling of being happy." Definitions should not be circular - we cannot now say "joy" is "A feeling of happiness."
					</li>
					<li> No fluff: Do not start with "A term meaning", or "This is a", or "X refers to", etc. Style: Begin with a Capital letter and end with a period.
					</li>
				</ul>
			</br>
			For more information, you can watch this video: <a href="https://www.youtube.com/watch?v=aaqOQQOYuHA" target="_blank">https://www.youtube.com/watch?v=aaqOQQOYuHA</a>
		</div>
	</div>
	<div id="about-footer">
		<img title="Return" class="control" src="media/leftarrow.png" onclick="playClick();return_to_game(); ">
	</div>
</div>
<div id="profile">
	<div id="profile-main">
		<div id="profile-avatar-wrapper">
			<img id="profile_avatar" src="" width="200">
		</div>
		<div id="profile-info-wrapper">
			<table id="profile_info">
				<tr>
					<td>Name</td>
					<td id="profile_name"></td>
				</tr>
				<tr>
					<td>Points</td>
					<td id="profile_points"></td>
				</tr>
				<tr>
					<td>Pending points</td>
					<td id="pending_points"></td>
				</tr>
				<tr>
					<td>Success Rate</td>
					<td id="profile_attempts"></td>
				</tr>
			</table>
		</div>
		<div id="profile_trophies_wrapper">
			<table id="profile_trophies"></table>
		</div>
	</div>
	<div id="profile-footer">
		<img title="Return" class="controlLeft" src="media/leftarrow.png" onclick="playClick();return_to_game();">
		<img id="settings_button" title="Leaders" class="controlMiddle" src="media/medal.png" onclick="playClick();display_leaderboard();">
		<img title="Settings" class="controlRight" src="media/settings.png" onclick="playClick();display_settings();">

	</div>
</div>
<div id="settings">
	<div id="settings-main">
		<p>My language is...</p>
	</br>
	<select id="language" onchange= size="1">
		<option>English</option>
		<option>Français</option>
		<option>tiếng Việt</option>
	</select>
</br></br>
<p>Notify me about new points I earn...</p>
</br>
<select id="notifications" size="1">
	<option>Real time</option>
	<option>Once daily</option>
	<option>Once weekly</option>
	<option>When pigs fly...</option>
</select>
</br></br>
<p>Post achievements to my timeline...</p>

<select id= "posts" size="1">
	<option>Always (Every time I have a winning entry)</option>
	<option>Often (Max once a day)</option>
	<option>Sometimes (Max once a week)</option>
	<option>Occasionally (Max once monthly)</option>
	<option>When pigs fly... (Never)</option>
</select>
</div>
<div id="settings-footer">
	<img title="Return" class="control" src="media/leftarrow.png" onclick="playClick(); saveSettings(); display_profile();">
</div>
</div>
<div id="leaderboard">
	<p>Language</p>
	<select id= "scoreLanguage" onchange="updateLeaderboard(); playClick();" size="1">
		<option>All languages</option>
		<option>English</option>
		<option>Vietnamese</option>
	</select>
</br></br>

<p>Game</p>
<select id= "scoreGame" size="1">
	<option>All games</option>
	<option>Game1</option>
	<option>Game2</option>
	<option>Game3</option>
</select>
</br></br>

<p>TimePeriod</p>
<select id= "scoretimePeriod" size="1">
	<option>All time</option>
	<option>Last Month</option>
	<option>Last Week</option>
	<option>Last 24 Hours</option>
</select>
</br></br>

<p>What to compare</p>
<select id= "scoreMetric" size="1">
	<option>Points Earned</option>
	<option>Number of Submissions</option>
	<option>Success Rate</option>

</select>
</br></br>
<p>Leader</p>

<div id="leader">
	<table id="score_table">
		<tr>
			<td>
			</td>
			<td>UserName</td>
			<td>UserScore</td>
			<td>UserRank</td>
		</tr>
		<tr>
			<td>
				<img id="leaderPic1">
			</td>
			<td id= "leaderName"></td>
			<td id= "leaderScore"> </td>
			<td id = "leaderRank"></td
			<td id="profile_attempts"></td>
		</tr>
	</table>

<p>Me</p>

<div id="leader">
	<table id="score_table">
		<tr>
			<td>
			</td>
			<td>UserName</td>
			<td>UserScore</td>
			<td>UserRank</td>
		</tr>
		<tr>
			<td>
							<img id="leaderPic1" src="http://graph.facebook.com/1629333623960388/picture" onmousedown="isNewUser();">
			</td>
			<td>UserName</td>
			<td>UserScore</td>
			<td>UserRank</td
			<td id="profile_attempts"></td>
		</tr>
	</table>


</div>
<div id="leaderboard-footer">
	<img title="Return" class="control" src="media/leftarrow.png" onclick="playClick(); display_profile();">
</div>
</div>
</div>
</div>
</div>

<script type="text/javascript"> var token = "<?php echo $newToken; ?>"; </script>
<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.2.15/angular.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="js/server_requests.js"></script>
<script src="js/login.js"></script>
<script src="js/sound.js"></script>
<script src="js/menu.js"></script>	
<script src="js/animation.js"></script>
</body>
</html>
