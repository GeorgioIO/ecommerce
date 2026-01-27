<?php

require_once  __DIR__ . '/../../../configuration/database.php';
require_once  __DIR__ . '/../../helpers.php';

function DB_validate_genre_has_books($conn , $id)
{
    if($id)
    {
        $query = "SELECT id FROM books WHERE genre_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i" , $id);

        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows > 0)
        {
            return 
            [
                'success' => false,
                'message' => 'Child row exists for the current genre , Deletion is blocked.'
            ];
        }
    }
    else
    {
        return [
            'success' => false,
            'message' => 'Invalid Genre ID'
        ];
    }

    return [
            'success' => true
        ];
}

function DB_validate_genre_name($conn , $name , $id=null)
{
    
    // When we are adding genre we wanna check if there is a genre with this name at all
    if($id !== null)
    {
        $query = "SELECT id FROM genres WHERE name = ? AND id <> ? LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si" , $name , $id);
    }
    // When we are updating a genre we wanna check if there is a genre with this name other than the genre with the id of the genre we are updating
    else
    {
        $query = "SELECT id FROM genres WHERE name = ?  LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s" , $name);
    }

    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0)
    {
        return [
            'success' => false,
            'message' => 'A genre with this Name already exists'
        ];
    }

    return [
        'success' => true
    ];
}