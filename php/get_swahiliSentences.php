<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
$keyword = $_GET['keyword'];
$amount = $_GET['amount'];


#$output = shell_exec("ssh taito 'bash -s' < ../getDataForWord.sh" . $keyword . " " . $amount);
$output = shell_exec("cd..; ./getDataForWord.sh kamusi 5 2>&1");# . $keyword . " " . $amount);
#$output = shell_exec("cd ..; cat getDataForWord.sh");

echo($output);

?>