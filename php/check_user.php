<?php

$userID = $_GET['userID'];

$user = 'root';
$pass = '';
$db = 'kamusi';

$con = mysqli_connect('localhost', $user, $pass, $db);

if (!$con) {
	die('Could not connect: ' . mysqli_error($con));
}

$sql = "SELECT EXISTS (SELECT * FROM users WHERE userID='" . $userID . "') As CheckResult;";
$query = mysqli_query($con, $sql);
$checkResult = mysqli_fetch_array($query);

if(!$checkResult[0]) {
	//Add user to database
	$sql = "INSERT INTO users (UserID) VALUES(" . $userID . ");";
	$query = mysqli_query($con, $sql);
}

$jsonData = json_encode($checkResult);
echo $jsonData;

?>