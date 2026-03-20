<?php

if($_SERVER['REQUEST_METHOD'] === "GET")
{
    header('Content-Type: application/json');
    require_once  __DIR__ .  "/../../configuration/database.php";
    require_once __DIR__ . "/../helpers.php";

    $hasPagination = isset($_GET['page']) && isset($_GET['perPage']);
    $paginationText = $hasPagination ? " LIMIT ? OFFSET ?" : "";
    $is_deleted = $_GET['is_deleted'] ?? 0;

    $query = <<<EOT
        SELECT 
            id, 
            name, 
            image, 
            is_deleted
        FROM 
            genres
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

    $genres = [];
    if($result && $result->num_rows > 0)
    {
        while($row = $result->fetch_assoc())
        {
            $genres[] = $row;
        }
    }

    $count_query = "SELECT COUNT(*) AS total_genres FROM genres WHERE is_deleted = ?";
    $count_stmt = $conn->prepare($count_query);
    $count_stmt->bind_param("i" , $is_deleted);
    $count_stmt->execute();
    $result = $count_stmt->get_result();
    $total_genres = $result->fetch_assoc()['total_genres'];

    $pagination = $hasPagination ? [
        'page' => $page,
        'perPage' => $perPage,
        'total' => $total_genres,
        'totalPages' => ceil($total_genres / $perPage)
    ] : null;

    $stmt->close();
    $count_stmt->close();
    $conn->close();

    respond(true , 200 , $genres , $pagination , null);
}
else
{
    respond(false , 400 , null , null , 'Wrong method used in getting genres');
}

?>