<?php

include 'login_services.php';
$userID = $_GET['userID'];

authentification();
getSwahiliwords($userID);

?>
