<?php

require_once  __DIR__ . '/../../config/database.php';
require_once  __DIR__ . '/../../config/helpers.php';

function DB_validate_author_name($conn , $name , $id=null)
{
    
    if($id !== null)
    {
        $query = "SELECT id FROM authors WHERE name = ? AND id <> ? LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si" , $name , $id);
    }
    else
    {
        $query = "SELECT id FROM authors WHERE name = ?  LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s" , $name);
    }

    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0)
    {
        return [
            'success' => false,
            'message' => 'A author with this Name already exists'
        ];
    }

    return [
        'success' => true
    ];
}