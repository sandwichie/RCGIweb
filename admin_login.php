<?php
session_start();

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

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['psw'];

    // Query to check username and fetch the hashed password
    $stmt = $conn->prepare("SELECT admin_ID, username, password FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    // Bind result
    $stmt->bind_result($admin_id, $admin_user, $db_password);
    $stmt->fetch();
    $stmt->close();

    // Verify password using password_verify
    if (password_verify($password, $db_password)) {
        $_SESSION['admin_ID'] = $admin_id; // Store admin ID in session
        $_SESSION['admin_user'] = $admin_user;

        header("Location: admin_dashboard.php");
        exit;
    } else {
        echo "<script>alert('Invalid username or password!');</script>";
    }
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RCGI | LOGIN</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <link rel="icon" href="pics\rcgiph_logo.jpg" type="logo">
    <style>
        body {
            /* Admin Login */
            font-family: 'Inter';
            font-weight: bold;
            font-weight: 600;
            font-size: 15px;
            line-height: 29px;
            color: #000000;
            background-image: url('pics/dylan-gillis-KdeqA3aTnBY-unsplash.jpg');
            background-size: cover;
            background-repeat: no-repeat;
        }

        h1 {
            text-align: center;
            left: 40%;
            right: 44.93%;
            font-size: 25px;
        }

        /* Rectangle 2 */
        .containerlogin {
            width: 400px;
            height: 400px;
            box-sizing: border-box;
            position: absolute;
            left: 36%;
            right: 31.94%;
            top: 20%;
            bottom: 23.73%;
            background: #FFFEFE;
            border: 2px solid #DFDDDD;
            box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
            border-radius: 20px;
        }

        .login-form label {
            margin-left: 50px;
            display: block;
            margin-bottom: -5px;
            letter-spacing: 1px;
            /*text-transform: uppercase;*/
        }

        .login-form input[type="text"] {
            width: 70%;
            padding: 5px;
            margin-left: 50px;
            margin-bottom: 5px;
            background: #FFFFFF;
            border: 1px solid #000000;
            border-radius: 5px;
        }

        .input-container input[type="password"] {
            width: 70%;
            padding: 5px;
            margin-left: 50px;
            margin-bottom: 5px;
            background: #FFFFFF;
            border: 1px solid #000000;
            border-radius: 5px;
        }

        button {
            background: #7A8D7A;
            border: 1px solid #040404;
            border-radius: 5px;
            width: 72%;
            margin-top: 20px;
            padding: 5px;
            position: relative;
            margin-left: 50px;
            color: white;

        }

        .button .loginbtn:hover {
            background-color: #9FAC9F;
            cursor: pointer;
        }

        .forgot-password {
            color: black;
            text-decoration: none;
            margin-left: 120px;
            padding: 10px;
            display: flex;
            color: #000000;
        }

        img {
            display: block;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: -20px;
            width: 25%;
        }
    </style>
</head>

<body>
    <div class="containerlogin" style="opacity: 80%;">
        <div class="login-form">
            <form action="" method="POST">
                <img src="pics\rcgiph_logo.jpg" alt="logo" />
                <h1>Admin Login</h1>
                <label for="username">Username</label>
                <input type="text" placeholder="Enter username" name="username" required />

                <label for="psw">Password</label>
                <div class="input-container">
                    <input type="password" id="id_password" placeholder="Enter password" name="psw" required />
                </div>

                <div class="button">
                    <button type="submit" name="login" class="loginbtn" style="text-align: center;">Login</button>
                </div>

                <a href="" class="forgot-password">Forgot Password?<br></a>
            </form>
        </div>

    </div>


</body>

</html>