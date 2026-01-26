<?php

header('Content-Type: application/json');

require __DIR__ . '/../../config/session.php';

if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    exit(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

require_once  __DIR__ . '/../../config/database.php';

$hasPagination = isset($_GET['page']) && isset($_GET['perPage']);


$query = <<<EOT
SELECT
    u.id,
    u.customer_code,
    u.name,
    u.email,
    u.phone_number,
    u.date_added,
    u.role,
    COALESCE(SUM(o.total_price), 0) AS total_spent,
    COUNT(o.id) AS total_orders
FROM users u
LEFT JOIN orders o ON u.id = o.user_id
GROUP BY u.id
ORDER BY u.role

EOT;

$params = [];
$types = "";

if($hasPagination)
{
    $page = $_GET['page'] ?? 1;
    $perPage = $_GET['perPage'] ?? 10;

    $page = max(1 , (int) $page);
    $perPage = min(50 , max(5 , $perPage));
    $offset = ($page - 1) * $perPage;

    $query .= " LIMIT ? OFFSET ?";

    $params[] = $perPage;
    $params[] = $offset;
    $types .= "ii";
}

$stmt = $conn->prepare($query);

if($hasPagination)
{
    $stmt->bind_param("ii" , $perPage , $offset);
}

$stmt->execute();
$result = $stmt->get_result();

$customers = [];

if($result && $result->num_rows > 0)
{
    while($row = $result->fetch_assoc())
    {
        $customers[] = $row;
    }
}

$result = $conn->query("SELECT COUNT(*) AS total_customers FROM users");
$total_customers = $result->fetch_assoc()['total_customers'];


$pagination = $hasPagination ? [
    'page' => $page,
    'perPage' => $perPage,
    'total' => $total_customers,
    'totalPages' => ceil($total_customers / $perPage)
] : null;

$conn->close();
$stmt->close();

echo json_encode([
    'success' => true,
    'data' => $customers,
    'pagination' => $pagination
]);
exit;

?>