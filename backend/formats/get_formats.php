<?php

require __DIR__ . '/../../configuration/session.php';

header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    exit(json_encode(['success' => false, 'message' => 'Unauthorized']));
}


require_once  __DIR__ .  "/../../configuration/database.php";

$query = <<<EOT

SELECT id , name 
FROM book_formats
ORDER BY name;
EOT;

$result = $conn->query($query);

$formats = [];

if($result && $result->num_rows > 0)
{
    while($row = $result->fetch_assoc())
    {
        $formats[] = $row;
    }
}

echo json_encode($formats);
$conn->close();

?>