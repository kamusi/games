<?php

$wordID = $_GET['wordID'];
$userID = $_GET['userID'];
$translation = $_GET['translation'];
$mode = $_GET['mode'];
$language = $_GET['language'];


//increase the number of submissions for this user
addXSubmissionsInGame($userID, $language, $mode, 1);



$sql = 	"INSERT INTO translations (LanguageID, WordID, UserID, Translation) VALUES (?,?,?,?);";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("iiss", $language,$wordID, $userID ,$translation);

$stmt->execute();

$stmt->close();

echo 'lanugaeMap' . $languageMap;

?>