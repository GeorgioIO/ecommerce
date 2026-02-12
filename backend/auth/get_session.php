<?php

require_once __DIR__ . '../../../configuration/session.php';


header("Content-Type: application/json");

$response = $_SESSION;

$_SESSION['redirect-message'] = null;
$_SESSION['redirect-message-type'] = null;

echo json_encode($response);
exit;

?>