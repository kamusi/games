<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
$userID = $argv[1];


// USING ROOT IS A SECURITY CONCERN

$user = 'root';
$pass = '';
$db = 'kamusi';


$mysqli = new mysqli('localhost', $user, $pass, $db);
$stmt = $mysqli->prepare("SELECT LastPost,WordTweetsSinceLastPost, PostTimeUnit  FROM users WHERE  UserID = ?;");
$stmt->bind_param("s", $userID);
$stmt->execute();
$stmt->bind_result($LastPost, $WordTweetsSinceLastPost, $PostTimeUnit);
$stmt->fetch();
$stmt->close(); 
$returnValue = array();


$stmt = $mysqli->prepare("UPDATE users SET WordTweetsSinceLastPost=0  WHERE  UserID = ?;");
$stmt->bind_param("s", $userID);
$stmt->execute();
$stmt->close(); 

echo json_encode($WordTweetsSinceLastPost);




?>
