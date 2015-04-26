<?php

include 'validate_token.php';
$offsetModulo = 2;

$userID = $_GET['userID'];
$mode = $_GET['mode'];


// USING ROOT IS A SECURITY CONCERN
$user = 'root';
$pass = '';
$db = 'kamusi';

$con = mysqli_connect('localhost', $user, $pass, $db);

if (!$con) {
	die('Could not connect: ' . mysqli_error($con));
}

$mysqli = new mysqli('localhost', $user, $pass, $db);


$results_array = FALSE;

while($results_array === FALSE) {
	$word_id =lookForWord($userID, $mysqli); 
	$results_array = getDefinitions($word_id, $mysqli);
}

$jsonData = json_encode($results_array);
echo $jsonData;

function lookForWord($userID, $mysqli) {
	global $offsetModulo, $mode;
//fetch the user in order to see which word is for him
	$stmt = $mysqli->prepare("SELECT * FROM users WHERE UserID = ? ");
	$stmt->bind_param("s", $userID );
	$stmt->execute();
	$result = $stmt->get_result();

	$stmt->close();

	$row = $result->fetch_assoc();
	$user_position = $row["PositionMode".$mode];
	$user_offset = $row["OffsetMode".$mode];

//fetch the word that has as rank user s position+offset
	$sql =  "SELECT ID As ID, DefinitionID As DefinitionID, Rank As Rank FROM (";
		$sql.=	"SELECT w.ID, w.DefinitionID, r.Rank FROM rankedwords As r LEFT JOIN words As w ON r.Word = w.Word";
		$sql.=	") As sq WHERE sq.ID IS NOT NULL AND sq.DefinitionID IS NOT NULL AND sq.ID NOT IN (SELECT WordID FROM wordsAlreadySeenMode".$mode." WHERE UserID=?) AND sq.Rank = ? LIMIT 1;";

$sum = intval($user_position) + intval($user_offset);

$stmt = $mysqli->prepare($sql);
if ($stmt === FALSE) {
	die ("Mysql Error: " . $mysqli->error);
}

$stmt->bind_param("si", $userID, $sum);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$word_id = $row["ID"];

$stmt->close();
if($result-> num_rows === 0){
	$stmt->close();
	if($user_offset == 0) {
		$stmt = $mysqli->prepare("UPDATE users SET PositionMode? = PositionMode? + 1 WHERE UserID=?;");
		$stmt->bind_param("sss", $mode, $mode, $userID);
		$stmt->execute();
		$stmt->close();

		//Clean up the DB that stores the encountered words, else it become too big

		$stmt = $mysqli->prepare("DELETE FROM wordsAlreadySeenMode? WHERE UserID=? AND Rank < ? ;");
		$stmt->bind_param("ssi", $mode, $userID, $sum);
		$stmt->execute();
		$stmt->close();

	}
	else {
		$stmt = $mysqli->prepare("UPDATE users SET OffsetMode? = OffsetMode? + 1 WHERE UserID=?;");
		$stmt->bind_param("sss", $mode, $mode, $userID);
		$stmt->execute();
		$stmt->close();		
	}
	return lookForWord($userID, $mysqli);
}
else {



	$stmt = $mysqli->prepare("INSERT INTO wordsAlreadySeenMode? (UserID ,WordID, Rank) VALUES (?,?,?);");
		if ($stmt === FALSE) {
		die ("Mysql Error: " . $mysqli->error);
	}
	$stmt->bind_param("ssii", $mode, $userID, $word_id, $sum);
	$stmt->execute();
	$stmt->close();	
	if($user_offset > $offsetModulo){
		$stmt = $mysqli->prepare("UPDATE users SET OffsetMode? = 0 WHERE UserID=?;");
		$stmt->bind_param("ss", $mode, $userID);
		$stmt->execute();
		$stmt->close();
	}
	else {

		$stmt = $mysqli->prepare("UPDATE users SET OffsetMode? = OffsetMode? + 1 WHERE UserID=?;");
		$stmt->bind_param("ss", $mode, $userID);
		$stmt->execute();
		$stmt->close();	
	}	
	return $word_id;
}
}

function getDefinitions($word_id, $mysqli){
	$sql =  "SELECT sq.ID As WordID, sq.Word, sq.PartOfSpeech, d.ID As DefinitionID, d.Definition, d.GroupID, d.UserID As Author ";
	$sql .= "FROM (SELECT * FROM words WHERE ID=?) AS sq ";
	$sql .= "LEFT JOIN definitions As d ON sq.DefinitionID = d.GroupID WHERE d.GroupID IS NOT NULL";
	$sql .= " ORDER BY Votes desc;";

	$stmt = $mysqli->prepare($sql);
	if ($stmt === FALSE) {
		die ("Mysql Error: " . $mysqli->error);
	}

	$stmt->bind_param("i",  $word_id);
	$stmt->execute();
	$result = $stmt->get_result();

	if($result-> num_rows === 0){
		return FALSE;
	}
	else {

		while ($row = $result->fetch_assoc()) {
			$results_array[] = $row;
		}


		$stmt->close();
		return $results_array;
	}
}

?>
