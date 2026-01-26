<?php

header('Content-Type: application/json');

session_start();

if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    exit(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/validators/customer_validators.php';

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
        ua.address_id, 
        ua.is_default,
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