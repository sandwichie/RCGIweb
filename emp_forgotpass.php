<?php
$host = "localhost";
$dbname = "db_rcgi";
$user = "root";
$password = "";

// Create connection using MySQLi
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['submit'])) {
    $employee_id = $_POST['employee_ID']; 
    $name = $_POST['name'];
    $reason = $_POST['reason'];
    $status = "Pending";

    try {
        // Prepare SQL statement using MySQLi
        $stmt = $conn->prepare("INSERT INTO emp_forgotpass (employee_ID, name, reason, status) VALUES (?, ?, ?, ?)");
        
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }

        // Bind parameters
        $stmt->bind_param("isss", $employee_id, $name, $reason, $status);

        // Execute query
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        $msg = "Your password has been reset. Contact your admin for your password.";
    } catch (Exception $e) {
        error_log($e->getMessage());
        $msg = "Error submitting your request. " . $e->getMessage();
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Forgot Password</title>
  <link rel="icon" href="pics/rcgiph_logo.jpg" type="image/x-icon" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <style>
    body {
      font-family: 'Inter', sans-serif;
      font-weight: 600;
      background: url('pics/bgforpass.png') no-repeat center center fixed;
      background-size: cover;
      height: 100vh;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .form-wrapper {
      background-color: rgba(255, 255, 255, 0.95);
      padding: 30px 25px;
      border-radius: 15px;
      width: 100%;
      max-width: 400px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
      text-align: center;
    }

    .form-wrapper img {
      width: 80px;
      margin-bottom: 15px;
    }

    .form-wrapper h5 {
      margin-bottom: 20px;
      font-weight: bold;
    }

    input,
    textarea {
      margin-bottom: 10px;
    }

    .form-check {
      text-align: left;
    }

    .form-check input[type="radio"] {
      margin-right: 8px;
    }

    .form-check-label input[type="text"] {
      margin-left: 25px;
      margin-top: 5px;
    }

    .submit-btn {
      background-color: #CC9D61;
      color: white;
      font-weight: bold;
      width: 100%;
      padding: 10px;
      border: none;
      border-radius: 5px;
      margin-top: 10px;
    }

    .submit-btn:hover {
      background-color: #E0BE92;
    }

    .back-link {
      display: block;
      margin-top: 15px;
      font-size: 14px;
      text-decoration: underline;
      color: #333;
    }
  </style>
</head>

<body>
  <div class="form-wrapper">
    <img src="pics/rcgiph_logo.jpg" alt="Company Logo" />
    <?php if (!empty($msg)) echo "<div class='alert alert-info text-center'>$msg</div>"; ?>
    <h5>Forgot Password Request Form</h5>
    <form action="" method="POST">
      <div class="mb-3 text-start">
        <label for="employee_ID" class="form-label">Employee ID</label>
        <input type="text" name="employee_ID" class="form-control" placeholder="Enter your Employee ID" required>
      </div>
      <div class="mb-3 text-start">
        <label for="name" class="form-label">Name</label>
        <input type="text" name="name" class="form-control" placeholder="Enter your name" required>
      </div>

      <div class="mb-3 text-start">
        <label class="form-label">Reason for Reset</label>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="reason" id="forgot" value="Forgot Password" required>
          <label class="form-check-label" for="forgot">Forgot Password</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="reason" id="locked" value="Account Locked">
          <label class="form-check-label" for="locked">Account Locked</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="reason" id="other" value="Other">
          <label class="form-check-label" for="other">Other</label>
          <input type="text" class="form-control mt-2" name="other_reason" placeholder="Please specify">
        </div>
      </div>

      <button type="submit" name="submit"  class="submit-btn">Submit</button>
    </form>
    <a href="emp_login.php" class="back-link">Back to Log in</a>
  </div>
</body>

</html>
