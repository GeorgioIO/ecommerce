<?php

require_once __DIR__ . '/../../configuration/session.php';
require_once __DIR__ . '/../../configuration/database.php';


if(!empty($_COOKIE['remember-me']))
{
    $raw_token = $_COOKIE['remember-me'];

    $query = "SELECT id, token_hash FROM remember_tokens";
    $result = $conn->query($query);

    while($row = $result->fetch_assoc())
    {
        if(password_verify($raw_token , $row['token_hash']))
        {
            $delete = $conn->prepare("DELETE FROM remember_tokens WHERE id = ?");
            $delete->bind_param("i" , $row['id']);
            $delete->execute();
            $delete->close();
            break;
        }
    }
}

setcookie("remember_me" , "", time() - 3600 , "/" , "" , true , true);
setcookie("username" , "" , time() - 3600 , "/" , "" , true , true);

$_SESSION = [];
session_unset();
session_destroy();

header("Location: /ecommerce/frontend/storefront/pages/my-account.php");
exit;

?>