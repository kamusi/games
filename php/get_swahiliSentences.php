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
if (!$ssh->login($helsinkiUserName, $helsinkiPassWord)) {
	exit('Login Failed');
}

$result=$ssh->exec('./getDataForWord.sh ' . $keyword . " " . $amount . " " . $pointer . "  2>&1");

$nextPointeDelimiter="NEXTPOINTER:";
//Get the new pointer and store it in the DB
$pointer= substr($result, strpos($result, $nextPointeDelimiter) + strlen($nextPointeDelimiter));

$sql= "UPDATE game4pointer SET pointer= ? WHERE lemma = ?;";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ss", $pointer, $keyword);
$stmt->execute();
$stmt->close();

$results_array= array();

$sentences = extractArray("SENTENCES");
$sourceFiles= extractArray("SOURCEFILE");
$sourceText= extractArray("SOURCESTEXT");

foreach ($sentences as $index => $sentence) {
	$sql= "INSERT INTO game4sentences(sentence, author, fileinfo) VALUES (?,?,?);";
	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param("sss", $sentence, $sourceText[$index], $sourceFiles[$index]);
	$stmt->execute();
	$stmt->close();

}

/*var_dump($sentences); 
var_dump($sourceFiles);
var_dump($sourceText);
*/

$jsonData = json_encode($sentences);
echo $jsonData;

function extractArray($inputDelimiter){
	global $result;
	$resulting_array = array();
	$beginArrayDelimiter="<".$inputDelimiter.">";
	$endArrayDelimiter="</".$inputDelimiter.">";
	$positionOfBeginArrayDelimiter=strpos($result, $beginArrayDelimiter) + strlen($beginArrayDelimiter);

	$sentences = substr($result, $positionOfBeginArrayDelimiter, strpos($result, $endArrayDelimiter) - $positionOfBeginArrayDelimiter);
	$array= explode("\n", $sentences);

	foreach ($array as $element ) {
		if(!ctype_space($element) && $element !=''){
			$resulting_array[]=$element;
		}
	}
	return $resulting_array;
}

?>