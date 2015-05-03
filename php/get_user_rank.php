<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

include 'global.php';

$userID = $_GET['userID'];
$metric = $_GET['metric'];
$mode = $_GET['mode'];
$language = $_GET['language'];
$timePeriod =  $_GET['period'];

$user = 'root';
$pass = '';
$db = 'kamusi';

$userAndScore= array();
$users = array();


//Get the 5 best players by rank according to the selected categories

$mysqli = new mysqli('localhost', $user, $pass, $db);

//Get all Users

#get all concerned users;
$stmt = $mysqli->prepare("SELECT UserID FROM users;");
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
	$users[] = $row["UserID"];
}

var_dump($users);

$stmt->close();

//Points
if($metric == '0'){

	//rank over everything
	if($language == '0' && $mode == '0'){

		foreach ($users as $user) {

			$stmt = $mysqli->prepare(getTotalPointsForUserStatement($user));
			$stmt->execute();
			$result = $stmt->get_result();
			$row = $result->fetch_assoc();

			$userAndScore[$user] = $row["totalpoints"];
			$stmt->close();		
		}
	}
}


$jsonData = json_encode($userAndScore);
echo $jsonData;

function getTotalPointsForUserStatement($user){
	include 'global.php';

	$sql = "SELECT SUM(t.points) AS totalpoints FROM ( ";
	$first=TRUE;
	foreach ($acceptedModes as $mode) {
		if($first == TRUE){
			$first=FALSE;
		}
		else {
			$sql .=" UNION ALL ";
		}
		$sql .= " SELECT points FROM game".$mode." WHERE userid=".$user." ";
	}

	$sql .= " ) t";

	return $sql;
}

?>
