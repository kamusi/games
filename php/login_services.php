<?php   
//1) Connect, see if you have a session_id and session_name

function setCurlDefaults($ch, $base_url){
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE); //equivalent to cache: false
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,30000);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //so that we get the data in the data variable in not on stdout 
	curl_setopt($ch, CURLOPT_VERBOSE, 1); //so that we get the data in the data variable in not on stdout 
}

function connect($session_name, $session_id, $csrf_token, $base_url) {
	global $kamusiUser;

	$connectHeaders = array();
	if ($csrf_token !== '' || $csrf_token !== 'undefined') {
		$connectHeaders = array('X-CSRF-Token: ' .$csrf_token);

	}

	$ch = curl_init();

	setCurlDefaults($ch,$base_url);
	curl_setopt($ch, CURLINFO_HEADER_OUT, true);

	curl_setopt($ch, CURLOPT_URL, $base_url . "/facebook_game_v1/system/connect.json");

	curl_setopt($ch, CURLOPT_HTTPHEADER, $connectHeaders);

	$plainresult =  curl_exec($ch);

	$sentHeaders = curl_getinfo($ch);
	debugVariable($sentHeaders, "Sent headers in connect"); 
	
	$result = tryToparseToJSONElseDie($plainresult);

	if (curl_errno($ch)) { 
		print "Error: " . curl_error($ch); 
	}
	else { 
            // Show me the result 
			debugVariable($result, "JSON response in connect"); 

		//storeUserData($base_url, $result->user->uid, $result->session_name, $result->sessid, $csrf_token);
		curl_close($ch); 
	}
}

//2) If Anonymous == uid<0 or uid=empty, you need to login

function login($sess_user, $sess_pass, $base_url) {

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $base_url . "/facebook_game_v1/user/login.json");

	setCurlDefaults($ch,$base_url);

	$data = array();
	$data['name']=$sess_user;
	$data['username'] = $sess_user;
	$data['pass'] = $sess_pass;
	$data['password'] = $sess_pass;

	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

	$plainresult =  curl_exec($ch); 
	
	$result = tryToparseToJSONElseDie($plainresult);

	if (curl_errno($ch)) { 
		print "Error: " . curl_error($ch); 
	}
	else { 
 		
		//debugVariable($result, "JSON response in login"); 

		storeUserData($base_url, $result->user->uid, $result->session_name, $result->sessid, '');
		curl_close($ch); 
	}
}
//3) Get Token, anonymous token
function getToken($session_name, $session_id, $base_url) {
	global $kamusiUser;

	$ch = curl_init();
	setCurlDefaults($ch,$base_url);

	$headers = array();

	if($session_name != '' && $session_id != '' ){
	//	curl_setopt($ch, CURLOPT_HEADER, TRUE);
		$headers = array ('Cookie: ' .$session_name . "=" . $session_id);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	}

	curl_setopt($ch, CURLOPT_URL, $base_url . "/facebook_game_v1/user/token.json");

	//set requestHEaderBefore Send?
	curl_setopt($ch, CURLINFO_HEADER_OUT, true);

	$plainresult =  curl_exec($ch);

	$sentHeaders = curl_getinfo($ch);
	debugVariable($sentHeaders, "Sent headers in getToken"); 


	
	$result = tryToparseToJSONElseDie($plainresult);

	if (curl_errno($ch)) { 
		print "Error: " . curl_error($ch); 
	}
	else { 
 		debugVariable($result, "JSON response in getToken"); 
	
		$kamusiUser['csrf_token'] = $result->token;

		curl_close($ch); 
	}
}

function tryToparseToJSONElseDie($whatToParse){
	$result = json_decode($whatToParse);
	if($result == NULL){
		die("Could not parse CURL response to JSON, what we got was: \n" . $whatToParse);
	}
	return $result;
}

function storeUserData($base_url, $uid, $session_name, $session_id, $csrf_token) {
	global $kamusiUser;
	$kamusiUser['base_url'] = $base_url;
	$kamusiUser['uid'] = $uid;
	$kamusiUser['session_name'] = $session_name;
	$kamusiUser['session_id'] = $session_id;

	if($csrf_token != ''){
		$kamusiUser['csrf_token'] = $csrf_token;
	}

}

