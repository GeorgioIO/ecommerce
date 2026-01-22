<?php

require_once  __DIR__ .  "/../../config/database.php";

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

header('Content-Type: application/json');
echo json_encode($formats);
$conn->close();

?>