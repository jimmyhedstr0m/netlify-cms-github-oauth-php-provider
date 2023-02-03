<?php
include "vars.php";
session_start();

$state = hash("sha512", mt_rand());
$_SESSION["oauth2state"] = $state;

$response_url = "https://github.com/login/oauth/authorize?response_type=code&client_id=".$client_id."&scope=repo,user&state=".$state;
header("Location: ".$response_url, true, 302);
exit;
?>
