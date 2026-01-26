<?php

session_start();

header('Content-Type: application/json');


if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    exit(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

require_once __DIR__ . '/../../../config/database.php';


$query = "UPDATE admin_notifications SET is_read = 1 WHERE is_read = 0";
$conn->query($query);

if($conn->affected_rows === 0)
{
    echo json_encode([
        'success' => false,
        'message' => 'No notifications available'
    ]);
}
else
{
    echo json_encode([
        'success' => true,
    ]);
    
}

$conn->close();
exit;

?>