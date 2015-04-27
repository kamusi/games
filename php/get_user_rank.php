<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

$userID = $_GET['userID'];
$metric = $_GET['metric'];


$user = 'root';
$pass = '';
$db = 'kamusi';


$mysqli = new mysqli('localhost', $user, $pass, $db);

//Points
if($metric == '0'){

$stmt = $mysqli->prepare("");
$stmt->bind_param("ssssii",  $tweetID,$tweetText, $tweetAuthor,$userID, $wordID, $good);

$stmt->execute();
$stmt->close();
	
}

$stmt = $mysqli->prepare("");
$stmt->bind_param("ssssii",  $tweetID,$tweetText, $tweetAuthor,$userID, $wordID, $good);

$stmt->execute();
$stmt->close();


echo "Total Score : " . $totalScore;

?>
