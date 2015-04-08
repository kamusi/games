<?php


$wordID = $_GET['wordID'];
$groupID = $_GET['groupID'];
$definition = $_GET['definition'];
$userID = $_GET['userID'];

$user = 'root';
$pass = '';
$db = 'kamusi';

$con = mysqli_connect('localhost', $user, $pass, $db);


if (!$con) {
	die('Could not connect: ' . mysqli_error($con));
}

if ($groupID == 'null') {
	$sql = 	"SELECT MAX(GroupID) FROM definitions;";
	$results_array = mysqli_query($con, $sql)->fetch_assoc();;
	$groupID = $results_array['MAX(GroupID)'] + 1;
	$sql = "UPDATE words SET DefinitionID=" . $groupID . " WHERE ID=" . $wordID . ";";
	$query = mysqli_query($con, $sql);
}

$sql = 	"INSERT INTO definitions " .
		"(Definition, GroupID, UserID) VALUES " . 
		"('" . $definition . "'," . $groupID . ",'" . $userID . "');";
$query = mysqli_query($con, $sql);

echo 'Success';

?>
