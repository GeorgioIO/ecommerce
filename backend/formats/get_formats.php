<?php

require_once __DIR__ . '/../helpers.php';

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] === 'GET')
{
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

    respond(true , 200 , $formats , null , null);
}
else
{
    respond(false , 400 , null , null , 'Wrong Method used');
}

?>