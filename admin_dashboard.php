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
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>RCGI | DASHBOARD</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link rel="icon" href="pics/rcgiph_logo.jpg" type="image/jpg" />
  <style>
    body {
      font-family: 'Inter', sans-serif;
      font-weight: 600;
      font-size: 15px;
      margin: 0;
      background: #E2E2E2;
      position: relative;
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
      height: calc(100vh - 60px);
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
      font-family: 'Inter', sans-serif;
      font-weight: 400;
      font-size: 13px;
      line-height: 17px;
      color: #000000;
    }

    .col-md-4 p {
      margin-top: -10px;
      margin-left: 40px;
      font-family: 'Inter', sans-serif;
      font-weight: 400;
      font-size: 36px;
      color: #000000;
    }

    .activity-item {
      display: block;
      text-align: left;
      padding: 10px;
      border: none;
      background: none;
      width: 100%;
    }

    @media (max-width: 768px) {
      .layout {
        flex-direction: column;
      }

      .sidebar {
        width: 100%;
        border-right: none;
        border-bottom: 1px solid #ddd;
      }

      .main-content {
        padding: 10px;
      }
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
      <a href="admin_dashboard.php" class="list-group-item list-group-item-action active">
        <i class="fas fa-tachometer-alt sidebar-icon"></i> Dashboard
      </a>
      <a href="admin_view-attendance.php" class="list-group-item list-group-item-action">
        <i class="fas fa-clock sidebar-icon"></i> View Attendance
      </a>
      <a href="admin_manage-employee.php" class="list-group-item list-group-item-action">
        <i class="fas fa-users sidebar-icon"></i> Manage Employees
      </a>
      <a href="admin_request-password.php" class="list-group-item list-group-item-action ">
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
    <div class="row">
      <div class="col-md-4">
        <div class="card p-3">
          <h4>Total Employees</h4>
          <p>156 <i class="fas fa-users"></i></p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-3">
          <h4>Present Today</h4>
          <p>142 <i class="fas fa-user-check"></i></p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-3">
          <h4>On Leave</h4>
          <p>14 <i class="fas fa-user-times"></i></p>
        </div>
      </div>
    </div>

    <!-- Recent Activities -->
    <div class="row mt-4">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header text-white" style="background-color: #5F7566;">
            <h5 class="mb-0">Recent Activities</h5>
          </div>
          <div class="card-body">
            <ul class="list-group">
              <li class="list-group-item">
                <button class="activity-item">
                  <i class="fas fa-user"></i> <span>Employee #123 checked in</span>
                  <p class="text-muted ms-4 mb-0">Today at 8:00 AM</p>
                </button>
              </li>
              <li class="list-group-item">
                <button class="activity-item">
                  <i class="fas fa-user"></i> <span>Employee #456 checked out</span>
                  <p class="text-muted ms-4 mb-0">Today at 5:00 PM</p>
                </button>
              </li>
              <li class="list-group-item">
                <button class="activity-item">
                  <i class="fas fa-user"></i> <span>Employee #789 requested leave</span>
                  <p class="text-muted ms-4 mb-0">Today at 2:00 PM</p>
                </button>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div> <!-- End Main Content -->
  </div>
</div> <!-- End Layout -->

</body>
</html>
