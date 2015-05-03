<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

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
		$sql = "SELECT SUM(t.points) AS totalpoints FROM (SELECT points FROM game1 WHERE userid=? UNION ALL";
		$sql .= " SELECT points FROM game2 WHERE userid=? UNION ALL SELECT points FROM game3 WHERE userid=?";
		$sql .= " ) t";
		$stmt = $mysqli->prepare($sql);
		$stmt->bind_param("sss",$userID,$userID,$userID);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();
		$totalScore = $row["totalpoints"];
		$stmt->close();		
	}
}


echo "Total Score : " . $totalScore;

?>
