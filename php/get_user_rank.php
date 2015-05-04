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
$userNameByUserID = array();
$users = array();
$thisUsersScore;


//Get the 5 best players by rank according to the selected categories

$mysqli = new mysqli('localhost', $user, $pass, $db);

//Get all Users

#get all concerned users;
$stmt = $mysqli->prepare("SELECT UserID, Username FROM users;");
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
	$users[] = $row["UserID"];
	$userNameByUserID[$row["UserID"]] = $row["Username"];

}

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
			$value = $row["totalpoints"];

			if($user == $userID){
				$thisUsersScore = $value;
			}

			if($value == null){
				$userAndScore[$user] = 0;
			}
			else {
				$userAndScore[$user] = $value;
			}
			$stmt->close();		
		}
	}
}


arsort($userAndScore);

$orderedScores = array_values($userAndScore);
$orderedUsers = array_keys($userAndScore);


$firstFiveUsers = array_slice($orderedUsers, 0, 5);
$firstFiveScores = array_slice($orderedScores, 0, 5);

if(! in_array($userID, $firstFiveUsers){
	$firstFiveUsers[]= $userID;
	$firstFiveScores[] = $userAndScore[$userID];
}


$result = array();
$result[] = $firstFiveScores;
$result[] = $firstFiveUsers;
$result[] = $userNameByUserID;

$result[] = array("currentUser"=>$thisUsersScore);

$jsonData = json_encode($result);
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
		$sql .= " SELECT points FROM game".$mode." WHERE userid='".$user."' ";
	}

	$sql .= " ) t;";

//echo "\n " . $sql;

	return $sql;
}

?>
