<?php

// include 'validate_token.php';

// $token = $_GET['token'];

// if(!validate_token($token)) {
// 	die();
// }

$userID = $_GET['userID'];

$user = 'root';
$pass = '';
$db = 'kamusi';

$con = mysqli_connect('localhost', $user, $pass, $db);

if (!$con) {
	die('Could not connect: ' . mysqli_error($con));
}

$sql = "SELECT * FROM users WHERE UserID='" . $userID . "';";
$result = mysqli_query($con, $sql);
$results_array = $result->fetch_assoc();

$user_position = $results_array["PositionMode2"];

$sql =  "SELECT ID As ID, DefinitionID As DefinitionID, Rank As Rank FROM (";
$sql.=	"SELECT w.ID, w.DefinitionID, r.Rank FROM rankedwords As r LEFT JOIN words As w ON r.Word = w.Word";
$sql.=	") As sq WHERE sq.ID IS NOT NULL AND sq.Rank >=" . $user_position . " ORDER BY(sq.Rank) LIMIT 1;";

$result = mysqli_query($con, $sql);
$results_array = $result->fetch_assoc();

$word_id = $results_array['ID'];
$definitionID = $results_array['DefinitionID'];
$new_rank = $results_array['Rank'] + 1;

// increment user position
$sql =	"UPDATE users SET PositionMode2 = " . $new_rank . " WHERE UserID = " . $userID . ";";
//$result = mysqli_query($con, $sql);

$sql =  "SELECT sq.ID, sq.Word, sq.PartOfSpeech, sq.DefinitionID, d.Definition FROM ";
$sql .= "(SELECT * FROM words WHERE ID=" . $word_id . ") AS sq ";
$sql .= "LEFT JOIN definitions As d ON sq.DefinitionID = d.GroupID ";
$sql .= "ORDER BY Votes desc LIMIT 1;";

$results_array = mysqli_query($con, $sql)->fetch_assoc();

$jsonData = json_encode($results_array);
echo $jsonData;

?>