<?php

header('Content-Type: application/json');

require_once __DIR__ . '/../../configuration/session.php';
require_once __DIR__ . '/../helpers.php';


if (!isset($_SESSION['admin_id']) && !isset($_SESSION['user_id'])) {
    respond(false , 401 , null , null , 'Unauthorized to use api');
}

if($_SERVER['REQUEST_METHOD'] === 'GET')
{
    require_once __DIR__ . '/../../configuration/database.php';
    require_once __DIR__ . '/validators/customer_validators.php';

    if (isset($_SESSION['user_id'])) {
        $id = $_SESSION['user_id'];
    } else {
        $id = $_GET['id'] ?? null;
    }

    $customer_id_validation = validate_entity_ID($id);
    if(!$customer_id_validation['valid'])
    {
        respond(false , 400 , null , null , $customer_id_validation['message']);
    }

    $query = <<<EOT
        SELECT
            u.address_id, 
            sd.first_name,
            sd.last_name,
            sd.email,
            sd.phone_number,
            sd.state,
            sd.city,
            sd.address_line1,
            sd.address_line2,
            sd.additional_notes,
            sd.admin_made
        FROM 
            users u
        JOIN shipping_addresses sd ON u.address_id = sd.id 
        WHERE 
            u.id = ? AND sd.is_active = 1
    EOT;

    if(isset($_SESSION['user_id']))
    {
        $query .= " AND sd.admin_made = 0";
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i" , $id);
    $stmt->execute();

    $result  = $stmt->get_result();

    $customer_addressess = [];

    if($result->num_rows > 0)
    {
        while($row = $result->fetch_assoc())
        {
            $customer_addressess[] = $row;
        }
    }


    respond(true , 200 , $customer_addressess , null , null);
}
else
{
    respond(false , 400 , null , null , 'Wrong method used in getting customer address');
}

?>