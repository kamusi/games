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

$con = mysqli_connect('localhost', $user, $pass, $db);

if (!$con) {
	die('Could not connect: ' . mysqli_error($con));
}

if (!mysqli_set_charset($con, "utf8")) {
    echo "PROBLEM WITH UTF 8 ENCODIG";
}

$stmt = $mysqli->prepare("UPDATE users SET NotificationTimeUnit=? WHERE UserID=$userID;");
$stmt->bind_param("s",  $data["notify"]);

$stmt->execute();
$stmt->close();

$stmt = $mysqli->prepare("UPDATE users SET PostTimeUnit=? WHERE UserID=$userID;");
$stmt->bind_param("s",  $data["notify"]);

$stmt->execute();
$stmt->close();

?>
