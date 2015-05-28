<?php
$_SESSION = array();

//TODO make this cleaner with the sessions
// If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finally, destroy the session.
session_destroy();
error_reporting(E_ALL);

ini_set('display_errors', 'On');
$userID = $_GET['userID'];
$language = $_GET['menuLanguage'];

$stmt = $mysqli->prepare("UPDATE users SET Language=? WHERE UserID=$userID;");
$stmt->bind_param("i",  $language);
$stmt->execute();
$stmt->close();

$return = "Menu Language Changed";
json_encode(return);

?>
