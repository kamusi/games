<?php

function update_user_rating($userID, $wordID) {
	$user = 'root';
	$pass = '';
	$db = 'kamusi';

	$con = mysqli_connect('localhost', $user, $pass, $db);

	if (!$con) {
		die('Could not connect: ' . mysqli_error($con));
	}


	//Check for consensus
	$sql = 	"SELECT Consensus FROM rankedwords " .
			"WHERE WordID=" . $wordID . ";";
	$result = mysqli_query($con, $sql);
	$results_array = $result->fetch_assoc();

	if($results_array["Consensus"]) {
		$sql = 	"SELECT * FROM definitions " .
				"WHERE WordID= " . $wordID . " " .
				"ORDER BY Votes DESC " .
				"LIMIT 1;";

		$result = mysqli_query($con, $sql);
		$results_array = $result->fetch_assoc();

		$sql = 	"SELECT COUNT(*) As Count FROM definitions " .
				"WHERE WordID= " . $wordID . ";";
		$result = mysqli_query($con, $sql);
		$results_array = $result->fetch_assoc();

		$rand_exp = 1/min(5, $results_array["Count"]);

		if($definition = $results_array['DefinitionID']) { //User selected correctly
			$sql = 	"UPDATE users SET Rating = Rating + " . (1 - $rand_exp) . "WHERE UserID='" . $userID . "';";
		}
		else { //User did not select correctly
			$sql = 	"UPDATE users SET Rating = Rating + " . -$rand_exp . "WHERE UserID='" . $userID . "';";
		}
		$result = mysqli_query($con, $sql);
	}
}

?>