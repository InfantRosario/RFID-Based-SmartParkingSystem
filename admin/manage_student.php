<?php
$conn = mysqli_connect("localhost", "root", "", "project");

/* ASSIGN RFID */
if (isset($_POST['assign_rfid'])) {
    $student_id = $_POST['student_id'];
    $rfid = $_POST['rfid'];

    $check = mysqli_query($conn, "SELECT * FROM register WHERE rfid='$rfid'");
    if (mysqli_num_rows($check) == 0) {
        mysqli_query($conn, "UPDATE register 
            SET rfid='$rfid', rfid_status='ACTIVE' 
            WHERE Department_number='$student_id'");
    } else {
        echo "<script>alert('RFID already assigned!');</script>";
    }
}

/* BLOCK */
if (isset($_GET['block'])) {
     $id = $_GET['block'];
    mysqli_query($conn, "UPDATE register 
        SET rfid_status='BLOCKED' 
        WHERE Department_number='$id'");
}


/* ACTIVATE */
if (isset($_GET['activate'])) {
     $id = $_GET['activate'];
    mysqli_query($conn, "UPDATE register 
        SET rfid_status='ACTIVE' 
        WHERE Department_number='$id'");
}

/* SEARCH */
$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $query = "SELECT * FROM register 
              WHERE Department_number LIKE '%$search%' ";
} else {
    $query = "SELECT * FROM register";
}

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Students</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="sidebar">
    <h2>Admin Panel</h2>
    <ul>
        <li><a href="adindex.php">Dashboard</a></li>
        <li><a href="manage_student.php">Manage Students</a></li>
        <li><a href="adminpark.php">Parking Logs</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<div class="main-content">
    <h1>Manage Students</h1>

    <div class="table-container">

        <form method="GET" class="search-bar">
            <input type="text" name="search" placeholder="Search student..." value="<?php echo $search; ?>">
            <button type="submit">Search</button>
        </form>

        <table>
            <tr>
                <th>Student Name</th>
                <th>Department number</th>
                <th>Department</th>
                <th>Vehicle No</th>
                <th>RFID UID</th>
                <th>RFID Status</th>
                <th>Action</th>
            </tr>

            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['Student_name']; ?></td>
                    <td><?php echo $row['Department_number']; ?></td>
                    <td><?php echo $row['Department']; ?></td>
                    <td><?php echo $row['Vehicle_no']; ?></td>
                    <td><?php echo $row['rfid'] ? $row['rfid'] : "Not Assigned"; ?></td>
                    <td>
                        <?php 
                        if ($row['rfid_status'] == "ACTIVE") {
                            echo "<span class='badge active-badge'>ACTIVE</span>";
                        } elseif ($row['rfid_status'] == "BLOCKED") {
                            echo "<span class='badge blocked-badge'>BLOCKED</span>";
                        } else {
                            echo "<span class='badge notassigned-badge'>NOT_ASSIGNED</span>";
                        }
                        ?>
                    </td>
                    <td>

                        <?php if ($row['rfid_status'] == "NOT_ASSIGNED") { ?>
                            <form method="POST" style="display:inline;" class="search-bar">
                                <input type="hidden" name="student_id" value="<?php echo $row['Department_number']; ?>">
                                <input type="text" name="rfid" placeholder="RFID" required>
                                <button type="submit" name="assign_rfid" class="action-btn assign-btn">Assign</button>
                            </form>

                        <?php } elseif ($row['rfid_status'] == "ACTIVE") { ?>
                            <a href="?block=<?php echo $row['Department_number']; ?>" class="action-btn block-btn">Block</a>

                        <?php } elseif ($row['rfid_status'] == "BLOCKED") { ?>
                            <a href="?activate=<?php echo $row['Department_number']; ?>" class="action-btn activate-btn">Activate</a>
                        <?php } ?>

                    </td>
                </tr>
            <?php } ?>
        </table>

    </div>
</div>

</body>