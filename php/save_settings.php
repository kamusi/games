<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
$userID = $_GET['userID'];
$notify = $_GET['notify'];
$post = $_GET['post'];

// USING ROOT IS A SECURITY CONCERN
$user = 'root';
$pass = '';
$db = 'kamusi';

$mysqli = new mysqli('localhost', $user, $pass, $db);

$stmt = $mysqli->prepare("UPDATE users SET NotificationTimeUnit=? WHERE UserID=$userID;");
$stmt->bind_param("s",  $notify);

$stmt->execute();
$stmt->close();

$stmt = $mysqli->prepare("UPDATE users SET PostTimeUnit=? WHERE UserID=$userID;");
$stmt->bind_param("s",  $post);

$stmt->execute();
$stmt->close();

$string = "";

//every day at midnight
if($post == "0"){
	$string = "00 00 * * *"; 
}
//every sunday
else if ($post == "1") {
 	$string = "00 00 * * 0";
}
//first of every month
else {
	$string = "00 00 1 * *";	
}

$output1 = shell_exec("echo \"". $string . "       /usr/bin/php -f /var/www/html/php/post_timeline_local.php " . $_GET['userID'] . "\" > /var/www/tempText/posts.txt; cat /var/www/tempText/posts.txt > /var/www/tempText/both.txt 2>&1");
$output2 = shell_exec("cat /var/www/tempText/notifications.txt >> /var/www/tempText/both.txt ; crontab /var/www/tempText/posts.txt 2>&1");

$bla = shell_exec("crontab -l 2>&1");


var_dump($bla);

?>
