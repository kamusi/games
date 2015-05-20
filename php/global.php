<?php

$acceptedModes = array("1","2","3","4");
$allUsers = "allusers";

$languageMap = array ("1" => "en_US", "2" => "fr_FR", "3" => "vi_VI" );

$user = 'root';
$pass = '';
$db = 'kamusi';

$mysqli = new mysqli('localhost', $user, $pass, $db);

function setOurLocale($newLocale) {

		$returnVal = setlocale(LC_ALL, $newLocale .'.utf8');

	//echo "Returnval was : ". $returnVal . "END";

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
}


function addXToValueInGame($userID, $language, $mode, $value, $x){
	global $mysqli;
	$stmt = $mysqli->prepare("UPDATE games SET ". $value . " = " . $value . " + ? WHERE userid=? and language = ? AND game=?;");
	$stmt->bind_param("isii", $x, $userID, $language, $mode);
	$stmt->execute();
	$stmt->close();	
}

function addXToPointsInGame($userID, $language, $mode, $x) {
	global $mysqli;

	addXToValueInGame($userID, $language, $mode, "points", $x);
	addXToValueInGame($userID, $language, $mode, "pointsmonth", $x);
	addXToValueInGame($userID, $language, $mode, "pointsweek", $x);
	
	$sql = "INSERT INTO pointtime (userID, language, game, amount, ts) VALUES ";
	$sql .= "(?,?,?,?, UTC_TIMESTAMP());";

	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param("siii", $userID, $language, $mode, $x);
	$stmt->execute();
	$stmt->close();	
}

function addXSubmissionsInGame($userID, $language, $mode, $x){
	global $mysqli;
	addXToValueInGame($userID, $language, $mode, "submissions", $x);
	addXToValueInGame($userID, $language, $mode, "submissionsweek", $x);
	addXToValueInGame($userID, $language, $mode, "submissionsmonth", $x);
	$sql = "INSERT INTO submissiontime (userID, language, game, amount, ts) VALUES ";
	$sql .= "(?,?,?,?, UTC_TIMESTAMP());";

	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param("siii", $userID, $language, $mode, $x);
	$stmt->execute();
	$stmt->close();	

}

?>
