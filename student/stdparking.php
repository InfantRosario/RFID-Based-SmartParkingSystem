<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "project");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check login
if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['uid'];

// Fetch parking history for logged-in student
$result = mysqli_query($conn, "
    SELECT entry_time, exit_time, status, fee 
    FROM parking_log 
    WHERE Department_number='$username'
    ORDER BY entry_time DESC
") or die(mysqli_error($conn));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Parking History</title>
    <link rel="stylesheet" href="style.css">

    <style>
        .status-in {
            color: green;
            font-weight: bold;
        }
        .status-out {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>Student Panel</h2>
    <ul>
        <li><a href="stdprofile.php">My Profile</a></li>
        <li><a href="stdparking.php" class="active">Parking History</a></li>
        <li><a href="stdhistory.php">Payment</a></li>
        <li><a href="login.php">Logout</a></li>
    </ul>
</div>

<!-- MAIN CONTENT -->
<div class="main-content">
    <h1>Parking History</h1>

    <div class="table-container">
        <table class="profile-table">
            <tr>
                <th>Entry Time</th>
                <th>Exit Time</th>
                <th>Status</th>
                <th>Fee (₹)</th>
            </tr>

            <?php 
            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['entry_time']; ?></td>

                        <td>
                            <?php 
                                echo $row['exit_time'] ? $row['exit_time'] : "Still Inside";
                            ?>
                        </td>

                        <td>
                            <?php 
                                if ($row['status'] == "IN") {
                                    echo "<span class='status-in'>IN</span>";
                                } else {
                                    echo "<span class='status-out'>OUT</span>";
                                }
                            ?>
                        </td>

                        <td> <?php echo $row['fee']; ?></td>
                    </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='4'>No parking history found</td></tr>";
            }
            ?>
        </table>
    </div>
</div>

</body>
</html>