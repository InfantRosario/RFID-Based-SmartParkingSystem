<?php
// Auto-refresh every 5 seconds for dashboard
header("Refresh: 5");

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project"; // your DB name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Handle ESP32 GET request
if(isset($_GET['rfid'])) {
    $rfid = $_GET['rfid'];

    // Check register table for active RFID
    $stmt = $conn->prepare("SELECT * FROM register WHERE rfid = ? AND rfid_status='ACTIVE'");
    $stmt->bind_param("s", $rfid);
    $stmt->execute();
    $result = $stmt->get_result();
   
    if($result->num_rows > 0){
        $student = $result->fetch_assoc();
        $dept = $student['Department_number'];
        $name = $student['Student_name'];
        $vehicle = $student['Vehicle_no'];

        // Check if student is already "In"
        $check = $conn->prepare("SELECT * FROM parking_log WHERE rfid_uid=? AND status='In' ORDER BY entry_time DESC LIMIT 1");
        $check->bind_param("s", $rfid);
        $check->execute();
        $checkResult = $check->get_result();

        if($checkResult->num_rows == 0){
            // Student is entering → calculate fee
            // Get last fee for this student
            $lastFeeResult = $conn->query("SELECT fee FROM parking_log WHERE rfid_uid='$rfid' ORDER BY entry_time DESC LIMIT 1");
            $lastFee = 0;
            if($lastFeeResult->num_rows > 0){
                $row = $lastFeeResult->fetch_assoc();
                $lastFee = $row['fee'];
            }
            $newFee = $lastFee + 3; // increment by 3 Rs

            // Insert entry (status = In)
            $insert = $conn->prepare("INSERT INTO parking_log (Department_number, rfid_uid, entry_time, status, fee, payment_status) VALUES (?, ?, NOW(), 'In', ?, 'Pending')");
            $insert->bind_param("ssi", $dept, $rfid, $newFee);
            $insert->execute();

            echo "Access granted: $name ($dept) — Vehicle: $vehicle — Fee: Rs $newFee";
        } else {
            // Student is exiting → update exit time and status
            $update = $conn->prepare("UPDATE parking_log SET exit_time=NOW(), status='Out' WHERE rfid_uid=? AND status='In' ORDER BY entry_time DESC LIMIT 1");
            $update->bind_param("s", $rfid);
            $update->execute();

            // Get fee for this entry
            $feeResult = $conn->query("SELECT fee FROM parking_log WHERE rfid_uid='$rfid' ORDER BY entry_time DESC LIMIT 1");
            $row = $feeResult->fetch_assoc();
            $currentFee = $row['fee'];

            echo "Exit recorded: $name ($dept) — Vehicle: $vehicle — Fee: Rs $currentFee";
        }
    } else {
        echo "Access denied: RFID not active or not assigned";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Parking Dashboard</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        h2 { color: #333; }
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        th, td { border: 1px solid #aaa; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        tr:nth-child(even) { background-color: #f9f9f9; }
    </style>
</head>
<body>
    <h2>Latest Parking Entries</h2>

    <?php
    // Display latest 20 parking entries
    $sql = "SELECT p.*, r.Student_name, r.Department, r.Vehicle_no 
            FROM parking_log p 
            LEFT JOIN register r ON p.rfid_uid = r.rfid
            ORDER BY p.entry_time DESC
            LIMIT 20";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr>
                <th>Time</th>
                <th>Status</th>
                <th>Department Number</th>
                <th>Name</th>
                <th>Department</th>
                <th>Vehicle No</th>
                <th>Fee</th>
                <th>Payment Status</th>
              </tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['entry_time'] . "</td>
                    <td>" . $row['status'] . "</td>
                    <td>" . $row['Department_number'] . "</td>
                    <td>" . $row['Student_name'] . "</td>
                    <td>" . $row['Department'] . "</td>
                    <td>" . $row['Vehicle_no'] . "</td>
                    <td>" . $row['fee'] . "</td>
                    <td>" . $row['payment_status'] . "</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No parking entries found.</p>";
    }

    $conn->close();
    ?>
</body>
</html>