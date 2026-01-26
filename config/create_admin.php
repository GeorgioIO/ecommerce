<?php 



// Temporary file used to create an admin

require_once __DIR__ . '/database.php';

$query = "INSERT INTO users (role , customer_code, name, email, phone_number , password)
VALUES (? , ? , ? , ? , ? , ?)
";

$stmt = $conn->prepare($query);

$DB_hashed_password = password_hash("12345" , PASSWORD_DEFAULT);
$role = "Admin";
$customer_code = "C1";
$name = "Georgio AD";
$email = "georgiojabbour.g.gj@gmail.com";
$phone = "+9617186123";

$stmt->bind_param(
    "ssssss",
    $role,
    $customer_code,
    $name,
    $email,
    $phone,
    $DB_hashed_password
);
$stmt->execute();

$stmt->close();
$conn->close();

echo "admin created";

?>