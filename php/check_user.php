<?php
session_start();
$userID = $_GET['userID'];
$userName = $_GET['userName'];

	
if (isset($_SESSION['lang'])){
		$checkResult="aleadyDoneBefore";
}
else {


$stmt = $mysqli->prepare("SELECT Language FROM users WHERE UserID = ? ;");
$stmt->bind_param("s", $userID );
$stmt->execute();
$userExists= $stmt->bind_result($checkResult);
$stmt->fetch();
$result = $stmt->get_result(); 

$stmt->close();


if( !$userExists){
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

	$checkResult="-1";	
}
else {
	if (isset($_SESSION['lang'])){
		$checkResult="aleadyDoneBefore";
	} 
	$_SESSION['lang']=$languageMap[$checkResult];

	$checkResult="done";

}
}

$jsonData = json_encode($checkResult);
echo $jsonData;

?>