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

$totalScore;


//Get the 5 best players by rank according to the selected categories

$mysqli = new mysqli('localhost', $user, $pass, $db);

//Points
if($metric == '0'){

	//rank over everything
	if($language == '0' && $mode == '0'){


		$stmt = $mysqli->prepare(getTotalPointsForUserStatement($userID));
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();
		$totalScore = $row["totalpoints"];
		$stmt->close();		
	}
}


echo "Total Score : " . $totalScore;

function getTotalPointsForUserStatement($user){
	include 'global.php';

	$sql = "SELECT SUM(t.points) AS totalpoints FROM ( ";
	$first=TRUE;
	foreach ($acceptedModes as $mode) {
		if(!$first){
			$sql .=" UNION ALL ";
			$first=FALSE;
		}
		$sql .= " SELECT points FROM game".$mode." WHERE userid=".$user." ";
	}

	$sql .= " ) t";
	echo "HAHAHA : " .$sql;

	return $sql;
}

?>
