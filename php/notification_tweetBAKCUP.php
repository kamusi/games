<?php



// added in v4.0.5
require_once( 'Facebook/HttpClients/FacebookHttpable.php' );
require_once( 'Facebook/HttpClients/FacebookCurl.php' );
require_once( 'Facebook/HttpClients/FacebookCurlHttpClient.php' );

// added in v4.0.0
require_once('Facebook/Entities/AccessToken.php');
require_once( 'Facebook/FacebookSession.php' );
require_once( 'Facebook/FacebookRedirectLoginHelper.php' );
require_once( 'Facebook/FacebookRequest.php' );
require_once( 'Facebook/FacebookResponse.php' );
require_once( 'Facebook/FacebookSDKException.php' );
require_once( 'Facebook/FacebookRequestException.php' );
require_once( 'Facebook/FacebookOtherException.php' );
require_once( 'Facebook/FacebookAuthorizationException.php' );
require_once( 'Facebook/GraphObject.php' );
require_once( 'Facebook/GraphSessionInfo.php' );

// added in v4.0.5
use Facebook\FacebookHttpable;
use Facebook\FacebookCurl;
use Facebook\FacebookCurlHttpClient;

// added in v4.0.0
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
		'/' . $user_id . '/notifications',
		array (
	    	'href' => '',
	    	'template' => "Your definition for '" . $word . "' has been voted best!",
	  	)
	);

	$response = $request->execute();
	$graphObject = $response->getGraphObject();
	var_dump($response);
	echo "Print this";


?>