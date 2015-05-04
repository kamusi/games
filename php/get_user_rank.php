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


	//rank over everything
if($language == '0' && $mode == '0'){

	foreach ($users as $user) {
		$value;
		switch ($metric) {
			case '0':
			$stmt = $mysqli->prepare(getTotalPointsForUserStatement($user, "points"));
			$stmt->execute();
			$result = $stmt->get_result();
			$row = $result->fetch_assoc();
			$value = $row["total"];
			$stmt->close();
			break;

			case '1':
			$stmt = $mysqli->prepare(getTotalPointsForUserStatement($user, "submissions"));
			$stmt->execute();
			$result = $stmt->get_result();
			$row = $result->fetch_assoc();
			$value = $row["total"];
			$stmt->close();
			break;
			
			case '2':
			$stmt = $mysqli->prepare(getTotalPointsForUserStatement($user, "points"));
			$stmt->execute();
			$result = $stmt->get_result();
			$row = $result->fetch_assoc();
			$tempScore = $row["total"];
			$stmt->close();
			$stmt = $mysqli->prepare(getTotalPointsForUserStatement($user, "points"));
			$stmt->execute();
			$result = $stmt->get_result();
			$row = $result->fetch_assoc();
			$stmt->close();			
			$value = $tempScore/ $row["total"];

			default:
			die("Unexpected metric")
			break;
		}

		if($user == $userID){
			$thisUsersScore = $value;
		}

		if($value == null){
			$userAndScore[$user] = 0;
		}
		else {
			$userAndScore[$user] = $value;
		}
			

	}
}



arsort($userAndScore);

$orderedScores = array_values($userAndScore);
$orderedUsers = array_keys($userAndScore);


$firstFiveUsers = array_slice($orderedUsers, 0, 5);
$firstFiveScores = array_slice($orderedScores, 0, 5);

$result = array();
$result[] = $firstFiveScores;
$result[] = $firstFiveUsers;
$result[] = $userNameByUserID; //TODO only send the entries that are needed, we are sending them all!

$result[] = array("myScore"=>$thisUsersScore, "myRank"=> array_search($userID, $orderedUsers)+1 );

$jsonData = json_encode($result);
echo $jsonData;

function getTotalXForUserStatement($user, $x){
	include 'global.php';

	$sql = "SELECT SUM(t.". $x .") AS total FROM ( ";
		$first=TRUE;
		foreach ($acceptedModes as $mode) {
			if($first == TRUE){
				$first=FALSE;
			}
			else {
				$sql .=" UNION ALL ";
			}
			$sql .= " SELECT ". $x ." FROM game".$mode." WHERE userid='".$user."' ";
		}

		$sql .= " ) t;";


return $sql;
}

?>
