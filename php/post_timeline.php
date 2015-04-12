<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
$userID = $_GET['userID'];


// USING ROOT IS A SECURITY CONCERN

$user = 'root';
$pass = '';
$db = 'kamusi';


$mysqli = new mysqli('localhost', $user, $pass, $db);
$stmt = $mysqli->prepare("SELECT LastPost,WordTweetsSinceLastPost, PostTimeUnit,  FROM users WHERE  UserID = ?;");
$stmt->bind_param("s", $userID);
$stmt->execute();
$stmt->bind_result($LastPost, $WordTweetsSinceLastPost, $PostTimeUnit);
$stmt->fetch();
$stmt->close(); 


if($PostTimeUnit == "0") {
	echo json_encode($WordTweetsSinceLastPost);
}

?>
