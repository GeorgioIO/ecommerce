<?php

require_once __DIR__ . '../../../configuration/session.php';


header("Content-Type: application/json");

$session_data = $_SESSION;
$cookie_data = [
    'username' => $_COOKIE['username'] ?? null
];

$_SESSION['redirect-message'] = null;
$_SESSION['redirect-message-type'] = null;

echo json_encode([
    'session' => $session_data,
    'cookie' => $cookie_data
]);
exit;

?>