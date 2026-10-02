<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "project");

if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['uid'];

/* Fetch all parking records for this student */
$result = mysqli_query($conn, "
        SELECT * FROM parking_log 
        WHERE Department_number='$username'
        ORDER BY entry_time DESC
    ");

/* Calculate total pending fee */
$total_query = mysqli_query($conn, "
    SELECT fee as total_fee
    FROM parking_log 
    WHERE Department_number='$username'
    AND payment_status='Pending'
    ORDER BY id DESC
    LIMIT 1
    ");
$total_data = mysqli_fetch_assoc($total_query);
$total_fee = $total_data['total_fee'] ?? 0;
?>

<!DOCTYPE html>
<html>

<head>
    <title>Parking History</title>
    <link rel="stylesheet" href="style.css">
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
                    <th>Date & Time</th>
                    <th>Fee</th>
                    <th>Status</th>
                </tr>

                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $row['entry_time']; ?></td>
                            <td>₹ <?php echo $row['fee']; ?></td>
                            <td>
                                <?php
                                if ($row['payment_status'] == "Paid") {
                                    echo "<span class='status-active'>Paid</span>";
                                } else {
                                    echo "<span class='status-pending'>Pending</span>";
                                }
                                ?>
                            </td>
                        </tr>
                    <?php
                    }
                } else { ?>
                    <tr>
                        <td colspan="3" style="text-align:center; padding:15px;">
                            No parking history found
                        </td>
                    </tr>
                <?php } ?>

                <!-- TOTAL ROW -->
                <tr>
                    <th>Total Pending</th>
                    <td colspan="2" style="color:red;">
                        ₹ <?php echo $total_fee ?? 0; ?>

                        <button type="submit" name="submit"
                            style="padding:8px 16px; background-color: red; color:white; border:none; border-radius:6px; cursor:pointer;">
                            Pay
                        </button>

                    </td>

                </tr>

            </table>
        </div>
    </div>

</body>

</html>