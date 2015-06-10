<?php
//1) Connect, see if you have a session_id and session_name

function setCurlDefaults($ch, $base_url){
	curl_setopt($ch, CURLOPT_POST, 1);
	//data type is json. HAve to set cache, tieout?

	curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE); //equivalent to cache: false
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,30000);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //so that we get the data in the data variable in not on stdout 

}

function connect($session_name, $session_id, $csrf_token, $base_url) {
	global $kamusiUser;

	$connectHeaders = array();
	if ($csrf_token !== '' || $csrf_token !== 'undefined') {
		$connectHeaders['X-CSRF-Token'] = $csrf_token;
	};

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $base_url . "/facebook_game_v1/system/connect.json");

	setCurlDefaults($ch,$base_url);

	curl_setopt($ch, CURLOPT_HTTPHEADER, $connectHeaders);

	$result = $result = json_decode(curl_exec($ch)); 

	if (curl_errno($ch)) { 
		print "Error: " . curl_error($ch); 
	}
	else { 
            // Show me the result 
		echo "CONNECT RETURNED : ";
		//var_dump($result); 
		storeUserData($base_url, $result->user->uid, $result->session_name, $result->sessid, $csrf_token);
		$kamusiUser['csrf_token'] = $csrf_token;
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

	$result =  json_decode(curl_exec($ch)); 


 	if (curl_errno($ch)) { 
		print "Error: " . curl_error($ch); 
	}
	else { 
            // Show me the result 
		echo "Login BEGIN";
	//	var_dump($result); 

	storeUserData($base_url, $result->user->uid, $result->session_name, $result->sessid);
		echo "Login END";


		curl_close($ch); 
	}
}
//3) Get Token, anonymous token
function getToken($session_name, $session_id, $base_url) {
	$ch = curl_init();
	setCurlDefaults($ch,$base_url);
	curl_setopt($ch, CURLOPT_URL, $base_url . "/facebook_game_v1/user/token.json");

	$headers = array();
	$headers['Cookie'] = $session_name + "=" + $session_id;
	//set xhrfields : with credentials?
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	//set requestHEaderBefore Send?
	$result =  json_decode(curl_exec($ch)); 

 	if (curl_errno($ch)) { 
		print "Error: " . curl_error($ch); 
	}
	else { 
            // Show me the result 
		echo "getToken BEGIN";
	//	var_dump($result); 

	//	storeUserData($base_url, $result.user.uid, $result.session_name, result.sessid, csrf_token);
		echo "getToken END";

		 $kamusiUser['csrf_token'] = $result->token;



		curl_close($ch); 
	}


}

function storeUserData($base_url, $uid, $session_name, $session_id) {
		global $kamusiUser;
		$kamusiUser['base_url'] = $base_url;
		$kamusiUser['uid'] = $uid;
		$kamusiUser['session_name'] = $session_name;
		$kamusiUser['session_id'] = $session_id;


		//var_dump($kamusiUser);
}

