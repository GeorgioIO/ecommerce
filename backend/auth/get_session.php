<?php
require_once __DIR__ . '../../../configuration/session.php';

header("Content-Type: application/json");

// Copy the session flash messages into variables
$redirect_message = $_SESSION['redirect_message'] ?? null;
$redirect_message_type = $_SESSION['redirect_message_type'] ?? null;

// Prepare the data to send to JS
$session_data = [
    'redirect_message' => $redirect_message,
    'redirect_message_type' => $redirect_message_type,
    'user_id' => $_SESSION['user_id'] ?? null,
    'user_email' => $_SESSION['user_email'] ?? null,
    'user_name' => $_SESSION['user_name'] ?? null
];

$cookie_data = [
    'username' => $_COOKIE['username'] ?? null
];

if($redirect_message != null)
{
    unset($_SESSION['redirect_message'] , $_SESSION['redirect_message_type']);
}

// Send JSON to JS
echo json_encode([
    'session' => $session_data,
    'cookie' => $cookie_data
]);
exit;