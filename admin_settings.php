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

if (isset($_POST['changePass'])) {
    $current_password = $_POST['current-password'];
    $new_password = $_POST['new-password'];
    $confirm_password = $_POST['confirm-password'];

    // Fetch current hashed password from database
    $sql = "SELECT password FROM admin WHERE admin_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $stmt->bind_result($db_password);
    $stmt->fetch();
    $stmt->close();

    // Verify the current password
    if (!password_verify($current_password, $db_password)) {
        echo "<script>alert('Current password is incorrect.');</script>";
    } elseif ($new_password !== $confirm_password) {
        echo "<script>alert('New passwords do not match.');</script>";
    } else {
        // Hash the new password before storing
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $sql = "UPDATE admin SET password = ? WHERE admin_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $hashed_password, $admin_id);

        if ($stmt->execute()) {
            echo "<script>alert('Password changed successfully.'); window.location.href='admin_dashboard.php';</script>";
        } else {
            echo "<script>alert('Error changing password.');</script>";
        }
        $stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>RCGI | SETTINGS</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="icon" href="pics/rcgiph_logo.png" type="image/x-icon">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      font-weight: 600;
      font-size: 15px;
      background: #F1F1F1;
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

    .settings-container {
      max-width: 600px;
      background: #FFFFFF;
      border: 2px solid #7A8D7A;
      box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
      border-radius: 20px;
      padding: 30px;
      margin: auto;
      margin-top: 50px;
    }

    .settings-container label {
      font-weight: bold;
      margin-top: 10px;
    }

    .settings-container input {
      width: 100%;
      padding: 8px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .settings-container .input-container {
      display: flex;
      align-items: center;
    }

    .settings-container .input-container input {
      width: 80px;
      text-align: center;
      margin-right: 10px;
    }

    .settings-container .small-text {
      font-size: 12px;
      font-style: italic;
      color: #666;
      margin-top: 5px;
    }

    .settings-container .submit {
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

    .settings-container .submit:hover {
      background-color: #5f6e5f;
    }

    .addadmin-container .submit {
      width: 20%;
      padding: 10px;
      background-color: #7A8D7A;
      color: white;
      border: none;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
      margin-top: 15px;
    }

    .addadmin-container .submit:hover {
      background-color: #5f6e5f;
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

<!-- Layout: Sidebar + Content -->
<div class="container-fluid">
    <div class="row vh-100">
  <!-- Sidebar -->
  <div class="sidebar">
    <div class="list-group">
      <a href="admin_dashboard.php" class="list-group-item list-group-item-action">
        <i class="fas fa-tachometer-alt sidebar-icon"></i> Dashboard
      </a>
      <a href="admin_view-attendance.php" class="list-group-item list-group-item-action">
        <i class="fas fa-clock sidebar-icon"></i> View Attendance
      </a>
      <a href="admin_manage-employee.php" class="list-group-item list-group-item-action">
        <i class="fas fa-users sidebar-icon"></i> Manage Employees
      </a>
      <a href="admin_request-password.php" class="list-group-item list-group-item-action">
            <i class="fas fa-users sidebar-icon"></i> Request Password
          </a>
      <a href="admin_settings.php" class="list-group-item list-group-item-action active">
        <i class="fas fa-cog sidebar-icon"></i> Settings
      </a>
      <a href="admin_logout.php" class="list-group-item list-group-item-action">
        <i class="fas fa-sign-out-alt sidebar-icon"></i> Logout
      </a>
    </div>
    <div class="w-100 text-center pb-3">
      <img src="pics/rcgiph_logo.png" class="img-fluid" alt="Logo" style="max-width: 50%; height: auto;">
    </div>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="row justify-content-start">
      <!-- Left Settings -->
      <div class="col-md-6">
        <div class="settings-container">
          <label for="companyname">Company Name</label>
          <input type="text" id="companyname" placeholder="Your Company Name" required />

          <label for="timezone">Time Zone</label>
          <input type="text" id="timezone" placeholder="(GMT-08:00) Pacific Time" required />

          <label for="threshold">Late Threshold</label>
          <div class="input-container">
            <input type="number" id="threshold" value="15" required /> Minutes
          </div>
          <p class="small-text">Specify how many minutes after scheduled time should be considered late</p>

          <button type="submit" class="submit">Save Changes</button>
        </div>

        <div class="text-start mt-3">
          <button class="submit" onclick="window.location.href='admin_registration.php';">
            Add New Admin?
          </button>
        </div>
      </div>

      <!-- Right Settings -->
      <div class="col-md-6">
        <div class="settings-container">
          <form method="POST" action="">
            <h4>Change Admin Password</h4>

            <label for="current-password">Current Password</label>
            <input type="password" name="current-password" id="current-password" placeholder="Enter current password" required />

            <label for="new-password">New Password</label>
            <input type="password" name="new-password" id="new-password" placeholder="Enter new password" required />

            <label for="confirm-password">Confirm New Password</label>
            <input type="password" name="confirm-password" id="confirm-password" placeholder="Confirm new password" required />

            <button class="submit" name="changePass">Change Password</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

</body>
</html>
