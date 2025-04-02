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

$sql = "
SELECT 
    at.*,  
    e.name,
    CASE 
        WHEN (HOUR(at.time_in) * 60 + MINUTE(at.time_in)) >= (HOUR(e.shift_start_time) * 60 + MINUTE(e.shift_start_time) + 30) 
        THEN 'Late'
        WHEN (HOUR(at.time_in) * 60 + MINUTE(at.time_in)) >= (HOUR(e.shift_start_time) * 60 + MINUTE(e.shift_start_time)) 
             AND (HOUR(at.time_in) * 60 + MINUTE(at.time_in)) < (HOUR(e.shift_start_time) * 60 + MINUTE(e.shift_start_time) + 30) 
        THEN 'On Time'
        ELSE 'Undertime'
    END AS status
FROM 
    attendance at  
JOIN 
    employee e ON at.employee_id = e.employee_id;";

$result = $conn->query($sql);





$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RCGI | VIEW ATTENDANCE</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="icon" href="pics/rcgiph_logo.jpg" type="image/x-icon">
    <style>
        body {
      font-family: 'Inter', sans-serif;
      font-weight: 600;
      font-size: 15px;
      background: #E2E2E2;
      margin: 0;
    }

    .navbar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: #5F7566;
      padding: 15px 30px;
      border-bottom: 2px solid #A5B7A5;
      box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.15);
      height: 70px;
    }

    .navbar .left {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 18px;
      font-weight: bold;
      color: white;
    }

    .navbar .left .brand-name {
      font-size: 22px;
      font-weight: 700;
    }

    .navbar .left .admin-label {
      font-size: 14px;
      font-style: italic;
      color: #DCE5D6;
    }

    .navbar .right {
      display: flex;
      align-items: center;
      gap: 20px;
    }

    .notification-icon {
      font-size: 20px;
      color: white;
      cursor: pointer;
      transition: transform 0.2s ease-in-out;
    }

    .notification-icon:hover {
      transform: scale(1.1);
    }

    .profile {
      width: 40px;
      height: 40px;
      background: #DCE5D6;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: background 0.3s ease-in-out;
    }

    .profile i {
      color: #5F7566;
      font-size: 18px;
    }

    .profile:hover {
      background: #A5B7A5;
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
      background-color: #5F7566;
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
        .card {
            border: 1px solid #DFDDDD;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.25);
            border-radius: 3px;
        }

        .col-md-4 h4 {
            margin-left: 30px;
            font-family: 'Inika', serif;
            font-weight: 400;
            font-size: 13px;
            line-height: 17px;
            color: #000000;
        }

        .col-md-4 p {
            margin-top: -10px;
            margin-left: 40px;
            font-family: 'Inika', serif;
            font-weight: 400;
            font-size: 36px;
            color: #000000;
        }

        .search-bar {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-bar .btn {
            background-color: #99A191;
            color: white;
        }

        .search-bar .btn i {
            margin-right: 5px;
        }

        .statusBtn {
        font-size: 0.85rem;
        font-weight: bold;
        padding: 4px 10px;
        border-radius: 20px;
        }

        .statusBtn.btn-success {
        background-color: #BCCFB9;
        border: none;
        }

        .statusBtn.btn-warning {
        background-color: #C29B99;
        border: none;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <div class="left">
      <i class="fas fa-building"></i>
      <span>RCGI WorkPulse</span>
      <span style="font-style:italic">for Admins</span>
    </div>
    <div class="right">
      <i class="fas fa-bell"></i>
      <div class="profile">
        <i class="fas fa-user"></i>
      </div>
    </div>
  </div>

<div class="container-fluid">
    <div class="row vh-100">
    <!-- Sidebar -->
    <div class="sidebar">
    <div class="list-group w-100">
      <a href="admin_dashboard.php" class="list-group-item list-group-item-action">
        <i class="fas fa-tachometer-alt sidebar-icon"></i> Dashboard
      </a>
      <a href="admin_view-attendance.php" class="list-group-item list-group-item-action active">
        <i class="fas fa-clock sidebar-icon"></i> View Attendance
      </a>
      <a href="admin_manage-employee.php" class="list-group-item list-group-item-action">
        <i class="fas fa-users sidebar-icon"></i> Manage Employees
      </a>
      <a href="admin_request-password.php" class="list-group-item list-group-item-action">
            <i class="fas fa-users sidebar-icon"></i> Request Password
          </a>
      <a href="admin_settings.php" class="list-group-item list-group-item-action">
        <i class="fas fa-cog sidebar-icon"></i> Settings
      </a>
      <a href="admin_logout.php" class="list-group-item list-group-item-action">
        <i class="fas fa-sign-out-alt sidebar-icon"></i> Logout
      </a>
    </div>

    <div class="w-100 text-center pb-3">
      <img src="pics/rcgiph_logo.jpg" class="img-fluid" alt="Logo" style="max-width: 50%; height: auto;">
    </div>
  </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2>Attendance Records</h2>
        <p class="text-muted mb-4">View and manage employee attendance records</p>

        <!-- Filters -->
        <div class="row g-3 align-items-end" style="margin-bottom: 10px;">
            <div class="col-md-3">
                <label><strong>Date Range</strong></label>
                <input type="date" class="form-control" value="2025-01-01" />
            </div>
            <div class="col-md-3">
                <label class="invisible">To</label>
                <input type="date" class="form-control" value="2025-12-31" />
            </div>
            <div class="col-md-3">
                <label><strong>Employee ID</strong></label>
                <select class="form-control">
                    <option selected>Select Employee ID</option>
                </select>
            </div>
            <div class="col-md-3">
                <label><strong>Search Employee</strong></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search by name..." />
                </div>
            </div>
        </div>

        <!-- Search & Export -->
        <div class="row mt-3 g-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search Record" />
                </div>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button class="btn btn-outline-dark">
                    <i class="fas fa-file-export"></i> Export CSV
                </button>
                <button class="btn btn-outline-dark">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table text-center order-table" style="width: 1200px; border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Date</th>
                                    <th>Time In</th>
                                    <th>Status</th>
                                    <th>Time Out</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                                if (isset($result) && $result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                echo"<tr>";
                                    echo "<td>" . htmlspecialchars($row['employee_ID']) . "</td>";
                                    echo "<td>". htmlspecialchars($row['name']) . "</td>";
                                    echo "<td>". htmlspecialchars($row['date']) . "</td>";
                                    echo "<td>". htmlspecialchars($row['time_in']) . "</td>";
                                    $statusClass = ($row['status'] === 'Late') ? 'btn-warning' : 'btn-success';
                                    echo '<td><button class="statusBtn btn ' . $statusClass . '">' . htmlspecialchars($row['status']) . '</button></td>';
                                    echo "<td>". htmlspecialchars($row['time_out']) . "</td>";
                                echo "</tr>";
                                    }
                                } else {
                                echo "<tr><td colspan='8'>No employees found.</td></tr>";
                                }
                            ?>    
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center px-3">
                            <span>Showing 1 to 3 of 3 results</span>
                            <div>
                                <button class="btn btn-light btn-sm">Previous</button>
                                <button class="btn btn-light btn-sm">Next</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>



</body>
</html>