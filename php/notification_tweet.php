<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
$userID = $_GET['userID'];

require_once( 'facebook/Entities/AccessToken.php' );
// added in v4.0.5
require_once( 'facebook/HttpClients/FacebookHttpable.php' );
require_once( 'facebook/HttpClients/FacebookCurl.php' );
require_once( 'facebook/HttpClients/FacebookCurlHttpClient.php' );

// added in v4.0.0
require_once( 'facebook/FacebookSession.php' );
require_once( 'facebook/FacebookRedirectLoginHelper.php' );
require_once( 'facebook/FacebookRequest.php' );
require_once( 'facebook/FacebookResponse.php' );
require_once( 'facebook/FacebookSDKException.php' );
require_once( 'facebook/FacebookRequestException.php' );
require_once( 'facebook/FacebookOtherException.php' );
require_once( 'facebook/FacebookAuthorizationException.php' );
require_once( 'facebook/GraphObject.php' );
require_once( 'facebook/GraphSessionInfo.php' );

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

function send_notification($user_id, $word_id) {
	$user = 'root';
	$pass = '';
	$db = 'kamusi';



	FacebookSession::setDefaultApplication($app_id, $app_secret);

	// If you already have a valid access token:
	//$session = new FacebookSession($access_token);

	// If you're making app-level requests:
	$session = FacebookSession::newAppSession();

	//Mark user for notification on loading game
	$sql = 	"UPDATE users SET Notify=1 WHERE UserID='" . $user_id . "'";
	$query = mysqli_query($con, $sql);

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
	$word= "ahaa"

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
}

?>