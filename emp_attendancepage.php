<?php
session_start();

// Make sure admin_ID is set in session
if (!isset($_SESSION['employee_ID'])) {
    echo "Unauthorized. Employee not logged in.";
    exit;
}
$employee_id = $_SESSION['employee_ID'];

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


// Prepare and bind statement
$stmt = $conn->prepare("SELECT * FROM employee WHERE employee_ID = ?");
$stmt->bind_param("i", $employee_id); 
$stmt->execute();

// Fetch result
$result = $stmt->get_result();
$employee = $result->fetch_assoc();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RCGI | ATTENDANCE</title>
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
      background: #E4C28B;;
      padding: 15px 20px;
      border-bottom: 1px solid #E4C28B;;
      box-shadow: 0 4px 4px rgba(0, 0, 0, 0.1);
    }

    .navbar .left {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 18px;
      font-weight: bold;
    }
    
    .navbar .username {
      font-weight: 600;
      margin-right: 10px;
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
      color: #000000;
      transition: background 0.3s ease-in-out;
    }

    .list-group-item:hover {
      background: #e0e0e0;
    }

    .list-group-item.active {
      background-color: #E4C28B;;
      color: #000;
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
            background-color: #374151;
            color: white;
            font-weight: bold;
        }

        .search-bar .btn i {
            margin-right: 5px;
        }

        .section-title {
      font-size: 20px;
      font-weight: 600;
      margin-bottom: 20px;
    }

    .card {
      border: none;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

        footer {
      text-align: center;
      padding: 20px;
      font-size: 14px;
      color: #6c757d;
      background: #f8f9fa;
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
<nav class="navbar d-flex justify-content-between align-items-center">
<div class="left">
    <i class="fas fa-users"></i> 
    <span>MY ATTENDANCE</span>
</div>
    <div class="d-flex align-items-center"><span class="username"><?php echo htmlspecialchars($employee['name']); ?></span>
      
      <img src="<?= htmlspecialchars($employee['photo']) ?>" alt="employee photo" style="width: 50px; height:45px;  border: 2px solid ; border-radius: 50%; ">
    </div>
  </nav>

<div class="container-fluid">
    <div class="row vh-100">
    <!-- Sidebar -->
    <div class="col-2 sidebar">
        <div class="list-group">
            <a href="#" class="list-group-item list-group-item-action active">
                <i class="fas fa-users sidebar-icon"></i> My Attendance
            </a>
            <a href="emp_logout.php" class="list-group-item list-group-item-action">
                <i class="fas fa-sign-out-alt sidebar-icon"></i> Logout
            </a>
        </div>
        <div class="w-100 text-center pb-3">
          <img src="pics/rcgiph_logo.jpg" class="img-fluid" alt="Logo" style="max-width: 50%; height: auto;">
        </div>
        
    </div>

    <div class="main-content">
      <!-- Employee Info and Stats Card -->
      <div class="card p-4 mb-4 d-flex flex-md-row flex-column align-items-center justify-content-between gap-4" style= "background: #E5E0D8">
          <div class="d-flex align-items-center gap-3">
              <img src="<?= htmlspecialchars($employee['photo']) ?>" alt="Profile" style="width: 90px; height: 90px; border-radius: 50%; border: 2px solid #ccc;">
              <div>
                  <h4 class="mb-1"><?= htmlspecialchars($employee['name']) ?></h4>
                  <p class="mb-0 small text-muted"><?= htmlspecialchars($employee['employee_ID']) ?></p>
                  <p class="mb-0 small"><?= htmlspecialchars($employee['org']) ?></p>
              </div>
          </div>
          <div class="d-flex flex-wrap gap-3 justify-content-center">
              <div class="px-3 py-2 rounded" style="background-color: #E4C28B;">
                  <strong>32h 30m 0s</strong><br><small>Total Hours Worked</small>
              </div>
              <div class="px-3 py-2 rounded" style="background-color: #D9D9D9;">
                  <strong>6h</strong><br><small>Extra Hours Worked</small>
              </div>
              <div class="px-3 py-2 rounded" style="background-color: #BCCFB9;">
                  <strong>08:56 AM</strong><br><small>Average Check In Time</small>
              </div>
              <div class="px-3 py-2 rounded" style="background-color: #C29B99;">
                  <strong>2 Lates</strong><br><small>Number of Lates</small>
              </div>
          </div>
      </div>

      <!--search field-->
      <div class="card p-4 mb-4">
        <form class="row g-3 align-items-end">
          <div class="col-md-4">
          <label for="startDate" class="form-label"><strong>Start Date</strong></label>
            <input type="date" id="startDate" class="form-control" />
          </div>
          <div class="col-md-4">
          <label for="endDate" class="form-label"><strong>End Date</strong></label>
            <input type="date" id="endDate" class="form-control" />
          </div>
          <div class="col-md-4">
          <button type="button" class="btn btn-primary btn-sm">Search</button>
            </div>
          <div>
        </form>
      </div>

      <!-- Attendance Table -->
      <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="mb-0">Attendance Records</h5>
          <button class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-download me-1"></i> Download Report
          </button>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered text-center align-middle mb-3">
            <thead class="table-light">
              <tr>
                <th>Date</th>
                <th>Time In</th>
                <th>Time Out</th>
                <th>Time In</th>
                <th>Time Out</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Jan 15, 2025</td>
                <td>08:55 AM</td>
                <td>12:00 PM</td>
                <td>13:00 PM</td>
                <td>17:00 PM</td>
                <td><span class="btn statusBtn">On-Time</span></td>
              </tr>
              <tr>
                <td>Jan 16, 2025</td>
                <td>09:10 AM</td>
                <td>12:00 PM</td>
                <td>13:00 PM</td>
                <td>05:45 PM</td>
                <td><span class="btn statusBtn">Late</span></td>
              </tr>
              <tr>
                <td>Jan 17, 2025</td>
                <td>08:45 AM</td>
                <td>12:00 PM</td>
                <td>13:00 PM</td>
                <td>05:15 PM</td>
                <td><span class="btn statusBtn">On-Time</span></td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination + Footer text -->
        <div class="d-flex justify-content-between align-items-center">
          <span>Showing 1 to 3 of 3 entries</span>
          <div>
            <button class="btn btn-light btn-sm me-2">Previous</button>
            <button class="btn btn-light btn-sm">Next</button>
          </div>
        </div>
      </div>
    </div>
</div>
  <!-- Footer -->
  <footer>
    © 2025 Attendance System. All rights reserved.
  </footer>

</div>

</body>
</html>
