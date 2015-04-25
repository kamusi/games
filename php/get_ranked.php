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

$mysqli = new mysqli('localhost', $user, $pass, $db);

//fetch the user in order to see which word is for him
$stmt = $mysqli->prepare("SELECT * FROM users WHERE UserID = ? ");
$stmt->bind_param("s", $userID );
$stmt->execute();
$result = $stmt->get_result();

$stmt->close();

$row = $result->fetch_assoc();
$user_position = $row["PositionMode1"];
$user_offset = $row["OffsetMode1"];

//fetch the word that has as rank user s position+offset
$sql =  "SELECT ID As ID, DefinitionID As DefinitionID, Rank As Rank FROM (";
$sql.=	"SELECT w.ID, w.DefinitionID, r.Rank FROM rankedwords As r LEFT JOIN words As w ON r.Word = w.Word";
$sql.=	") As sq WHERE sq.ID IS NOT NULL  AND sq.Rank = ? LIMIT 1;";

$sum = intval($user_position) + intval($user_offset);

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i",  $sum);
$stmt->execute();
$result = $stmt->get_result();

$row = $result->fetch_assoc();
$word_id = $row["ID"];

$stmt->close();

$sql =  "SELECT sq.ID As WordID, sq.Word, sq.PartOfSpeech, d.ID As DefinitionID, d.Definition, d.GroupID, d.UserID As Author ";
$sql .= "FROM (SELECT * FROM words WHERE ID=?) AS sq ";
$sql .= "LEFT JOIN definitions As d ON sq.DefinitionID = d.GroupID";
$sql .= "WHERE d.GroupID NOT IN (SELECT GroupID FROM usersDefinitionsMode1 WHERE userID= ?) ORDER BY Votes desc;";

$stmt = $mysqli->prepare($sql);
if ($stmt === FALSE) {
    die ("Mysql Error: " . $mysqli->error);
}
var_dump($stmt);

$stmt->bind_param("is",  $word_id, $userID);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
	$results_array[] = $row;
}


$stmt->close();


$jsonData = json_encode($results_array);
echo $jsonData;

?>
