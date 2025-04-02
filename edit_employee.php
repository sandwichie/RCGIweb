<?php
session_start();

// Make sure admin_ID is set in session
if (!isset($_SESSION['admin_ID'])) {
    echo "Unauthorized. Admin not logged in.";
    exit;
}
$admin_id = $_SESSION['admin_ID'];

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_rcgi";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $employee_id = intval($_GET['id']);
    $sql = "SELECT * FROM employee WHERE employee_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $employee = $result->fetch_assoc();
    } else {
        echo "Product not found.";
        exit;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $employee_id = $_POST['employee_ID'];
        $name = $_POST['name'];
        $startshift = $_POST['shift_start_time'];
        $endshift = $_POST['shift_end_time'];

        $sql = "UPDATE employee SET name = ?, shift_start_time = ? , shift_end_time = ? WHERE employee_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $name, $startshift, $endshift, $employee_id);


        if ($stmt->execute()) {
            echo "<script>alert('Employee updated successfully.'); window.location.href = 'manage-employee.php';</script>";
            exit;
        } else {
            echo "Error updating product: " . $conn->error;
        }
    
}


$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RCGI | SETTINGS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="icon" href="pics/rcgiph_logo.jpg" type="image/x-icon">
    <style>
       body {
      font-family: 'Inter', sans-serif;
      font-weight: 600;
      font-size: 15px;
      background: #F3F4F6;
      margin: 0;
    }

    .navbar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: white;
      padding: 15px 20px;
      border-bottom: 1px solid #DFDDDD;
      box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
      height: 60px;
    }

    .navbar .left {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 18px;
      font-weight: bold;
    }

    .navbar .right {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .navbar .profile {
      width: 35px;
      height: 35px;
      background: black;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
    }

    .layout {
      display: flex;
      height: calc(100vh - 60px); /* full height minus navbar */
    }

    .sidebar {
      background-color: #f8f9fa;
      width: 250px;
      border-right: 1px solid #ddd;
      padding-top: 20px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .list-group-item {
      background: none;
      border: none;
      padding: 12px 20px;
      font-weight: bold;
      display: flex;
      align-items: center;
      color: #000;
      transition: background 0.3s ease-in-out;
    }

    .list-group-item:hover {
      background: #e0e0e0;
    }

    .list-group-item.active {
      background-color: #99A191;
      color: white;
      border-radius: 5px;
    }

    .sidebar-icon {
      width: 20px;
      height: 20px;
      margin-right: 10px;
    }

    .main-content {
      flex: 1;
      padding: 20px;
      overflow-y: auto;
    }

        /* Settings Container */
        .employee-form {
            max-width: 600px;
            background: #FFFFFF;
            border: 2px solid #7A8D7A;
            box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
            border-radius: 20px;
            padding: 30px;
            margin: auto;
            margin-top: 50px;
        }

        .employee-form label {
            font-weight: bold;
            margin-top: 10px;
        }

        .employee-form input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .employee-form .input-container {
            display: flex;
            align-items: center;
        }

        .employee-form .input-container input {
            width: 80px;
            text-align: center;
            margin-right: 10px;
        }

        .employee-form .small-text {
            font-size: 12px;
            font-style: italic;
            color: #666;
            margin-top: 5px;
        }

        .employee-form .save-button {
            width: 100%;
            padding: 10px;
            background-color: #7A8D7A;
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 15px;
        }

        .employee-form .save-button:hover {
            background-color: #5f6e5f;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 10px;
            }
            .sidebar {
                width: 100%;
                position: relative;
                height: auto;
                padding-bottom: 10px;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <div class="left">
        <i class="fas fa-cog"></i>
        <span>Edit Employee</span>
    </div>
    <div class="right">
        <i class="fas fa-bell"></i>
        <div class="profile">
            <i class="fas fa-user"></i>
        </div>
    </div>
</div>

  <!-- Layout -->
<div class="container-fluid">
    <div class="row vh-100">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="list-group">
                <a href="dashboard.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-tachometer-alt sidebar-icon"></i> Dashboard
                </a>
                <a href="view-attendance.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-clock sidebar-icon"></i> View Attendance
                </a>
                <a href="manage-employee.php" class="list-group-item list-group-item-action active">
                    <i class="fas fa-users sidebar-icon"></i> Manage Employees
                </a>
                <a href="settings.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-cog sidebar-icon"></i> Settings
                </a>
                <a href="logoutpage.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-sign-out-alt sidebar-icon"></i> Logout
                </a>
            </div>
            <div class="w-100 text-center pb-3">
            <img src="pics/rcgiph_logo.jpg" class="img-fluid" alt="Logo" style="max-width: 50%; height: auto;">
            </div>
        </div>

    <div class="employee-form">
    <form action="" method="post" enctype="multipart/form-data" class="mt-4">
    <input type="hidden" name="employee_ID" value="<?= htmlspecialchars($employee['employee_ID']) ?>">
        <label for="Name">Full Name</label>
        <input type="text" placeholder="Enter name" name="name" autocomplete="off" value="<?= htmlspecialchars($employee['name']) ?>"><br>

        <label class="form-label">Start Shift</label>
            <select class="form-select" name="shift_start_time">
                <option selected>Select Shift</option>
                <option>8:00 AM</option>
                <option>9:00 AM</option>
            </select>

        <label class="form-label">End Shift</label>
            <select class="form-select" name="shift_end_time">
                <option selected>Select Shift</option>
                <option>5:00 PM</option>
                <option>6:00 PM</option>
            </select>
            <button type="submit" name="save" class="save-button">Save Changes</button>
    </form>
    </div>
</div>
    </div>


</body>
</html>
