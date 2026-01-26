<?php

session_start();

header('Content-Type: application/json');


if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    exit(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

require_once __DIR__ . '/../../../config/database.php';

$query = "SELECT COUNT(*) AS notification_count FROM admin_notifications WHERE is_read = 0";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$notification_count = (int) $row['notification_count'];

$stmt->close();
$conn->close();

echo json_encode([
    'value' => $notification_count
]);
exit;

?>