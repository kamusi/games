<?php

include 'validate_token.php';

$userID = $_GET['userID'];
// $token = $_GET['token'];

// if(!validate_token($token)) {
// 	die();
// }

// USING ROOT IS A SECURITY CONCERN
$user = 'root';
$pass = '';
$db = 'kamusi';

$con = mysqli_connect('localhost', $user, $pass, $db);

if (!$con) {
	die('Could not connect: ' . mysqli_error($con));
}


// Retrieve ID of word with first Rank greater than user_position, i.e. the first word with a sense.
$sql =  "SELECT ID As ID, DefinitionID As DefinitionID, Rank As Rank FROM (";
$sql.=	"SELECT w.ID, w.DefinitionID, r.Rank FROM rankedwords As r LEFT JOIN words As w ON r.Word = w.Word";
$sql.=	") As sq WHERE sq.ID IS NOT NULL AND sq.Rank =2 ORDER BY(sq.Rank) LIMIT 1;";

$result = mysqli_query($con, $sql);
$results_array = $result->fetch_assoc();

$word_id = $results_array['ID'];
$new_rank = $results_array['Rank'] + 1;

// increment user position
//$sql =	"UPDATE users SET PositionMode1 = " . $new_rank . " WHERE UserID = " . $userID . ";";
//
// Return all definitions corersponding to this GroupID
$sql =  "SELECT sq.ID As WordID, sq.Word, sq.PartOfSpeech, d.ID As DefinitionID, d.Definition, d.GroupID, d.UserID As Author ";
$sql .= "FROM (SELECT * FROM words WHERE ID=" . $word_id . ") AS sq ";
$sql .= "LEFT JOIN definitions As d ON sq.DefinitionID = d.GroupID ORDER BY Votes desc;";

$result = mysqli_query($con, $sql);

$results_array = array();

while ($row = $result->fetch_assoc()) {
	$results_array[] = $row;
}

$jsonData = json_encode($results_array);
echo $jsonData;

?>
