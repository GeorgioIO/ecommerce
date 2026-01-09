<?php

header('Content-Type: application/json');
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../validators/customer_validators.php';

$id = $_POST['id'] ?? null;


// Validate Customer ID
$customer_id_result = validate_customer_id($id);
if(!$customer_id_result['success'])
{
    echo json_encode([
        'success' => false,
        'message' => $customer_id_result['message']
    ]);
    exit;
}

$DB_customer_id = $customer_id_result['value'];

$query = <<<EOT
    SELECT 
        ua.is_default,
        sd.first_name AS "First Name",
        sd.last_name AS "Last Name",
        sd.email AS "Email",
        sd.phone_number AS "Phone Number",
        sd.state AS "State",
        sd.city AS "City",
        sd.address_line1 AS "Address Line 1",
        sd.address_line2 AS "Address Line 2",
        sd.additional_notes AS "Additional Notes"
    FROM 
        user_addresses ua
    JOIN 
        shipping_addresses sd ON ua.address_id = sd.id
    WHERE 
        ua.user_id = ?
EOT;

$stmt = $conn->prepare($query);
$stmt->bind_param("i" , $DB_customer_id);
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

$stmt->close();
$conn->close();

echo json_encode($customer_addressess);
exit;


?>