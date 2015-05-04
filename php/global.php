<?php
$acceptedModes = array("1","2","3");
$allUsers = "allusers";

$user = 'root';
$pass = '';
$db = 'kamusi';

$mysqli = new mysqli('localhost', $user, $pass, $db);

function addXToValueInGame($userID, $language, $mode, $value, $x){
	global $mysqli;
	$stmt = $mysqli->prepare("UPDATE game". $mode . " SET ". $value . " = " . $value . " + ? WHERE userid=? and language = ?;");
	$stmt->bind_param("isi", $x, $user, $data["language"]);
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
	$stmt->bind_param("siii", $user, $language, $mode, $x);
	$stmt->execute();
	$stmt->close();	
}

?>
