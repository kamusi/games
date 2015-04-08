<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
$wordID = $_GET['wordID'];

$user = 'root';
$pass = '';
$db = 'kamusi';

$con = mysqli_connect('localhost', $user, $pass, $db);


if (!$con) {
	die('Could not connect: ' . mysqli_error($con));
}
$sql = ";";


$query = mysqli_query($con, $sql);


echo 'Success' . $userID . "tweetID" . $tweetID;

?>
