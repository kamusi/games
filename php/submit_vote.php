<?php

include 'notification.php';
include 'honeypot.php';

$wordID = $_GET['wordID'];
$definitionID = $_GET['definitionID'];
$vote = $_GET['vote'];

$user = 'root';
$pass = '';
$db = 'kamusi';

$con = mysqli_connect('localhost', $user, $pass, $db);

if (!$con) {
	die('Could not connect: ' . mysqli_error($con));
}

//Increment points
$sql = 	"UPDATE definitions " .
		"SET Votes = Votes + " . $vote . " " . 
		"WHERE ID = " . $definitionID . ";";
$query = mysqli_query($con, $sql);

$sql = 	"SELECT UserID, Votes FROM definitions " .
		"WHERE ID = " . $definitionID . ";";

$result = mysqli_query($con, $sql);
$results_array = $result->fetch_assoc();

$user_id = $results_array["UserID"];
$votes = $results_array["Votes"];

//Increment user score--if eligible
if($votes == 3 && $user_id != 'wordnet') {
	$sql = 	"UPDATE users " .
			"SET Points = Points + 1 " . 
			"WHERE UserID = '" . $user_id . "';";
	$query = mysqli_query($con, $sql);
	echo "THis is the WORDID : " .$wordID;
	send_notification($user_id, $wordID);
}

update_user_rating($userID, $wordID);

echo 'Success' . $wordID;

?>
