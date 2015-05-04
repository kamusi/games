<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

//sinclude 'global.php';

$user = 'root';
$pass = '';
$db = 'kamusi';


$userID = $_GET['userID'];
$language = $_GET['language'];
$mode = $_GET['mode'];

$returnText = "nothing";

$mysqli = new mysqli('localhost', $user, $pass, $db);

if (!$mysqli->set_charset('utf8')) {
	printf("Error loading character set utf8: %s\n", $mysqli->error);
}

if(!in_array($mode, $acceptedModes)) {
	die("Got a strange mode as input!". $mode);
}
$sql = "SELECT * FROM game" . $mode . " WHERE userid=? AND language = ?;";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("si", $userID, $language );
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$stmt->close();

echo json_encode($row);
     
?>
