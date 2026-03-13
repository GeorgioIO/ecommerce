<?php

header('Content-Type: application/json');

require __DIR__ . '/../../configuration/session.php';
require_once __DIR__ . '/../../configuration/database.php';

$hasPagination = isset($_GET['page']) && isset($_GET['perPage']);
$paginationText = $hasPagination ? " LIMIT ? OFFSET ?" : "";
$is_deleted = $_GET['is_deleted'] ?? 0;

$query = <<<EOT
    SELECT 
        id, 
        name, 
        is_deleted
    FROM 
        authors
    WHERE 
        is_deleted = ?
    ORDER BY name
    $paginationText
EOT;

$params = [$is_deleted];
$types = "i";

if($hasPagination)
{
    $page = $_GET['page'] ?? 1;
    $perPage = $_GET['perPage'] ?? 10;

    $page = max(1 , (int) $page);
    $perPage = min(50 , max(5 , $perPage));
    $offset = ($page - 1) * $perPage;

    $params[] = $perPage;
    $params[] = $offset;
    $types .= "ii";
}

$stmt = $conn->prepare($query);
$stmt->bind_param($types , ...$params);
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

$count_query = "SELECT COUNT(*) AS total_authors FROM authors WHERE is_deleted = ?";
$count_stmt = $conn->prepare($count_query);
$count_stmt->bind_param("i" , $is_deleted); 
$count_stmt->execute();
$result = $count_stmt->get_result();
$total_authors = $result->fetch_assoc()['total_authors'];

$pagination = $hasPagination ? [
    'page' => $page,
    'perPage' => $perPage,
    'total' => $total_authors,
    'totalPages' => ceil($total_authors / $perPage)
] : null;

$count_stmt->close();
$stmt->close();
$conn->close();

echo json_encode([
    'success' => true,
    'status' => 200,
    'data' => $authors,
    'pagination' => $pagination
]);
exit;

?>