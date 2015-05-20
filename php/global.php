<?php

$acceptedModes = array("1","2","3","4");
$allUsers = "allusers";

$e1=_("Definition Game");
$e2=_("Translation Game");
$e3= ("Tweet Game");
$e4=_("Sentence Game");
$gameNames = array('1' => $e1, '2' => $e2 , '3' => $e3, '4'=> $e4);

$user = 'root';
$pass = '';
$db = 'kamusi';

$mysqli = new mysqli('localhost', $user, $pass, $db);

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
