<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "db_rcgi");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete employee
if (isset($_GET['id'])) {
    $employee_id = intval($_GET['id']); 

    $sql = "DELETE FROM employee WHERE employee_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $employee_id); 
    if ($stmt->execute()) {
        header("Location: admin_manage-employee.php");
        exit;
    } else {
        echo "Error deleting employee: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
?>
