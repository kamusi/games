<?php

session_start();
function generateToken() {
	// generate token from random value
	$token = md5(rand(pow(2, 32), pow(2, 33)));  

	// Store token in session superglobal
	$_SESSION['token'] = $token; 

	return $token;
}
	
	$locale= isset($_SESSION['lang']) ? $_SESSION['lang'] : $languageMap["1"];


	setlocale(LC_ALL, $locale .'.utf8');

	/**
	 * Because the .po file is named messages.po, the text domain must be named
	 * that as well. The second parameter is the base directory to start
	 * searching in.
	 */
	bindtextdomain('messages', 'locale');

	/**
	 * Tell the application to use this text domain, or messages.mo.
	 */
	textdomain('messages');	
	
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
$gameNames = array('1' => _("Definition Game"), '2' => _("Translation Game") , '3' => _("Tweet Game"), '4'=> _("Sentence Game"));
$gameLanguages= array('0' => _("Undefined Language"), '1' => _("English"), '2' => _("French") , '3' => _("Vietnamese"), '4' => _("Swahili"));
$implementedGames= array('1' => array(1,2), '2' => array(2,3), '3' => array(1), '4' => array(4));
$partOfSpeechArray= array('noun' => _("noun"), 'verb' => _('verb'), 'adjective' => _('adjective'), 'adjective_satellite' => _('adjective_satellite'), 'adverb' => _('adverb'), 'phrase' => _('phrase'));

?>

<body> <!-- onkeypress="secondEnter(event)" -->
	<div id="main" >
		<div id="portal">
			<div id="welcome">
				<canvas width="930" height="550" id="animation">Your browser doesn't support HTML5.</canvas>
				<img id="logo" src="media/logo.png" onmousedown="isNewUser();">
				<img title="Definition Game" id="enter1" class="shaded_enter" src="media/definition2.png" onmousedown="playClick();enter_game1();">
				<img title="Translation Game" id="enter2" class="shaded_enter" src="media/translation.png" onmousedown="playClick();enter_game2();">
				<img title="Tweet Game" id="enter3" class="shaded_enter" src="media/twitterBird.png" onmousedown="playClick();enter_game3();">
				<img title="Sentence Game" id="enter4" class="shaded_enter" src="media/book.png" onmousedown="playClick();enter_game4();">
				<img title="Choose Language" id="enter0" class="shaded_enter" src="media/language_selector.png" onmousedown="playClick(); display_changeLanguage() ">
				<fb:login-button scope="public_profile,email" id="enterLogin" class="shaded_enterLogin" onlogin="checkLoginState();"></fb:login-button>

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
							<h1 id="title1"> <?php printf(_("%s"), $gameNames["1"]);; ?>  </h1>

							<p id="instructions1"> </p>

							<p id="word"></p>
							<p id="pos"></p>
							<p id="consensus"></p>
						</div>
						<div id="definitions_wrapper">
							<div class="input_tool" ng-click="$event.stopPropagation()" ng-show="showtooltip">
								<input id="input_tool_box" type="text" ng-model="value" ng-keypress="searchEnter($event);" onFocus="this.select()"/>
								<img title="Submit" id="SubmitDef" ng-click="submitGame1($event);" class="controlSmall" src="media/rightarrowSmall.png" onclick="">

							</div>							
							<table id="definitions">

								<tr><td>


									<li ng-click="toggleTooltip($event)" id="user_definition" class="inactive_definition">{{value}}</li>
								</td></tr>
							</table>
						</div>

					</div>

					<div id="gamezone-footer1">
										<div id="hunt_wrapper">
						<div id="hunt">
							<a id="wiktionary" target="_blank">Wiktionary</a>
							<a>•</a>
							<a id="dictionary" target="_blank">Dictionary.com</a>
							<a>•</a>
							<a id="wordnik" target="_blank">Wordnik</a>
						</div>
					</div>
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
						<h1 id="title2"> <?php printf(_("%s"),$gameNames["2"]); ?>  </h1>
							<p id="instructions2"></p>
			
							<p id="translation_word"></p>
							<p id="translation_pos"></p>
							<p id="translation_definition"></p>
						</div>
						<div id="translations_wrapper">
							<div class="input_tool" ng-click="$event.stopPropagation()" ng-show="showtooltip2">

								<input id="translation_input_tool_box" type="text" ng-model="translation" ng-keypress="searchEnter2($event);" onFocus="this.select()"/>
								<img title="Submit" id="SubmitTrans" ng-click="submitGame2($event);" class="controlSmall" src="media/rightarrowSmall.png" onclick="">

							</div>
							<table id="translations">
								<tr><td>
								</div>	
								<li ng-click="toggleTooltip2($event)" id="user_translation" class="inactive_definition">{{translation}}</li>
							</td></tr>
						</table>
					</div>
				</div>
			</div>

			<div id="gamezone3" ng-controller="InlineEditorController" ng-click="hideTooltip();">
				<div id="gamezone-main3" >
					<div id="entry">
					<h1 id="title3"> <?php printf(_("%s"),$gameNames["3"]); ?>  </h1>
						<p id="instructions"><?php echo _("Check ONLY the tweets that are excellent examples of THIS meaning: "); ?>   </p>
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
					</div>
					<div id="footer-next3">
						<img title="Next" id="next1" ng-click="clear($event)" class="control" src="media/rightarrow.png" onclick='submitCheckBoxData("tweet"); setTimeout(getRankedForTweets(), 500);'>
					</div>
				</div>
			</div>
			<div id="gamezone4" ng-controller="InlineEditorController" ng-click="hideTooltip();">
				<div id="gamezone-main4" >
					<div id="entry">
						<h1 id="title4"> <?php printf(_("%s"),$gameNames["4"]); ?>  </h1>
						<p id="instructions"><?php echo _("Check the sentences that correspond well to this word: "); ?></p>
						<p id="word4"></p>		
						<p id="pos4"></p>					
						<p id="transEnglish4"></p>
						<p id="defSwahili4"></p>
						<div id="sentences_wrapper">
							<p id="swahiliSentences"></p>
						</div>
					</div>
				</div>

				<div id="footer-next3">
					<img title="Next" id="next1" ng-click="clear($event)" class="control" src="media/rightarrow.png" onclick='submitCheckBoxData("game4"); setTimeout(getRankedForSwahili(), 500);'>
				</div>
			</div>

		</div>
		<div id="about">
			<div id="about-main">
				<div id="about-data">
					<h2><?php echo _("About"); ?></h2>
					<p>
						<?php echo _("The Kamusi Project "); ?>(<a href="http://kamusi.org/" target="_blank">http://kamusi.org/</a>) <?php echo _("is a participatory international effort dedicated to improving knowledge of the world's languages. Our long term mission is to produce dictionaries and other language resources for every language, and to make those resources available for free to everyone."); ?>
					</p>
				</br>
				<h2><?php echo _("How to write a great definition"); ?></h2>
				<p>
					<?php echo _("Definitions are explanations of what a word means. They are not single words (those are synonyms). You can usually use a definition instead of the actual word. Stick to these rules:"); ?>
				</p>
				<ul>
					<li> <?php echo _("Short and sweet: A definition should be as brief as possible to explain the concept, but long enough to describe it fully. If the same word has different meanings, those are different concepts with different definitions- a definition in Kamusi only explains *one* concept."); ?>
					</li>
					<li> <?php echo _("Easy does it: Use the simplest words you can -definitions should not force readers to jump around the dictionary more, unless technical terms are absolutely necessary."); ?>
					</li>
					<li> <?php echo _('No circles: Definitions should NOT contain the word that is being defined, nor its close relatives. "Happiness" is "A feeling of joy.", not "The feeling of being happy." Definitions should not be circular - we cannot now say "joy" is "A feeling of happiness."'); ?>
					</li>
					<li> <?php echo _('No fluff: Do not start with "A term meaning", or "This is a", or "X refers to", etc. Style: Begin with a Capital letter and end with a period.'); ?>
					</li>
				</ul>
			</br>
			<?php echo _("For more information, you can watch this video: "); ?><a href="https://www.youtube.com/watch?v=aaqOQQOYuHA" target="_blank">https://www.youtube.com/watch?v=aaqOQQOYuHA</a>
		</div>
	</div>
	<div id="about-footer">
		<img title="Return" class="control" src="media/leftarrow.png" onclick="playClick();return_to_game(); ">
	</div>
</div>
<div id="profile">
	<div id="profile-main">
		<h1 id= "yourachievements"></h1>
		<div id="profile-avatar-wrapper">
			<img id="profile_avatar" src="" width="200">
		</div>
		<div id="profile-info-wrapper">
			<br>
			<table id="profile_info">
				<tr>
					<td><?php echo _("Name"); ?></td>
					<td id="profile_name"></td>
				</tr>
				<tr>
					<td><?php echo _("Points"); ?></td>
					<td id="profile_points"></td>
				</tr>
				<tr>
					<td><?php echo _("Pending points"); ?></td>
					<td id="pending_points"></td>
				</tr>
				<tr>
					<td><?php echo _("Success Rate"); ?></td>
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
		<img id="settings_button" title="Leaders" class="controlMiddle" src="media/medal.png" onclick="playClick();display_leaderboard();updateLeaderboard(); startAutoUpdateOfLeaderboard();">
		<img title="Settings" class="controlRight" src="media/settings.png" onclick="playClick();display_settings();">

	</div>
</div>
<div id="settings">
	<div id="settings-main">
		<p><?php echo _("Change the Hints and Help Language"); ?></p>
			<img title="ChooseLanguage" id="changeLanguage" src="media/language_selector.png" onmousedown="playClick(); saveSettings();display_changeLanguage();">

</br></br>
	<p><?php echo _("Game Language"); ?></p>
<select id="language" onchange= size="1">
	<option><?php echo _("English"); ?></option>
	<option><?php echo _("French"); ?></option>
	<option><?php echo _("Vietnamese"); ?></option>
	<option><?php echo _("Swahili"); ?></option>
</select>
</br></br>
<p><?php echo _("Notify me about new points I earn..."); ?></p>
<select id="notifications" size="1">
	<option><?php echo _("Real time"); ?></option>
	<option><?php echo _("Once daily"); ?></option>
	<option><?php echo _("Once weekly"); ?></option>
	<option><?php echo _("When pigs fly..."); ?></option>
</select>
</br></br>
<p><?php echo _("Post achievements to my timeline..."); ?></p>

<select id= "posts" size="1">
	<option><?php echo _("Always (Every time I have a winning entry)"); ?></option>
	<option><?php echo _("Often (Max once a day)"); ?></option>
	<option><?php echo _("Sometimes (Max once a week)"); ?></option>
	<option><?php echo _("Occasionally (Max once monthly)"); ?></option>
	<option><?php echo _("When pigs fly... (Never)"); ?></option>
</select>
</div>
</br></br>
	<img title="Submit" class="control" src="media/checksign.jpg" onclick="playClick(); saveSettings(); location.reload();">


</div>
<div id="changeMenuLanguage">
</br></br>
</br></br>
	<img src="media/language_selector.png">
	</br></br>
	</br></br>
<select id="menuLanguage" onchange= size="1">
	<option><?php echo ("English"); ?></option>
	<option><?php echo ("Français"); ?></option>
<!-- The languages are not translated yet
	<option><?php echo ("tiếng Việt"); ?></option>	
	<option><?php echo ("Swahili"); ?></option> -->
</select>
</br></br>
</br></br>
<div id="menuLanguage-footer">
	<img title="Submit" class="control" src="media/checksign.jpg" onclick="playClick(); saveMenuLanguage();">
</div>
</div>
<div id="leaderboard">
	<div id="settings-outer">
		<div class="settings-inner">   
			<?php echo _("Language"); ?>
			<select class="scoreSelectors" id= "scoreLanguage" onchange="updateLeaderboard(); playClick();" size="1">
				<option><?php echo _("All languages"); ?></option>
				<option><?php echo _("English"); ?></option>
				<option><?php echo _("French"); ?></option>
				<option><?php echo _("Vietnamese"); ?></option>
				<option><?php echo _("Swahili"); ?></option>

			</select>
		</div>
		<div class="settings-inner">
			<?php echo _("Game"); ?>
			<select class="scoreSelectors" id= "scoreGame" onchange="updateLeaderboard(); playClick();" size="1">
				<option><?php echo _("All games"); ?></option>
				<option><?php printf(_("%s"),$gameNames["1"]); ?></option>
				<option><?php printf(_("%s"),$gameNames["2"]); ?></option>
				<option><?php printf(_("%s"),$gameNames["3"]); ?></option>
				<option><?php printf(_("%s"),$gameNames["4"]); ?></option>
			</select>
		</div>
		<div class="settings-inner">
			<?php echo _("TimePeriod"); ?>
			<select class="scoreSelectors" id= "scoretimePeriod" onchange="updateLeaderboard(); playClick();" size="1">
				<option><?php echo _("All time"); ?></option>
				<option><?php echo _("Last Month"); ?></option>
				<option><?php echo _("Last Week"); ?></option>
				<option><?php echo _("Last 24 Hours"); ?></option>
			</select>
		</div>
		<div class="settings-inner">
			<?php echo _("What to compare"); ?>
			<select class="scoreSelectors" id= "scoreMetric" onchange="updateLeaderboard(); playClick();" size="1">
				<option><?php echo _("Points Earned"); ?></option>
				<option><?php echo _("# of Submissions"); ?></option>
				<option><?php echo _("Success Rate"); ?></option>

			</select>
		</div>
		<div class="settings-inner">
		<input id = "autoloop" class="scoreSelectors" type="checkbox" name="autoloop" value="autoloop" checked="true"; ><?php echo _("Auto-Loop"); ?><br>
		</div>
	</div>
	<h1><?php echo _("Leaderboard"); ?></h1>

	<div id="leader">
		<table id="score_table">
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
<script>
//All dynamic text is references here
ICanWrite = "<?php echo gettext(" I can write the winning definition for this idea!"); ?>"
ICantSay = "<?php echo gettext(" I can't say - skip this one..."); ?>"
ICanTranslate = "<?php echo gettext(" I can translate this word!"); ?>"
keepTheGeneralSense = "<?php echo gettext(" Keep the General Sense. It's a good definition as is!"); ?>"
generalSense = "<?php echo _("Working definition: "); ?>"
translateTheFollowing= "<?php echo _("Translate the following word to : ")  ?>"
yourAchievements = "<?php echo _("Your achievements for the ")  ?>"
stringin= "<?php echo _(" in ")  ?>"
writeOrVote= "<?php echo _("Write or vote for a definition in "); ?>" 
rankString = "<?php echo _("Rank: "); ?>"

<?php
$js_array = json_encode($gameLanguages);
echo "var gameLanguages = ". $js_array . ";\n";
$js_array = json_encode($implementedGames);
echo "var implementedGames = ". $js_array . ";\n"; 
$js_array = json_encode($gameNames);
echo "var gameNames = ". $js_array . ";\n"; 
$js_array = json_encode($partOfSpeechArray);
echo "var partOfSpeechArray = ". $js_array . ";\n";

?>
 
</script>
<script src="js/server_requests.js"></script>
<script src="js/login.js"></script>
<script src="js/sound.js"></script>
<script src="js/menu.js"></script>	
<script src="js/animation.js"></script>
</body>
</html>
