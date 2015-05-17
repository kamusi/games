<?php
//include 'global.php';

$userID = $_GET['userID'];
$userName = $_GET['userName'];


$stmt = $mysqli->prepare("SELECT * FROM users WHERE UserID = ? ");
$stmt->bind_param("s", $userID );
$stmt->execute();
$result = $stmt->get_result(); 

$checkResult = $result-> num_rows;
$stmt->close();


if( $checkResult== 0){
	//Add user to database
	$stmt = $mysqli->prepare("INSERT INTO users (UserID, Username) VALUES(?,?);");
	$stmt->bind_param("ss", $userID, $userName );
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
			$stmt = $mysqli->prepare("INSERT INTO games (userID, game, language) VALUES(?,?,?);");
			$stmt->bind_param("sii", $userID, $mode, $language );
			$stmt->execute();
			$stmt->close();
		}
	}	
}

$jsonData = json_encode($checkResult);
echo $jsonData;

?>