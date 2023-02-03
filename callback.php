<?php
include "vars.php";
session_start();

function getToken($params) {
    global $client_id, $client_secret;
    $options = array(
        "grant_type" => "authorization_code",
        "client_id" => $client_id,
        "client_secret" => $client_secret
    );
    $data = array_merge($params, $options);

    $url = "https://github.com/login/oauth/access_token";

    $curl = curl_init($url);
    $headers = array(
        "Accept: application/json",
        "Content-Type: application/x-www-form-urlencoded",
    );

    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    
    $result = curl_exec($curl);
    curl_close($curl);
    
    $response = json_decode($result);
    return $response;
}

if (empty($_GET["state"]) || (isset($_SESSION["oauth2state"]) && $_GET["state"] !== $_SESSION["oauth2state"])) {
    if (isset($_SESSION["oauth2state"])) {
        unset($_SESSION["oauth2state"]);
    }

    exit("Invalid state");
}

if (!isset($_GET["code"]) || $_GET["code"] == "") {
    http_response_code(400);
    exit("Code is missing");
}

$code = $_GET["code"];
$options = array(
    "code" => $code
);

$result = getToken($options);

$mess = "success";
$token = "";

if ($result->error != "") {
    $mess = "error";
} else {
    $token = $result->access_token;
}

$content = array(
    "token" => $token,
    "provider" => "github"
);

$html = "
<script>
    (function() {
        function recieveMessage(e) {
            console.log(\"recieveMessage %o\", e);
            
            // send message to main window with the app
            window.opener.postMessage(
                'authorization:github:".$mess.":".json_encode($content)."', 
                e.origin
            );
        }

        window.addEventListener(\"message\", recieveMessage, false);
        window.opener.postMessage(\"authorizing:github\", \"*\");
    })();
</script>
";

echo $html;
?>
