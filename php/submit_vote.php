<?php

include 'notification.php';
include 'login_services.php';

//login($sess_user, $sess_pass, $base_url);

echo "START";
var_dump($kamusiUser);
login($sess_user, $sess_pass, $base_url);
echo "END 2";
var_dump($kamusiUser);
connect($kamusiUser['session_name'], $kamusiUser['session_id'], '', $base_url);
echo "END 3";
var_dump($kamusiUser);

$wordID = $_GET['wordID'];
$definitionID = $_GET['definitionID'];
$vote = $_GET['vote'];
$groupID = $_GET['groupID'];
$mode = $_GET['mode'];
$language = $_GET['language'];



//Increment number of votes for this definition
$sql = 	"UPDATE definitions " .
"SET Votes = Votes + ? " . 
"WHERE ID = ?;";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ii", $vote, $definitionID);
$stmt->execute();
$stmt->close();



$sql = 	"SELECT UserID, Votes FROM definitions WHERE ID = ?;";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $definitionID);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

$results_array = $result->fetch_assoc();

$user_id = $results_array["UserID"];
$votes = $results_array["Votes"];

$earnedPoints = 1;

//If the definition got 3 votes, we reached consensus and the user gets 10 extra points
if($votes > 3 && $user_id != 'wordnet') {
	$earnedPoints = 11;
//Substract the pending points from the user
	$sql = 	"UPDATE games " .
	" SET pendingpoints = pendingpoints -10 ". 
	" WHERE userid = ? AND language = ? AND game = ?;";

	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param("sii", $user_id, $language, $mode);
	$stmt->execute();
	$stmt->close();
	echo "IN RIHWDIJWEDFKJERKCFJWçOKFJEçKLEKRGJF";
	send_notification($user_id, $wordID);
}

//Give the points to the user
addXToPointsInGame($user_id, $language, $mode, $earnedPoints);


//update_user_rating($user_id, $wordID, $groupID);

echo 'Success' . $wordID;

?>
