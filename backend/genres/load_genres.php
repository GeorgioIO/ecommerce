<?php

require_once  __DIR__ .  "/../../config/database.php";

$query = <<<EOT

SELECT id , name , image
FROM genres
ORDER BY name;
EOT;

$result = $conn->query($query);

$genres = [];

if($result && $result->num_rows > 0)
{
    while($row = $result->fetch_assoc())
    {
        $genres[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($genres);
$conn->close();

?>