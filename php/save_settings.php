<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
$userID = $_GET['userID'];
$notify = $_GET['notify'];
$post = $_GET['post'];

// USING ROOT IS A SECURITY CONCERN
$user = 'root';
$pass = '';
$db = 'kamusi';

$mysqli = new mysqli('localhost', $user, $pass, $db);

$stmt = $mysqli->prepare("UPDATE users SET NotificationTimeUnit=? WHERE UserID=$userID;");
$stmt->bind_param("s",  $notify);

$stmt->execute();
$stmt->close();

$stmt = $mysqli->prepare("UPDATE users SET PostTimeUnit=? WHERE UserID=$userID;");
$stmt->bind_param("s",  $post);

$stmt->execute();
$stmt->close();

?>
