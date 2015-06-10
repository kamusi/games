<?php
//1) Connect, see if you have a session_id and session_name

function connect($session_name, $session_id, $csrf_token, $base_url) {
	$connectHeaders = array();
	if ($csrf_token !== '' || $csrf_token !== 'undefined') {
		$connectHeaders['X-CSRF-Token'] = $csrf_token;
	};

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $base_url . "/facebook_game_v1/system/connect.json");
	curl_setopt($ch, CURLOPT_POST, 1);
	//data type is json. HAve to set cache, tieout?

	curl_setopt($ch, CURLOPT_HTTPHEADER, $connectHeaders);

	$data = curl_exec($ch); 

	if (curl_errno($ch)) { 
		print "Error: " . curl_error($ch); 
	}
	else { 
            // Show me the result 
		var_dump($data); 
		curl_close($ch); 
	}
}

connect('','','',"http://dev.kamusi.org:8282");

