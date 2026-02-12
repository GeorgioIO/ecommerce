<?php

require_once __DIR__ . '../../../configuration/session.php';


header("Content-Type: application/json");

$response = [];

if(isset($_SESSION['redirect-message']) || isset($_SESSION['redirect-message-type']))
{
    $response['redirect-message'] = $_SESSION['redirect-message'] ?? null;
    $response['redirect-message-type'] = $_SESSION['redirect-message-type'] ?? null;

    $_SESSION['redirect-message'] = null;
    $_SESSION['redirect-message-type'] = null;
}



echo json_encode($response);
exit;

?>