<?php

header('Content-Type: application/json');
require_once __DIR__ . '/../../config/database.php';

$query = <<<EOT

SELECT id, name 
FROM authors
ORDER BY name;
EOT;

$result = $conn->query($query);

$authors = [];

if($result && $result->num_rows > 0)
{
    while($row = $result->fetch_assoc())
    {
        $authors[] = $row;
    }
}

echo json_encode($authors);
$conn->close();

?>