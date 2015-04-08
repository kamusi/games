<?php

$userID = $_GET['userID'];

$user = 'root';
$pass = '';
$db = 'kamusi';

$con = mysqli_connect('localhost', $user, $pass, $db);

if (!$con) {
	die('Could not connect: ' . mysqli_error($con));
}

$sql =	"UPDATE users SET Mute=1 WHERE UserID='" . $userID . "';";
$result = mysqli_query($con, $sql);

echo "Mute successful";

?>