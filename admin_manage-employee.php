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

if ($_SERVER['REQUEST_METHOD']  == "POST" && isset($_POST['submit'])) {
  $employee_id = $_POST['employee_ID'];
  $name = $_POST['name'];
  $fingerprint_id = $_POST['fingerprint_ID'];
  $startshift = $_POST['shift_start_time'];
  $endshift = $_POST['shift_end_time'];
  $hiredate = $_POST['hire_date'];
  $org = $_POST['org'];

  // Check if an image is uploaded
  $employee_image = null;
  if (isset($_FILES['employee_image']) && $_FILES['employee_image']['error'] === UPLOAD_ERR_OK) {
    $target_dir = "employee_pfp/";
    $file_name = basename($_FILES['employee_image']['name']);
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif'])) {
      $unique_name = uniqid() . "_" . $file_name;
      $target_file = $target_dir . $unique_name;

      if (move_uploaded_file($_FILES['employee_image']['tmp_name'], $target_file)) {
        $employee_image = $target_file;
      } else {
        echo "<script>alert('Error uploading the file.');</script>";
        exit;
      }
    } else {
      echo "<script>alert('Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.');</script>";
      exit;
    }
  } else {
    echo "<script>alert('No image uploaded or upload error.');</script>";
    exit;
  }

  $conn->begin_transaction();
  try {
    // Updated SQL to include admin_ID
    $stmt = $conn->prepare("INSERT INTO employee (admin_ID, employee_id, name, fingerprint_id, photo, hire_date, shift_start_time, shift_end_time, org) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisisssss", $admin_id, $employee_id, $name, $fingerprint_id, $employee_image, $hiredate, $startshift, $endshift, $org);
    $stmt->execute();

    $conn->commit();
    echo "<script>alert('Employee added successfully.');</script>";
  } catch (Exception $e) {
    $conn->rollback();
    error_log($e->getMessage());
    echo "<script>alert('Error adding employee. Please try again.');</script>";
  }
}

// Fetch all employees
$sql = "SELECT employee_id, photo, name, fingerprint_id, shift_start_time, shift_end_time, hire_date, org FROM employee";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>RCGI | Manage Employees</title>
  <link rel="icon" href="pics/rcgiph_logo.jpg" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />

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
      height: calc(100vh - 60px);
      /* full height minus navbar */
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

    .table img {
      width: 60px;
      height: 60px;
    }

    #openModalBtn {
      background-color: #E2E2E2;
      color: black;
      font-size: 16px;
      font-weight: bold;
      padding: 12px 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.2);
    }

    #openModalBtn:hover {
      background-color: #9FAC9F;
      /* Darker blue on hover */
      transform: scale(1.05);
    }

    #openModalBtn:active {
      background-color: #5F7566;
      transform: scale(1);
    }

    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
      width: 60%;
      margin: 2.2% auto;
      position: relative;
    }

    .close-btn {
      position: absolute;
      top: 10px;
      right: 15px;
      font-size: 22px;
      cursor: pointer;
      color: red;
    }

    .form-container {
      display: flex;
      gap: 20px;
    }

    .col-left {
      flex: 0.8;
      /* Smaller than the right column */
    }

    .col-right {
      flex: 1.2;
      /* Bigger than the left column */
      font-size: 13px;
    }

    .form-group {
      margin-bottom: 15px;
    }

    label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
    }

    input,
    select {
      width: 100%;
      padding: 5px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .image-upload input {
      display: block;
      margin: 10px auto;
      width: 100%;
    }

    .image-upload {
      text-align: center;
      margin-bottom: 15px;
      width: 100%;
    }

    .image-upload img {
      width: 80%;
      margin-bottom: 10px;
    }

    .modal-footer {
      text-align: right;
    }

    .btn {
      padding: 5px 15px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .btn-primary {
      background-color: #5F7566;
      color: white;
    }

    .btn-secondary {
      background-color: #6c757d;
      color: white;
    }

    .modal-header {
      display: flex;
      align-items: center;
      background-color: #5F7566;
      color: white;
      border-top-left-radius: 5px;
      border-top-right-radius: 5px;
      height: 50px;
    }

    .modal-header h2 {
      margin-top: 10px;
      font-size: 25px;
    }

    .close-btn {
      font-size: 24px;
      cursor: pointer;
      padding-top: -20px;
      color: white;
    }

    .modal-body {
      background-color: #E2E2E2;
      border-radius: 10px;
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

  <!-- Layout -->
  <div class="container-fluid">
    <div class="row vh-100">

      <!-- Sidebar -->
      <div class="col-2 sidebar">
        <div class="list-group">
          <a href="admin_dashboard.php" class="list-group-item list-group-item-action">
            <i class="fas fa-tachometer-alt sidebar-icon"></i> Dashboard
          </a>
          <a href="admin_view-attendance.php" class="list-group-item list-group-item-action">
            <i class="fas fa-clock sidebar-icon"></i> View Attendance
          </a>
          <a href="admin_manage-employee.php" class="list-group-item list-group-item-action active">
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
      <div class="col-10 main-content">
        <h2>Manage Employees</h2>
        <p class="text-muted mb-4">View and manage employee records</p>

        <!-- Filters -->
        <div class="row g-3 align-items-end" style="margin-bottom: 15px;">
          <div class="col-md-3" style="width: 20%;">
          <label><strong>Start Shift</strong></label>
                    <select class="form-control" name="shift_start_time" required>
                      <option selected>Select Shift</option>
                      <option>8:00 AM</option>
                      <option>9:00 AM</option>
                    </select>
          </div>
          <div class="col-md-3" style="width: 20%;">
          <label><strong>End Shift</strong></label>
                    <select class="form-control" name="shift_end_time" required>
                      <option selected>Select Shift</option>
                      <option>5:00 PM</option>
                      <option>6:00 PM</option>
                    </select>
          </div>
          <div class="col-md-3" style="width: 30%;">
            <label><strong>Employee ID</strong></label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-search"></i></span>
              <input type="text" class="form-control" placeholder="Search by Employee ID..." />
            </div>
          </div>
          <div class="col-md-3" style="width: 30%;">
            <label><strong>Search Employee</strong></label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-search"></i></span>
              <input type="text" class="form-control" placeholder="Search by Name..." />
            </div>
          </div>
        </div>
        <!-- Employee List -->
        <div class="card">
          <div class="card-header fw-bold d-flex justify-content-between align-items-center" style="background: #5F7566;">
            <span style="color: white;">Company Employees</span>
            <!-- Button to Open Modal -->
            <button id="openModalBtn" class="btn btn-primary">Add New Employee</button>
            <div id="employeeModal" class="modal">
              <div class="modal-content">
                <div class="modal-header">
                  <h2>Add New Employee</h2>
                  <span class="close-btn" id="closeModalBtn">&times;</span>
                </div>
                <div class="modal-body">
                  <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-container">
                      <!-- Left Column -->
                      <div class="col-left">
                        <div class="image-upload">
                          <label style="margin-top: 50px; margin-bottom: 15px;">Insert Profile Picture</label>
                          <img src="pics/placeholder.jpg" class="img-thumbnail" alt="Profile Image" id="employee_image">
                          <input type="file" class="form-control" name="employee_image" style="width: 70%;" id="imageUpload" accept="image/*" required>
                        </div>
                      </div>

                      <!-- Right Column -->
                      <div class="col-right">
                        <div class="form-group">
                          <label>Employee ID</label>
                          <input type="text" name="employee_ID" placeholder="Enter employee ID" required>
                        </div>
                        <div class="form-group">
                          <label>Name</label>
                          <input type="text" name="name" placeholder="Enter name" required>
                        </div>
                        <div class="form-group">
                          <label>Fingerprint ID</label>
                          <input type="text" name="fingerprint_ID" placeholder="Enter fingerprint ID" required>
                        </div>
                        <div class="form-group">
                          <label>Hire Date</label>
                          <input type="date" name="hire_date" required>
                        </div>
                        <div class="form-group">
                          <label>Start Shift</label>
                          <select name="shift_start_time" required>
                            <option selected>Select Shift</option>
                            <option>8:00 AM</option>
                            <option>9:00 AM</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label>End Shift</label>
                          <select name="shift_end_time" required>
                            <option selected>Select Shift</option>
                            <option>5:00 PM</option>
                            <option>6:00 PM</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label>Organization</label>
                          <select name="org" required>
                            <option selected>RCGI</option>
                            <option>Terraco</option>
                          </select>
                        </div>
                        <div class="modal-footer">
                          <button type="submit" name="submit" class="btn btn-primary">Add Employee</button>
                          <button type="button" class="btn btn-secondary" id="cancelBtn">Cancel</button>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>

          </div>
          <div class="card-body table-responsive">
            <table class="table table-bordered text-center align-middle">
              <thead class="table-light">
                <tr>
                  <th>Employee ID</th>
                  <th>Photo</th>
                  <th>Name</th>
                  <th>Fingerprint ID</th>
                  <th>Shift</th>
                  <th>Organization</th>
                  <th>Member Since</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if (isset($result) && $result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['employee_id']) . "</td>";
                    echo "<td><img src='" . htmlspecialchars($row['photo']) . "' class='img-thumbnail' style='width: 60px; height: 60px;' onerror=\"this.src='placeholder.jpg'\" /></td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['fingerprint_id']) . "</td>";
                    echo "<td>" . date("H:i", strtotime($row['shift_start_time'])) . " AM - " . date("H:i", strtotime($row['shift_end_time'])) . " PM</td>";
                    echo "<td>" . htmlspecialchars($row['org']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['hire_date']) . "</td>";
                    echo "<td>
                                <a href='edit_employee.php?id={$row['employee_id']}' class='icon-btn'>
                                  <i class='fas fa-edit'></i>
                                </a>
                                <a href='delete_employee.php?id={$row['employee_id']}' class='icon-btn' onclick='return confirm(\"Are you sure you want to delete this employee?\")'>
                                  <i class='fas fa-trash'></i>
                                </a>
                              </td>";
                    echo "</tr>";
                  }
                } else {
                  echo "<tr><td colspan='8'>No employees found.</td></tr>";
                }
                ?>
              </tbody>
            </table>
            <nav>
              <ul class="pagination justify-content-end">
                <li class="page-item disabled"><a class="page-link">Previous</a></li>
                <li class="page-item disabled"><a class="page-link">Next</a></li>
              </ul>
            </nav>
          </div>
        </div>
      </div> <!-- End Main Content -->
    </div> <!-- End Row -->
  </div> <!-- End Container -->

  <script>
    document.getElementById("openModalBtn").addEventListener("click", function() {
      document.getElementById("employeeModal").style.display = "block";
    });

    document.getElementById("closeModalBtn").addEventListener("click", function() {
      document.getElementById("employeeModal").style.display = "none";
    });

    document.getElementById("cancelBtn").addEventListener("click", function() {
      document.getElementById("employeeModal").style.display = "none";
    });

    // Close modal if user clicks outside content box
    window.onclick = function(event) {
      var modal = document.getElementById("employeeModal");
      if (event.target === modal) {
        modal.style.display = "none";
      }
    };

    document.getElementById("imageUpload").addEventListener("change", function(event) {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          document.getElementById("employee_image").src = e.target.result;
        };
        reader.readAsDataURL(file);
      }
    });
  </script>
</body>

</html>