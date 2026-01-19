<?php

require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/helpers.php';

$admin_email = $_POST['email'];
$admin_password = $_POST['password'];

$query = <<<EOT
    SELECT 
        id,
        role,
        name,
        email,
        password
    FROM 
        users
    WHERE 
        email = ?
EOT;

$admin_email_result = validate_email($admin_email);
if(!$admin_email_result['valid'])
{
    echo json_encode([
        'success' => false,
        'message' => $admin_email_result['message']
    ]);
    exit;
}

$DB_admin_email = $admin_email_result['value'];

$stmt = $conn->prepare($query);
$stmt->bind_param("s" , $DB_admin_email);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows === 1)
{
    $user = $result->fetch_assoc();

    if(password_verify($admin_password , $user['password']))
    {
        // User is not an admin
        if($user['role'] !== 'Admin')
        {
            echo json_encode([
                'success' => false,
                'message' => 'User is not an admin'
            ]);
            exit;
        }

        // User is an admin

        // Session values are created here this is very important
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_email'] = $user['email'];
        $_SESSION['admin_name'] = $user['name'];

        echo json_encode([
            'success' => true,
            'message' => 'Login succesful'
        ]);
        exit;
    }
    else
    {
        echo json_encode([
            'success' => false,
            'message' => 'Password is incorrect.'
        ]);
        exit;
    }
}
else
{
    echo json_encode([
        'success' => false,
        'message' => 'User not found'
    ]);
    exit;
}

?>