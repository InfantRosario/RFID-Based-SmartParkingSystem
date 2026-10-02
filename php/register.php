<?php
if(isset($_POST['submit']))
{
    $u = $_POST['name'];
    $dnum = $_POST['dnum'];
    $dname = $_POST['dname'];
    $vehi = $_POST['vehi'];
    $mail= $_POST['mail'];
    $pass = $_POST['pass'];

    $con = new mysqli("localhost","root","","project");

    if($con->connect_error)
    {
        die("Connection failed " . $con->connect_error);
    }

    $check = $con->query("SELECT * FROM login WHERE username='$dnum'");

    if($check->num_rows > 0)
    {
        echo "<script>alert('Department number already exists!');</script>";
    }
    else
    {
        $sql = "INSERT INTO register (Student_name, Department_number, Department, Vehicle_no,  password, email)
                VALUES ('$u', '$dnum', '$dname', '$vehi', '$pass', '$mail')";

        $sql1 = "INSERT INTO login (username, password)
                 VALUES ('$dnum', '$pass')";

        if($con->query($sql) == TRUE && $con->query($sql1) == TRUE)
        {
            header("location:login.php");
            exit();
        }
        else
        {
            echo "<script>alert('Error occurred!');</script>";
        }
    }
}
?>
<!DOCTYPE html>
 <html lang="en"> 
  <head>
     <meta charset="UTF-8">
      <title>Admin Login</title>
       <link rel="stylesheet" href="style.css"> 
    </head> 
    <body> 
      <header> 
        <h1>🚲 Smart Parking System</h1>
      </header> 
      <section class="login-container"> 
        <div class="login-card"> 
          <h1>Register</h1> 
          <form method="post"> 
            <label>Student Name</label> 
            <input type="text" name="name" placeholder="Enter Name Correctly" required> 
            <label>Department Number</label> 
            <input type="text" name="dnum" placeholder="Enter Roll No" required> 
            <label>Department</label> 
            <input type="text" name="dname" placeholder="Enter Department" required> 
            <label>Vehicle No</label> 
            <input type="text" name="vehi" placeholder="Enter Vehicle Number" required> 
            <label>E-mail</label> 
            <input type="text" name="mail" placeholder="Enter E-mial  " required> 
            <label>Password</label> 
            <input type="password" name="pass" placeholder="Enter Password" required>
            <button type="submit" name="submit" style="Background-color: green">Register</button> 
            </form> <div class="login-links"> 
            <b>Already Registered? </b><a href="login.php"><u>Login</u></a>
        </div> 
      </div> 
    </section> 
  </body> 
  </html>

