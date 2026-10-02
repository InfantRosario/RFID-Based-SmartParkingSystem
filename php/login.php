<?php
session_start();
if(isset($_POST['submit']))
  {
    $uid=$_POST['uid'];
    $pass=$_POST['pass'];
    $con=new mysqli("localhost","root","Rio@10062006","project");
    if($con->connect_error)
      {
        die("Connection failed".$con->connect_error);
      }
    $sql="SELECT * FROM login WHERE username='$uid' AND password='$pass'";
    $result=$con->query($sql);
    if($result->num_rows>0)
      {
        $row=$result->fetch_assoc();
        $_SESSION['uid']=$row['username'];
        if($row['username']== "Admin")
          {
            header("location:adindex.php");
            exit();
          }
        else{
          header("location:stdprofile.php");
          exit();
        }
      }
    else{
      echo"<script>alert('invalid password')</script>";
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
  <h1>🚲 Smart Parking System</h1>
</header>

<section class="login-container">

  <div class="login-card">
    <h2>Log-in</h2>

    <form method="post">
      <label>Roll Number</label>
      <input type="text" name="uid" placeholder="Enter Roll Number" required>

      <label>Password</label>
      <input type="password" name="pass" placeholder="Enter Password" required>

      <button type="submit" name="submit">Login</button>
    </form>

    <div class="login-links">
      <label>Don't you have an Account?<a href="register.php"><u>Register here</u></a></label>
    </div>
  </div>

</section>
</body>
</html>