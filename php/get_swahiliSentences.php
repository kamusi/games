<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
$keyword = $_GET['keyword'];
$amount = $_GET['amount'];
$pointer = "";

set_include_path('/usr/share/pear/phpseclib/phpseclib');
include('Net/SSH2.php');

$sql= "SELECT pointer FROM game4pointer WHERE lemma = ?;";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $keyword);
$stmt->execute();
$result = $stmt->get_result();


$results_array = $result->fetch_assoc();

$pointer = $results_array['pointer'];
$stmt->close();



if(empty($pointer)) {
	$pointer= "";
	$sql= "INSERT INTO game4pointer (lemma, pointer ) VALUES (?,?);";
	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param("ss", $keyword, $pointer);
	$stmt->execute();
	$stmt->close();
}

$ssh = new Net_SSH2('taito.csc.fi');
if (!$ssh->login('babst', 'Jsts8472')) {
	exit('Login Failed');
}

$result=$ssh->exec('./getDataForWord.sh ' . $keyword . " " . $amount . " " . $pointer . "  2>&1");

$nextPointeDelimiter="NEXTPOINTER:";
//Get the new pointer and store it in the DB
#$pointer= substr($result, strpos($result, $nextPointeDelimiter) + strlen($nextPointeDelimiter));

$sql= "UPDATE game4pointer SET pointer= ? WHERE lemma = ?;";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ss", $pointer, $keyword);
$stmt->execute();
$stmt->close();

//extract the sentences we want:
$beginSentenceDelimiter="<SENTENCES>";
$endSentenceDelimiter="</SENTENCES>";

$positionOfBeginSentenceDelimiter=strpos($result, $beginSentenceDelimiter) + strlen($beginSentenceDelimiter);

$sentences = substr($result, $positionOfBeginSentenceDelimiter, strpos($result, $endSentenceDelimiter) - $positionOfBeginSentenceDelimiter);
$sentencesArray= explode("\n", $sentences);


$results_array= array();

foreach ($sentencesArray as $sentence ) {
	if(!ctype_space($sentence) && $sentence !=''){
		$results_array[]=$sentence;
	}
}
$jsonData = json_encode($results_array);
echo $jsonData;


?>