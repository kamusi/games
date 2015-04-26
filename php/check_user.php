<?php
include 'global.php';

$userID = $_GET['userID'];

$user = 'root';
$pass = '';
$db = 'kamusi';

$mysqli = new mysqli('localhost', $user, $pass, $db);


	$stmt = $mysqli->prepare("SELECT * FROM users WHERE UserID = ? ");
	$stmt->bind_param("s", $userID );
	$stmt->execute();
	$result = $stmt->get_result();

$checkResult = $result-> num_rows;
	$stmt->close();


if( $checkResult=== 1){
	//Add user to database
/*	$stmt = $mysqli->prepare("INSERT INTO users (UserID) VALUES(?);");
	$stmt->bind_param("s", $userID );
	$stmt->execute();
	$stmt->close();
*/
	//Create an entry for user for each game

	foreach ($acceptedModes as $mode) {
	$stmt = $mysqli->prepare("INSERT INTO game".$mode." (UserID) VALUES(?);");
	$stmt->bind_param("s", $userID );
	$stmt->execute();
	$stmt->close();

	}	
}

$jsonData = json_encode($checkResult);
echo $jsonData;

?>