<?php

$languageID = 3; //Vietnamese by default
$wordID = $_GET['wordID'];
$userID = $_GET['userID'];
$translation = $_GET['translation'];
$mode = $_GET['mode'];
$language = $_GET['language'];

$user = 'root';
$pass = '';
$db = 'kamusi';

$con = mysqli_connect('localhost', $user, $pass, $db);

if (!$con) {
	die('Could not connect: ' . mysqli_error($con));
}

//increase the number of submissions for this user
$stmt = $mysqli->prepare("UPDATE game". $data["mode"] . " SET submissions = submissions + 1 WHERE userid=? and language = ?;");
$stmt->bind_param("si", $userID, $data["language"]);
$stmt->execute();
$stmt->close();

$sql = 	"INSERT INTO translations " .
		"(LanguageID, WordID, UserID, Translation) VALUES " .
		"(" . $languageID . "," . $wordID . ",'" . $userID . "','" . $translation . "');";

$query = mysqli_query($con, $sql);

echo 'Success';

?>