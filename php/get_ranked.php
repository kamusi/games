<?php

include 'validate_token.php';

$userID = $_GET['userID'];
// $token = $_GET['token'];

// if(!validate_token($token)) {
// 	die();
// }

// USING ROOT IS A SECURITY CONCERN
$user = 'root';
$pass = '';
$db = 'kamusi';

$con = mysqli_connect('localhost', $user, $pass, $db);

if (!$con) {
	die('Could not connect: ' . mysqli_error($con));
}

$mysqli = new mysqli('localhost', $user, $pass, $db);

$stmt = $mysqli->prepare("SELECT * FROM users WHERE UserID = ? ");
$stmt->bind_param("s", $userID );
$stmt->execute();
$result = $stmt->get_result();

$stmt->close();

$user_position = $result["PositionMode1"];



$jsonData = json_encode($user_position);
echo $jsonData;

?>
