<?php
$conn = mysqli_connect("localhost","root","","project");

// Search
$search = "";
if(isset($_GET['search'])){
    $search = $_GET['search'];
}

$query = "
SELECT p.*, r.Student_name, r.Department, r.Vehicle_no
FROM parking_log p
LEFT JOIN register r ON p.rfid_uid = r.rfid
WHERE r.Student_name LIKE '%$search%'
OR p.Department_number LIKE '%$search%'
OR r.Vehicle_no LIKE '%$search%'
ORDER BY p.entry_time DESC
";

$result = mysqli_query($conn,$query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Parking Logs</title>
<link rel="stylesheet" href="style.css">
</head>

<body>


<div class="sidebar">
<h2>Admin</h2>
<ul>
<li><a href="adindex.php">Dashboard</a></li>
<li><a href="manage_student.php">Manage Students</a></li>
<li><a href="adpark.php">Parking Logs</a></li>
<li><a href="login.php">Logout</a></li>
</ul>
</div>

<div class="main-content">

<h1>Parking Logs</h1>

<div class="table-container">

<form method="GET" class="search-bar">
<input type="text" name="search" placeholder="Search Name / Dept No / Vehicle"
value="<?php echo $search; ?>">
<button type="submit">Search</button>
</form>

<table>

<tr>
<th>Entry Time</th>
<th>Exit Time</th>
<th>Status</th>
<th>Department No</th>
<th>Student Name</th>
<th>Department</th>
<th>Vehicle No</th>
<th>Fee</th>
<th>Payment</th>
</tr>

<?php
while($row = mysqli_fetch_assoc($result)){
?>

<tr>

<td><?php echo $row['entry_time']; ?></td>

<td>
<?php
if($row['exit_time'] == NULL){
echo "-";
}else{
echo $row['exit_time'];
}
?>
</td>

<td><?php echo $row['status']; ?></td>

<td><?php echo $row['Department_number']; ?></td>

<td><?php echo $row['Student_name']; ?></td>

<td><?php echo $row['Department']; ?></td>

<td><?php echo $row['Vehicle_no']; ?></td>

<td>₹ <?php echo $row['fee']; ?></td>

<td>
<?php
if($row['payment_status']=="Paid"){
echo "<span class='status-active'>Paid</span>";
}else{
echo "<span class='status-pending'>Pending</span>";
}
?>
</td>

</tr>

<?php
}
?>

</table>

</div>

</div>

</body>
</html>