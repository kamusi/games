<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

//include 'global.php';

$userID = $_GET['userID'];
$metric = $_GET['metric'];
$selectedMode = $_GET['mode'];
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


foreach ($users as $user) {
	$value;
	switch ($metric) {
		case '0':
		$stmt = $mysqli->prepare(getTotalXForUserStatement($user, "points", $timePeriod));
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();
		$value = $row["total"];
		$stmt->close();
		break;

		case '1':
		$stmt = $mysqli->prepare(getTotalXForUserStatement($user, "submissions", $timePeriod));
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();
		$value = $row["total"];
		$stmt->close();
		break;

		case '2':
		$stmt = $mysqli->prepare(getTotalXForUserStatement($user, "points", $timePeriod));
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();
		$tempScore = $row["total"];
		$stmt->close();

		$stmt = $mysqli->prepare(getTotalXForUserStatement($user, "submissions", $timePeriod));
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();
		$stmt->close();			
		$value = $tempScore/ ($row["total"] + 1);
		break;

		default:
		die("Unexpected metric");
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




arsort($userAndScore);

$orderedScores = array_values($userAndScore);
$orderedUsers = array_keys($userAndScore);
$orderedUsers = array_map('strval',$orderedUsers);


$firstFiveUsers = array_slice($orderedUsers, 0, 5);
$firstFiveScores = array_slice($orderedScores, 0, 5);

$result = array();
$result[] = $firstFiveScores;
$result[] = $firstFiveUsers;
$result[] = $userNameByUserID; 
//TODO only send the entries that are needed, we are sending them all!

$result[] = array("myScore"=>$thisUsersScore, "myRank"=> array_search($userID, $orderedUsers)+1 );

$jsonData = json_encode($result);
echo $jsonData;

function getTotalXForUserStatement($user, $x){
	global $selectedMode, $language, $timePeriod, $acceptedModes; 

	if($timePeriod == '1') {
		$x.= "month";	
	}
	if($timePeriod == '2') {
		$x.= "week";	
	}

	$sql = "SELECT SUM(t.". $x .") AS total FROM ( ";

		if($timePeriod == '3'){
			if($language == '0' && $selectedMode == '0'){
				$sql .= " SELECT ". $x ." FROM pointtime WHERE userid='".$user."' ";
			}
			else if( $language == '0') {
				$sql .= " SELECT ". $x ." FROM pointtime WHERE userid='".$user."' AND game= " . $selectedMode ." ";
			}
			else if ($selectedMode == '0') {
				$sql .= " SELECT ". $x ." FROM pointtime WHERE userid='".$user."' AND language= " . $language ." ";
			}	
			else {
				$sql .= " SELECT ". $x ." FROM pointtime WHERE userid='".$user."' AND language= " . $language . " AND game= " . $selectedMode ." ";
			}	
		}
		else {

			//rank of everything
			if($language == '0' && $selectedMode == '0'){
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
			}
			else if($selectedMode == '0'){
				$first=TRUE;
				foreach ($acceptedModes as $mode) {
					if($first == TRUE){
						$first=FALSE;
					}
					else {
						$sql .=" UNION ALL ";
					}
					$sql .= " SELECT ". $x ." FROM game".$mode." WHERE userid='".$user."' AND language=" . $language . " ";
				}			
			}
			else if( $language == '0') {
				$sql .= " SELECT ". $x ." FROM game".$selectedMode." WHERE userid='".$user."' ";
			}
			else {
				$sql .= " SELECT ". $x ." FROM game".$selectedMode." WHERE userid='".$user."' AND language=" . $language . " ";

			}
		}
		
	$sql .= " ) t;";
	return $sql;
}

?>
