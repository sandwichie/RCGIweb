<?php
require "conn.php";

if (isset($_POST['submit'])) {
    $employee_id = $_POST['emp-id'];
    $time = $_POST['time'];
    $type = $_POST['type']; //time in or time out
    $date = $_POST['date'];

    // Convert date and time to the correct format
    $datetime = DateTime::createFromFormat('Y-m-d H:i:s', "$date $time");

    $formatted_date = $datetime->format('Y-m-d');
    $formatted_time = $datetime->format('H:i:s');

    $check_emp = $conn->prepare("SELECT * FROM employee WHERE employee_ID = :employee_ID");
    $check_emp->bindParam(':employee_ID', $employee_id);
    $check_emp->execute(); 
    $emp_exists = $check_emp->fetch(PDO::FETCH_OBJ);

    //check if employee exists
    if ($emp_exists && $employee_id == $emp_exists->employee_ID) {
        if ($type == 'time_in') {
            $check = $conn->prepare("SELECT * FROM attendance WHERE employee_ID = :employee_ID AND date = :date");
            $check->execute(array(':employee_ID' => $employee_id, ':date' => $formatted_date));
            $data = $check->fetch(PDO::FETCH_OBJ);
            // if there is already a record for the employee for the current date it will not allow to time in again
            if ($data && $data->date == $formatted_date && $data->employee_ID == $employee_id) {
                echo "<script>alert('You have already timed in for today!');</script>";
            } else {
                $insert = $conn->prepare("INSERT INTO attendance (employee_ID, date, time_in) VALUES (:employee_ID, :date, :time)");
                $insert->bindParam(':employee_ID', $employee_id);
                $insert->bindParam(':date', $formatted_date);
                $insert->bindParam(':time', $formatted_time);
                $insert->execute();
                echo "<script>alert('You have successfully timed in!');</script>";
            }
        } else {// timeout
            $check = $conn->prepare("SELECT * FROM employee WHERE employee_ID = :employee_ID");
            $check->execute(array(':employee_ID' => $employee_id));
            $data = $check->fetch(PDO::FETCH_OBJ);

            $check_attendance = $conn->prepare("SELECT * FROM attendance WHERE employee_ID = :employee_ID AND date = :date");
            $check_attendance->execute(array(':employee_ID' => $employee_id, ':date' => $formatted_date));
            $check_time_in = $check_attendance->fetch(PDO::FETCH_OBJ);
            // checks if the employee has timed in it will allow to time out
            if ($check_time_in && $check_time_in->time_in != null) {
                // checks if the time out is earlier than the shift end time
                if ($data && $data->shift_end_time > $formatted_time) {
                    $update = $conn->prepare("UPDATE attendance SET time_out = :time WHERE employee_ID = :employee_ID AND date = :date");
                    $update->bindParam(':time', $formatted_time);
                    $update->bindParam(':employee_ID', $employee_id);
                    $update->bindParam(':date', $formatted_date);
                    $update->execute();
                    echo "<script>alert('You can time out but you have not reached your shift end time!');</script>";
                // if the time out is later than the shift end time or exactly the same it will allow to time out
                } else if ($data && $data->shift_end_time <= $formatted_time) {
                    $update = $conn->prepare("UPDATE attendance SET time_out = :time WHERE employee_ID = :employee_ID AND date = :date");
                    $update->bindParam(':time', $formatted_time);
                    $update->bindParam(':employee_ID', $employee_id);
                    $update->bindParam(':date', $formatted_date);
                    $update->execute();
                    echo "<script>alert('You have successfully timed out!');</script>";
                }
            } else {
                echo "<script>alert('You have not timed in yet!');</script>";
            }
        }
    } else {
        echo "<script>alert('Employee ID does not exist!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RCGI | TIME IN</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="icon" href="pics/rcgiph_logo.jpg" type="image/x-icon">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            font-size: 15px;
            background: #F3F4F6;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .timein-container {
            background: #E5E0D8;
            border: 2px solid #E5E0D8;
            box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            max-width: 500px;
            width: 100%;
        }

        .logo {
            display: block;
            margin: 0 auto 10px;
            width: 60px;
        }

        h4 {
            font-size: 18px;
            font-style: italic;
            margin-bottom: 10px;
        }

        #time {
            font-size: 18px;
            background: #DCE7D8;
            padding: 5px 10px;
            border-radius: 5px;
            display: inline-block;
            font-weight: bold;
        }

        .clock-icon {
            font-size: 40px;
            margin: 15px 0;
            color: black;
        }

        select,
        input {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input::placeholder {
            text-align: center;
        }

        .enter-button {
            width: 100%;
            padding: 10px;
            background-color: #CC9D61;
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 15px;
        }

        .enter-button:hover {
            background-color: #E0BE92;
        }
    </style>
</head>

<body>

    <div class="timein-container">
        <img src="pics/rcgiph_logo.jpg" alt="Company Logo" class="logo">
        <form action="emp-time-in.php" method="POST">
            <h4 name="date" id="date"></h4>
            <span name="time" id="time">--:--:--</span>
            <div class="clock-icon"><i class="fas fa-clock"></i></div>

            <select name="type" class="form-control">
                <option value="time_in" selected>TIME IN</option>
                <option value="time_out">TIME OUT</option>
            </select>

            <input name="emp-id" type="text" class="form-control" placeholder="Employee ID" required>
            <input name="date" type="hidden" id="hidden-date">
            <input name="time" type="hidden" id="hidden-time">

            <button name="submit" type="submit" class="enter-button">ENTER</button>

        </form>
    </div>

    <script>
        function updateTime() {
            const now = new Date();
            const dateOptions = {
                weekday: 'long',
                month: 'long',
                day: '2-digit',
                year: 'numeric'
            };
            const timeOptions = {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            };

            document.getElementById('date').textContent = now.toLocaleDateString('en-US', dateOptions);
            document.getElementById('time').textContent = now.toLocaleTimeString('en-US', timeOptions);
            document.getElementById('hidden-date').value = now.toISOString().split('T')[0];
            document.getElementById('hidden-time').value = now.toTimeString().split(' ')[0];
        }

        setInterval(updateTime, 1000);
        updateTime();
    </script>

</body>

</html>
