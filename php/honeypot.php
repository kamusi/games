<?php

function update_user_rating($userID, $wordID) {
	$user = 'root';
	$pass = '';
	$db = 'kamusi';

	//Check for consensus
	$sql = 	"SELECT Consensus FROM rankedwords " .
			"WHERE WordID=" . $wordID . ";";
	$query = mysqli_query($con, $sql);
	$results_array = $result->fetch_assoc();

	if($results_array["Consensus"]) {
		$sql = 	"SELECT * FROM definitions " .
				"WHERE WordID= " . $wordID . " " .
				"ORDER BY Votes DESC " .
				"LIMIT 1;";

		$query = mysqli_query($con, $sql);
		$results_array = $result->fetch_assoc();

		$sql = 	"SELECT COUNT(*) As Count FROM definitions " .
				"WHERE WordID= " . $wordID . ";";
		$query = mysqli_query($con, $sql);
		$results_array = $result->fetch_assoc();

		$rand_exp = 1/min(5, $results_array["Count"]);

		if($definition = $results_array['DefinitionID']) { //User selected correctly
			$sql = 	"UPDATE users SET Rating = Rating + " . (1 - $rand_exp) . "WHERE UserID='" . $userID . "';";
		}
		else { //User did not select correctly
			$sql = 	"UPDATE users SET Rating = Rating + " . -$rand_exp . "WHERE UserID='" . $userID . "';";
		}
		$query = mysqli_query($con, $sql);
	}
}

?>