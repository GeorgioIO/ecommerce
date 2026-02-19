<?php

header('Content-Type: application/json');

require __DIR__ . '/../../configuration/session.php';

if (!isset($_SESSION['admin_id']) && !isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'status' => 401,
        'message' => 'Unauthorized'
    ]);
    exit;
}

require_once  __DIR__ . '/../../configuration/database.php';
require_once  __DIR__ . '/validators/customer_validators.php';

if(!isset($_POST["id"]))
{
    $id = $_SESSION['user_id'];
}
else
{
    $id = $_POST["id"];
}

// validate author id
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
    u.id,
    u.customer_code,
    u.name,
    u.email,
    u.phone_number,
    u.date_added,
    u.password,
    COALESCE(SUM(o.total_price), 0) AS total_spent,
    COUNT(o.id) AS total_orders
FROM users u
LEFT JOIN orders o ON u.id = o.user_id
WHERE u.id = ?
GROUP BY u.id
EOT;

$stmt = $conn->prepare($query);
$stmt->bind_param("i" , $DB_customer_id);
$stmt->execute();

$result = $stmt->get_result();

// id isnt found in database
if($result->num_rows === 0){
    echo json_encode([
        'success' => false, 
        'data' => 'Customer not found'
    ]);
    exit;
}

$customer = $result->fetch_assoc();

$stmt->close();
$conn->close();

echo json_encode([
    'success' => true,
    'data' => $customer
]);
exit;

?>
