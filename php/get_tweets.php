<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
$keyword = $_GET['keyword'];
$amount = $_GET['amount'];




$output = shell_exec("python2.7 ../twitterReq.py " . $keyword . " " . $amount);

echo($output);

?>