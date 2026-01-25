<?php

header('Content-Type: application/json');
require_once  __DIR__ .  "/../../config/database.php";

$hasPagination = isset($_GET['page']) && isset($_GET['perPage']);



$query = <<<EOT

SELECT id , name , image
FROM genres
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

$genres = [];

if($result && $result->num_rows > 0)
{
    while($row = $result->fetch_assoc())
    {
        $genres[] = $row;
    }
}

$result = $conn->query("SELECT COUNT(*) AS total_genres FROM genres");
$total_genres = $result->fetch_assoc()['total_genres'];

$pagination = $hasPagination ? [
    'page' => $page,
    'perPage' => $perPage,
    'total' => $total_genres,
    'totalPages' => ceil($total_genres / $perPage)
] : null;

$conn->close();
$stmt->close();

echo json_encode([
    'success' => true,
    'data' => $genres,
    'pagination' => $pagination
]);
exit;

?>