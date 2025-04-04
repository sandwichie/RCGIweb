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
    $name = trim($_POST['name']);
    $password1 = trim($_POST['psw']);

    // Check if it's the admin
    if ($name === "admin" && $password1 === "Password1") {
        $_SESSION['admin_user'] = $name;
        header("Location: admin_login.php"); // Change to your actual admin page
        exit;
    }

    // Normal employee login
    $stmt = $conn->prepare("SELECT employee_ID, name, password FROM employee WHERE BINARY name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($employee_id, $employee_user, $db_password);
        $stmt->fetch();

        if ($password1 === $db_password) {
            $_SESSION['employee_ID'] = $employee_id;
            $_SESSION['employee_user'] = $employee_user;
            header("Location: emp_attendancepage.php");
            exit;
        } else {
            echo "<script>alert('Invalid username or password!');</script>";
        }
    } else {
        echo "<script>alert('Employee not found!');</script>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Agency - Start Bootstrap Theme</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="rcgi_styles.css" rel="stylesheet" />
</head>

<body id="page-top">
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="#page-top">
                <h3><span><img src="pics/rcgiph_logo-notext.png" alt="Logo" /></span>RCGI WorkPulse</h3>
            </a>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav text-uppercase ms-auto py-4 py-lg-0">
                    <li class="nav-item"><a class="nav-link" href="#portfolio">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Masthead-->
    <header class="masthead">
        <div class="container">
            <div class="masthead-subheading">Our BRANDS, Our CULTURE.</div>
            <div class="masthead-heading">We See <span style="color: #FFC349;">Work Excellence</span> and <span style="color: #FFC349;">Service Excellence Environment.</span></div>
            <a class="btn btn-primary btn-xl text-uppercase" href="#portfolio">Tell Me More</a>
        </div>
    </header>

    <!-- Portfolio Grid-->
    <section class="page-section" id="portfolio">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading text-uppercase">About Us</h2>
            </div>
            <div class="row">
                <div class="col-lg-4 col-sm-6 mb-4">
                    <!--item 1-->
                    <div class="portfolio-item">
                        <div class="portfolio-caption">
                            <div class="portfolio-caption-heading" style="text-align: left;">We See <span style="color: #9C554F; font-style: italic;"> Work Excellence</span> and <span style="color: #9C554F; font-style: italic;">Service Excellence Environment</span></div>
                            <div class="portfolio-caption-subheading text-muted">
                                At <span style="color: #5F7566; font-weight: bold; font-size: 1.2rem;">RestaurantConcepts Group, Inc.</span> "Our BRANDS, Our CULTURE" is more than a statement; it is the essence of our identity and the foundation of our success.<br><br>
                                This guiding principle reflects our unwavering dedication to achieving the highest standards in every aspect of our organization.<br><br>

                                <span style="color: #E2AA37; font-weight: bold; font-size: 1.2rem;">Our BRANDS:</span>
                                Each of our brands represents a unique commitment to culinary artistry and innovation. We take pride in offering exceptional dining experiences, and a keen eye for detail. Our brands are a testament to our passion for creating unforgettable moments for our guests.<br><br>

                                <span style="color: #E2AA37; font-weight: bold; font-size: 1.2rem;">Our CULTURE:</span>
                                Our culture is built on the pillars of work excellence and service excellence. We foster an environment driven by a shared commitment to professionalism, integrity, and genuine hospitality.<br><br>

                                Every INTERACTION, BRAND, AND SERVICE is a reflection of our culture of excellence.
                                Together, "Our BRANDS, Our CULTURE" encapsulates our mission and steadfast commitment to a Work Excellence and Service Excellence Environment.<br><br>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 mb-4">
                    <!-- Portfolio item 2-->
                    <div class="portfolio-item">
                        <div class="portfolio-caption">
                            <img class="img-fluid" src="pics/rcgiph_tag.jpg" alt="..." />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Contact-->
    <section class="page-section" id="contact">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading text-uppercase">Employee Login</h2>
            </div>

            <form action="" method="POST">
                <div class="form-group">
                    <!-- Name input-->
                    <label for="name" style="color: white; margin-left: 340px;">Name</label>
                    <input class="form-control" style="margin-left: 340px;" type="text" placeholder="Enter your name" name="name" required />
                </div>
                <div class="form-group">
                    <!-- Password input-->
                    <label for="psw" style="color: white; margin-left: 340px; margin-top: 20px;">Password</label>
                    <input class="form-control" style="margin-left: 340px;" type="password" id="id_password" placeholder="Enter password" name="psw" required />
                    <div class="button">
                        <button type="submit" name="login" style="margin-left: 575px; margin-top: 50px; margin-bottom: 40px;" class="btn btn-primary btn-xl text-uppercase" style="text-align: center;">Login</button>
                    </div>
                    <a href="emp_forgotpass.php" style="margin-left: 575px;  margin-top: 100px;" class="forgot-password">Forgot Password?<br></a>
            </form>
        </div>
    </section>
    <!-- Admin Modal popup-->
    <div class="portfolio-modal modal fade" id="portfolioModal1" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="close-modal" data-bs-dismiss="modal"><img src="assets/img/close-icon.svg" alt="Close modal" /></div>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="modal-body">
                                <!-- Admin Login Form -->
              <form action="" method="POST" style="max-width: 400px; margin: auto;">
                <img src="pics/rcgiph_logo.png" alt="logo" style="width: 80px; margin-bottom: 20px;" />
                <h2 class="text-uppercase mb-4">Admin Login</h2>

                <div class="form-group text-start mb-3">
                  <label for="username">Username</label>
                  <input type="text" class="form-control" name="username" placeholder="Enter username" required />
                </div>

                <div class="form-group text-start mb-4">
                  <label for="psw">Password</label>
                  <input type="password" class="form-control" id="id_password" name="psw" placeholder="Enter password" required />
                </div>

                <button type="submit" name="login" class="btn btn-primary btn-xl text-uppercase w-100 mb-2">Login</button>

                <a href="#" class="forgot-password d-block mt-2" style="color: #000;">Forgot Password?</a>
              </form>

              <!-- Close button -->
              <button class="btn btn-secondary btn-sm mt-4" data-bs-dismiss="modal" type="button">
                <i class="fas fa-xmark me-1"></i>
                Close
              </button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="rcgi_scripts.js"></script>
    <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
    <!-- * *                               SB Forms JS                               * *-->
    <!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
    <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
    <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
</body>

</html>
