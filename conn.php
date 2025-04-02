
<?php
try {
    // Database connection
    $host = "localhost";
    $dbname = "db_rcgi";
    $user = "root";
    $password = "";

    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "error is: " . $e->getMessage();
    die("db error");
}


?>

