<?php
session_start();
$con=new mysqli("localhost","root","","project");
    if($con->connect_error)
      {
        die("Connection failed".$con->connect_error);
      }

// 🔒 SESSION CHECK
if(!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit();
}

// 📊 TOTAL STUDENTS
$total_students_query = "SELECT COUNT(*) AS total_students FROM register";
$total_students_result = mysqli_query($con, $total_students_query);
$total_students_data = mysqli_fetch_assoc($total_students_result);
$total_students = $total_students_data['total_students'];

// 📊 TOTAL ENTRIES TODAY
$today_query = "SELECT COUNT(*) AS total_today 
                FROM parking_log
                WHERE DATE(entry_time) = CURDATE()";
$today_result = mysqli_query($con, $today_query);
$today_data = mysqli_fetch_assoc($today_result);
$total_today = $today_data['total_today'];

// 📊 ACTIVE PARKING
$active_query = "SELECT COUNT(*) AS active_count 
                 FROM parking_log 
                 WHERE status = 'IN'";
$active_result = mysqli_query($con, $active_query);
$active_data = mysqli_fetch_assoc($active_result);
$active_count = $active_data['active_count'];

// 📋 FETCH STUDENTS
$students_query = "SELECT * FROM register ORDER BY Department_number DESC";
$students_result = mysqli_query($con, $students_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Smart Parking</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="sidebar">
    <h2>Admin Panel</h2>
    <ul>
        <li><a href="adindex.php">Dashboard</a></li>
        <li><a href="manage_student.php">Manage Students</a></li>
        <li><a href="adminpark.php">Parking Logs</a></li>
        <li><a href="login.php">Logout</a></li>
    </ul>
</div>

<div class="main-content">

    <h1>Dashboard</h1>

    <div class="cards">

        <div class="card">
            <h3>Total Students</h3>
            <p><?php echo $total_students; ?></p>
        </div>

        <div class="card">
            <h3>Entries Today</h3>
            <p><?php echo $total_today; ?></p>
        </div>

        <div class="card">
            <h3>Active Parking</h3>
            <p><?php echo $active_count; ?></p>
        </div>

    </div>

    <h2>Registered Students</h2>

    <div class="table-container">
        <table>
            <tr>
                
                <th>Student Name</th>
                <th>Department number</th>
                <th>Department</th>
                <th>Vehicle No</th>
                <th>RFID UID</th>
                <th>RFID Status</th>
            </tr>

            <?php while($row = mysqli_fetch_assoc($students_result)) { ?>
            <tr>
            
                <td><?php echo $row['Student_name']; ?></td>
                <td><?php echo $row['Department_number']; ?></td>
                <td><?php echo $row['Department']; ?></td>
                <td><?php echo $row['Vehicle_no']; ?></td>
                <td><?php echo $row['rfid']; ?></td>
                <td><?php echo $row['rfid_status'];?></td>
            </tr>
            <?php } ?>

        </table>
    </div>

</div>

</body>
</html>