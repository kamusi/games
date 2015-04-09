<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
$wordID = $_GET['wordID'];
$amount = $_GET['amount'];

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

// NOT CHECKING INPUTS MAKES THIS VULNERABLE TO SQL INJECTION
$sql = "SELECT DISTINCT TweetID,Text ,Author  FROM TweetContext WHERE WordID='" . $wordID . "';";
$result = mysqli_query($con, $sql);
$results_array = array();
var_dump($result);
while ($row = $result->fetch_assoc()) {
    $results_array[] = $row;
}
 mysqli_fetch_all($result,MYSQLI_ASSOC);



$jsonData = json_encode($results_array);

    switch (json_last_error()) {
        case JSON_ERROR_NONE:            
        break;
        case JSON_ERROR_DEPTH:
            echo ' - Maximum stack depth exceeded';
        break;
        case JSON_ERROR_STATE_MISMATCH:
            echo ' - Underflow or the modes mismatch';
        break;
        case JSON_ERROR_CTRL_CHAR:
            echo ' - Unexpected control character found';
        break;
        case JSON_ERROR_SYNTAX:
            echo ' - Syntax error, malformed JSON';
        break;
        case JSON_ERROR_UTF8:
            echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
        break;
        default:
            echo ' - Unknown error';
        break;
    }

echo $jsonData;

?>
