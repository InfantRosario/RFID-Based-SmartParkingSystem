<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "project");

if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['uid'];

/* Fetch student details */
$result = mysqli_query($conn, "SELECT * FROM register WHERE Department_number='$username'");
$data = mysqli_fetch_assoc($result);

/* Fetch latest parking/payment record */
$result1 = mysqli_query($conn, "SELECT * FROM parking_log 
                                WHERE Department_number='$username' 
                                ORDER BY id DESC LIMIT 1");
$data1 = mysqli_fetch_assoc($result1);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- 🔷 SIDEBAR -->
<div class="sidebar">
    <h2>Student Panel</h2>
    <ul>
        <li><a href="stdprofile.php" class="active">My Profile</a></li>
        <li><a href="stdparking.php">Parking History</a></li>
        <li><a href="stdhistory.php">Payment</a></li>
        <li><a href="login.php">Logout</a></li>
    </ul>
</div>

<!-- 🔷 MAIN CONTENT -->
<div class="main-content">

    <h1>Student Profile</h1>

    <div class="table-container">

        <table class="profile-table">

            <tr>
<th style="width:35%; padding:14px 16px; text-align:left; font-weight:600; border-bottom:1px solid #0a64eb; background:#ffffff; color: black;">
    Student ID
</th>
                <td><?php echo $data['Department_number'] ?? 'N/A'; ?></td>
            </tr>

            <tr>
                <th style="width:35%; padding:14px 16px; text-align:left; font-weight:600; border-bottom:1px solid #0a64eb; background:#ffffff; color: black;">Name</th>
                <td><?php echo $data['Student_name'] ?? 'N/A'; ?></td>
            </tr>

            <tr>
                <th style="width:35%; padding:14px 16px; text-align:left; font-weight:600; border-bottom:1px solid #0a64eb; background:#ffffff; color: black;">Department</th>
                <td><?php echo $data['Department'] ?? 'N/A'; ?></td>
            </tr>

            <tr>
                <th style="width:35%; padding:14px 16px; text-align:left; font-weight:600; border-bottom:1px solid #0a64eb; background:#ffffff; color: black;">Email</th>
                <td><?php echo $data['email'] ?? 'N/A'; ?></td>
            </tr>

            <tr>
                <th style="width:35%; padding:14px 16px; text-align:left; font-weight:600; border-bottom:1px solid #0a64eb; background:#ffffff; color: black;">Vehicle Number</th>
                <td><?php echo $data['Vehicle_no'] ?? 'N/A'; ?></td>
            </tr>

            <tr>
                <th style="width:35%; padding:14px 16px; text-align:left; font-weight:600; border-bottom:1px solid #0a64eb; background:#ffffff; color: black;">RFID UID</th>
                <td><?php echo !empty($data['rfid']) ? $data['rfid'] : "Not Assigned"; ?></td>
            </tr>

            <tr>
                <th style="width:35%; padding:14px 16px; text-align:left; font-weight:600; border-bottom:1px solid #0a64eb; background:#ffffff; color: black;">RFID Status</th>
                <td>
                    <?php 
                        if (empty($data['rfid'])) {
                            echo "<span class='status-pending'>Not Assigned</span>";
                        } elseif ($data['rfid_status'] == "BLOCKED") {
                            echo "<span class='status-pending'>Blocked</span>";
                        } else {
                            echo "<span class='status-active'>Active</span>";
                        }
                    ?>
                </td>
            </tr>

            <tr>
                <th style="width:35%; padding:14px 16px; text-align:left; font-weight:600; border-bottom:1px solid #0a64eb; background:#ffffff; color: black;">Fee Status</th>
                <td>
                    <?php 
                        if (!empty($data1) && isset($data1['payment_status']) && $data1['payment_status'] == "Paid") {
                            echo "<span class='status-active'>Paid</span>";
                        } else {
                            echo "<span class='status-pending'>Pending</span>";
                        }
                    ?>
                </td>
            </tr>

            <tr>
                <th style="width:35%; padding:14px 16px; text-align:left; font-weight:600; border-bottom:1px solid #0a64eb; background:#ffffff; color: black;">Total Fee</th>
                <td>
                    ₹ 
                    <?php 
                        echo !empty($data1['fee']) ? $data1['fee'] : "0";
                    ?>
                </td>
            </tr>

        </table>

    </div>

</div>

</body>
</html> 