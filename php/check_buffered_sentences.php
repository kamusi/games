<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
$keyword = $_GET['keyword'];


$sql= "SELECT * FROM game4sentences WHERE keyword = ?;";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $keyword);
$stmt->execute();
$result = $stmt->get_result();

$whatToSend=$result->num_rows;
$stmt->close();


$jsonData = json_encode($whatToSend);
echo $jsonData;

?>