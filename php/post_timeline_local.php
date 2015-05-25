<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
$userID = $argv[1];


$stmt = $mysqli->prepare("UPDATE users SET DoPost=1  WHERE  UserID = ?;");
$stmt->bind_param("s", $userID);
$stmt->execute();
$stmt->bind_result($LastPost, $WordTweetsSinceLastPost, $PostTimeUnit);
$stmt->fetch();
$stmt->close(); 
$returnValue = array();

 json_encode($WordTweetsSinceLastPost);




?>
