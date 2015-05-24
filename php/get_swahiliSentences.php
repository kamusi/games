<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
$keyword = $_GET['keyword'];
$amount = $_GET['amount'];
$wordid = $_GET['wordid'];
$pointer = "";

$numberOfSentencesToFetchInAdvance = 3;

set_include_path('/usr/share/pear/phpseclib/phpseclib');
include('Net/SSH2.php');

//first we are going to look for the sentence in the buffered db.
//Here we have a mockup of word ids because word retrieval from kamusi side could not be implemented yet

$sql= "SELECT sentence FROM game4sentences WHERE keyword = ?;";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $keyword);
$stmt->execute();
$result = $stmt->get_result();
$results_array = $result->fetch_assoc();
$stmt->close();
///////////////

//find out if we need to fetch more from helsinki

$sql= "SELECT pointer FROM game4pointer WHERE lemma = ?;";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $keyword);
$stmt->execute();
$result = $stmt->get_result();


$results_array = $result->fetch_assoc();

$pointer = $results_array['pointer'];
$stmt->close();

if($pointer != "DONE"){
	//we have to fetch something, but in a background process heyho!!
<?php
class AsyncOperation extends Thread {

    public function __construct($arg) {
        $this->arg = $arg;
    }

    public function run() {
        if ($this->arg) {
            $sleep = mt_rand(1, 10);
            printf('%s: %s  -start -sleeps %d' . "\n", date("g:i:sa"), $this->arg, $sleep);
            sleep($sleep);
            printf('%s: %s  -finish' . "\n", date("g:i:sa"), $this->arg);
        }
    }
}

// Create a array
$stack = array();

//Iniciate Miltiple Thread
foreach ( range("A", "D") as $i ) {
    $stack[] = new AsyncOperation($i);
}

// Start The Threads
foreach ( $stack as $t ) {
    $t->start();
}

?>
/*
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

	$result=$ssh->exec('./getDataForWord.sh ' . $keyword . " " . $numberOfSentencesToFetchInAdvance . " " . $pointer . "  2>&1");

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
		$sql= "INSERT INTO game4sentences(keyword, sentence, author, fileinfo) VALUES (?,?,?,?);";
		$stmt = $mysqli->prepare($sql);
		$stmt->bind_param("ssss", $keyword, $sentence, $sourceText[$index], $sourceFiles[$index]);
		$stmt->execute();
		$stmt->close();

	}*/
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