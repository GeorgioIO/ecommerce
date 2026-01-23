<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(null);
    exit;
}

echo json_encode([
    'id' => $_SESSION['admin_id'],
    'email' => $_SESSION['admin_email'],
    'name' => $_SESSION['admin_name']
]);

?>