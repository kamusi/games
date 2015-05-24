<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
$keyword = $_GET['keyword'];
$amount = $_GET['amount'];
$wordid = $_GET['wordid'];


$sql= "SELECT sentenceid, sentence FROM game4sentences WHERE keyword = ? LIMIT ?;";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("si", $keyword, $amount);
$stmt->execute();
$result = $stmt->get_result();

//$stmt->close();

$results_array = array();
while ($row = $result->fetch_assoc()) {
	$sql= "UPDATE game4sentences SET used = used + 1 WHERE sentenceid = ?;";
	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param("i", $row["sentenceid"]);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();

	$results_array[] = $row["sentence"];
}



$jsonData = json_encode($results_array);
echo $jsonData;

?>