<?php


function insert_admin_notification($conn , $type , $title , $message , $entity , $entity_id)
{
    $query = <<<EOT

        INSERT INTO admin_notifications 
        (type , title , message , entity , entity_id)
        VALUES
        (? , ? , ? , ? , ?)

    EOT;

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi" , $type , $title , $message , $entity , $entity_id);
    $stmt->execute();
    $stmt->close();
}

?>