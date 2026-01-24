<?php

header('Content-Type: application/json');
require_once __DIR__ . '/../../../config/database.php';

$query = "SELECT COUNT(id) AS notification_count FROM admin_notifications WHERE is_read = 0";
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