<?php

header('Content-Type: application/json');

require_once __DIR__ . '/../../../config/database.php';

$result = $conn->query("

    SELECT 
        id,
        type,
        title,
        message,
        entity,
        entity_id,
        is_read,
        created_at
    FROM
        admin_notifications
    ORDER BY is_read ASC , created_at DESC
    LIMIT 10; 

");


$admin_notifications = [];

if($result)
{
    while($row = $result->fetch_assoc())
    {
        $admin_notifications[] = $row;
    }
}

$conn->close();

echo json_encode($admin_notifications);
exit;


?>