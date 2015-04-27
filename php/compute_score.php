<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

$userID = $_GET['userID'];



$user = 'root';
$pass = '';
$db = 'kamusi';


$mysqli = new mysqli('localhost', $user, $pass, $db);


$totalScore = 0;

$stmt = $mysqli->prepare("INSERT INTO TweetContext (TweetID, Text, Author, UserID, WordID, Good) VALUES (?,?,?,?,?,? );");
$stmt->bind_param("ssssii",  $tweetID,$tweetText, $tweetAuthor,$userID, $wordID, $good);

$stmt->execute();
$stmt->close();



$stmt = $mysqli->prepare("SELECT SUM(Good) FROM TweetContext WHERE WordID= ? AND TweetID= ?;");
$stmt->bind_param("is", $wordID, $tweetID);
$stmt->execute();
$stmt->bind_result($totalScore);
    $stmt->fetch();
$result = $stmt->get_result();
$stmt->close();

#Check if this tweet was already vote down, if it was, remove it from the db
if ($totalScore < -1 ) {


	
$stmt = $mysqli->prepare("DELETE FROM TweetContext WHERE WordID= ? AND TweetID= ?;");
$stmt->bind_param("is", $wordID, $tweetID);
$stmt->execute();
$result = $stmt->get_result();
}








#$result = mysqli_query($con, $sql) or die(mysqli_error($con));


echo "Total Score : " . $totalScore;

?>
