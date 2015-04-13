<?php



session_start();
require_once( 'Facebook/Entities/AccessToken.php' );
require_once( 'Facebook/Entities/SignedRequest.php' );
require_once('Facebook/HttpClients/FacebookHttpable.php');
require_once('Facebook/HttpClients/FacebookCurl.php');
require_once('Facebook/HttpClients/FacebookCurlHttpClient.php');
require_once('Facebook/FacebookSession.php');
require_once('Facebook/FacebookRedirectLoginHelper.php');
require_once('Facebook/FacebookRequest.php');
require_once('Facebook/FacebookResponse.php');
require_once('Facebook/FacebookSDKException.php');
require_once('Facebook/FacebookRequestException.php');
require_once('Facebook/FacebookOtherException.php');
require_once('Facebook/FacebookAuthorizationException.php');
require_once('Facebook/GraphObject.php');
require_once('Facebook/GraphSessionInfo.php');
require_once('Facebook/GraphUser.php');

use Facebook\HttpClients\FacebookHttpable;
use Facebook\HttpClients\FacebookCurl;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookOtherException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphSessionInfo;
use Facebook\GraphUser;
use Facebook\Entities\AccessToken;
use Facebook\Entities\SignedRequest;



error_reporting(E_ALL);
ini_set('display_errors', 'On');


$userID = $_GET['userID'];
$user = 'root';
$pass = '';
$db = 'kamusi';

$con = mysqli_connect('localhost', $user, $pass, $db);

if (!$con) {
	die('Could not connect: ' . mysqli_error($con));
}

$sql =	"SELECT * FROM app;";

$result = mysqli_query($con, $sql);

$results_array = $result->fetch_assoc();

	//These must be retrieved from the database
$app_id = $results_array["app_id"];
$app_secret = $results_array["app_secret"];

$mysqli = new mysqli('localhost', $user, $pass, $db);

$stmt = $mysqli->prepare("SELECT  NewPointsSinceLastNotification FROM users WHERE UserID=?;");
$stmt->bind_param("s", $userID);
$stmt->bind_result($newPoints);
$stmt->fetch();
$stmt->execute();
$stmt->close();

if($newPoints == 0){
	echo "Did not sent notif, no points were gained." .$userID ;
}
else {
	FacebookSession::setDefaultApplication($app_id, $app_secret);

	// If you already have a valid access token:
	//$session = new FacebookSession($access_token);

	// If you're making app-level requests:
	$session = FacebookSession::newAppSession();

	// To validate the session:
	try {
		$session->validate();
	}
	catch (FacebookRequestException $ex) {
		// Session not valid, Graph API returned an exception with the reason.
		echo $ex->getMessage();
	}
	catch (\Exception $ex) {
		// Graph API returned info, but it may mismatch the current app or have expired.
		echo $ex->getMessage();
	}
	$word= "ahaa";
	// start session
	$request = new FacebookRequest(
		$session,
		'POST',
		'/' . $userID . '/notifications',
		array (
			'href' => '',
			'template' => "You just gained '" . $newPoints . " new points' !",
			)
		);

	$response = $request->execute();
	$graphObject = $response->getGraphObject();
}


?>