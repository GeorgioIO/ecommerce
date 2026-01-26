<?php

header('Content-Type: application/json');

require __DIR__ . '/../../config/session.php';

if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    exit(json_encode(['success' => false, 'message' => 'Unauthorized']));
}


require_once __DIR__ . '/../../config/database.php';

$hasPagination = isset($_GET['page']) && isset($_GET['perPage']);


$query = <<<EOT

SELECT id, name 
FROM authors
ORDER BY name
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

$authors = [];

if($result && $result->num_rows > 0)
{
    while($row = $result->fetch_assoc())
    {
        $authors[] = $row;
    }
}

$result = $conn->query("SELECT COUNT(*) AS total_authors FROM authors");
$total_authors = $result->fetch_assoc()['total_authors'];

$pagination = $hasPagination ? [
    'page' => $page,
    'perPage' => $perPage,
    'total' => $total_authors,
    'totalPages' => ceil($total_authors / $perPage)
] : null;

$conn->close();
$stmt->close();


echo json_encode([
    'success' => true,
    'data' => $authors,
    'pagination' => $pagination
]);
exit;

?>