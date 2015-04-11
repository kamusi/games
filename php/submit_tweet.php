<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

$data = json_decode($_POST['json'], true);




$user = 'root';
$pass = '';
$db = 'kamusi';


$mysqli = new mysqli('localhost', $user, $pass, $db);

if (!$mysqli->set_charset('utf8')) {
    printf("Error loading character set utf8: %s\n", $mysqli->error);
}

$totalScoreOfTweet = 0;
$pendingScore = 02;
$concernedUsers = array();

#remove chracters that might be a problem

#insert the word in the TweetContext table
$stmt = $mysqli->prepare("INSERT INTO TweetContext (TweetID, Text, Author, UserID, WordID, Good) VALUES (?,?,?,?,?,? );");
$stmt->bind_param("ssssii",  $data["tweetID"],$data["tweetText"], $data["tweetAuthor"],$data["userID"], $data["wordID"], $data["good"]);

$stmt->execute();
$stmt->close();


#Sum up the good values
$stmt = $mysqli->prepare("SELECT SUM(Good) FROM TweetContext WHERE WordID= ? AND TweetID= ?;");
$stmt->bind_param("is", $data["wordID"], $data["tweetID"]);
$stmt->execute();
$stmt->bind_result($totalScoreOfTweet);
$stmt->fetch();

$stmt->close();

#get all concerned users;
$stmt = $mysqli->prepare("SELECT DISTINCT UserID FROM TweetContext WHERE WordID= ? AND TweetID= ?;");
$stmt->bind_param("is", $data["wordID"], $data["tweetID"]);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
	$concernedUsers[] = $row["UserID"];
}

$stmt->close();


#Check if this tweet has been voted as bad by at least 2 users
if ($totalScoreOfTweet < -1 ) {
	
	$stmt = $mysqli->prepare("DELETE FROM TweetContext WHERE WordID= ? AND TweetID= ?;");
	$stmt->bind_param("is", $data["wordID"], $data["tweetID"]);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();


	foreach($concernedUsers as $user) {

		$stmt = $mysqli->prepare("UPDATE users SET Points = Points + 1 WHERE UserID=?;");
		$stmt->bind_param("s", $user);
		$stmt->execute();
		$stmt->close();



	}
}

#after 5 upvotes, this tweet is a definite example for that word. Remove it from temp db and add it to the definitive db
if ($totalScoreOfTweet > 0 ) {

	foreach($concernedUsers as $user) {

		$stmt = $mysqli->prepare("UPDATE users SET Points = Points + 1 WHERE UserID=?;");
		$stmt->bind_param("s", $user);
		$stmt->execute();
		$stmt->close();

		postToTimeline($user);

	}

	$stmt = $mysqli->prepare("INSERT INTO WordTweet (WordID, TweetID, UserID, ts) VALUES (?,?,?, UTC_TIMESTAMP());");
	$stmt->bind_param("iss", $data["wordID"], $data["tweetID"], $data["userID"]);
	$stmt->execute();
	$stmt->close();	

	$stmt = $mysqli->prepare("INSERT INTO Tweets (TweetID, Text, Author) VALUES (?,?,?);");
	$stmt->bind_param("sss", $data["tweetID"], $data["tweetText"], $data["tweetAuthor"]  );
	$stmt->execute();
	$stmt->close();	


	#remove the tweet from the aggregation DB
	$stmt = $mysqli->prepare("DELETE FROM TweetContext WHERE WordID= ? AND TweetID= ?;");
	$stmt->bind_param("is", $data["wordID"], $data["tweetID"]);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();


	$numberOfRefsForThatWord = -1;
	$stmt = $mysqli->prepare("SELECT Count(WordID) FROM WordTweet WHERE WordID= ?;");
	$stmt->bind_param("i", $data["wordID"] );
	$stmt->execute();
	$stmt->bind_result($numberOfRefsForThatWord);
	$stmt->fetch();

	$stmt->close();	
	if($numberOfRefsForThatWord > 2) {
	var_dump($data["wordID"]);
	$stmt = $mysqli->prepare("UPDATE rank SET GotEnoughExamples=1 WHERE ID= ? ;");
	$stmt->bind_param("i", $data["wordID"]);
	$stmt->execute();
	$stmt->close();	
	}

}

foreach($concernedUsers as $user) {

$stmt = $mysqli->prepare("SELECT Count(UserID) FROM TweetContext WHERE  UserID = ?;");
$stmt->bind_param("s", $user);
$stmt->execute();
$stmt->bind_result($pendingScore);
$stmt->fetch();
$stmt->close();


$stmt = $mysqli->prepare("UPDATE users SET PendingPoints= ? WHERE UserID = ?;");
$stmt->bind_param("is", $pendingScore, $user);
$stmt->execute();
$stmt->close();

}

#$result = mysqli_query($con, $sql) or die(mysqli_error($con));


print('{}');

function postToTimeline($user) {
	$stmt = $mysqli->prepare("SELECT LastPost, PostTimeUnit FROM users WHERE  UserID = ?;");
	$stmt->bind_param("s", $user);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close(); 

	var_dump($result["LastPost"]); 

}

?>
