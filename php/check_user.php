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


if( $checkResult=== 'this is nonsense'){
	//Add user to database
	$stmt = $mysqli->prepare("INSERT INTO users (UserID) VALUES(?);");
	$stmt->bind_param("s", $userID );
	$stmt->execute();
	$stmt->close();

	//Create an entry for user for each game
	$stmt = $mysqli->prepare("SELECT ID FROM languages; ");
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	$languageArray = array();
	while ($row = $result->fetch_assoc()) {
		$languageArray[] = $row['ID'];
	}


	foreach ($acceptedModes as $mode) {
		foreach ($languageArray as $language) {
			$stmt = $mysqli->prepare("INSERT INTO game".$mode." (userID, language) VALUES(?,?);");
			$stmt->bind_param("si", $userID, $language );
			$stmt->execute();
			$stmt->close();
		}
	}	
}

$jsonData = json_encode($checkResult);
echo $jsonData;

?>