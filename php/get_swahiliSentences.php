<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
$keyword = $_GET['keyword'];
$amount = $_GET['amount'];
$pointer = "";


echo "PATH : ". get_include_path();
set_include_path('/usr/share/pear/phpseclib/phpseclib');
include('Net/SSH2.php');

$sql= "SELECT pointer FROM game4pointer WHERE lemma = ?;";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $keyword);
$stmt->execute();
$result = $stmt->get_result();

if($result){
	$results_array = $result->fetch_assoc();

	$pointer = $results_array['pointer'];
	$stmt->close();
	

}
else {
	$stmt->close();
	$pointer= "";
	$sql= "INSERT INTO game4pointer (lemma, pointer ) VALUES (?,?));";
	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param("ss", $keyword, $pointer);
	$stmt->execute();
	$stmt->close();
}

echo "COUCOUC";
var_dump($result);
var_dump($pointer);
echo $pointer;

$ssh = new Net_SSH2('taito.csc.fi');
if (!$ssh->login('babst', 'Jsts8472')) {
	exit('Login Failed');
}

#$result=$ssh->exec('./getDataForWord.sh ' . $keyword . " " . $amount . " " . $pointer . "  2>&1");

//Get the new pointer and store it in the DB
$pointer= substr($result, strpos($result, "NEXTPOINTER:"));

$sql= "UPDATE game4pointer SET pointer= ? WHERE lemma = ?;";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ss", $pointer, $keyword);
$stmt->execute();
$stmt->close();

echo $result;
/*
#$output = shell_exec('cd .. ; echo $USER ; ssh -i /home/ec2-user/.ssh/taitoApache.rsa babst@taito.csc.fi  \'bash -s\' < getDataForWord.sh ' . $keyword . " " . $amount . " 2>&1");
$output = shell_exec('cd .. ; echo $USER ; ssh -i /var/www/.ssh/taitoApache.rsa babst@taito.csc.fi echo bouyeah ls 2>&1');

#$output = shell_exec("cd .. ; pwd;  bash getDataForWord.sh kamusi 5 2>&1");# . $keyword . " " . $amount);
#$output = shell_exec('echo blabla; groups $USER; echo $USER');
*/

?>