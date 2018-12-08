<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {
  margin: 0;
  font-family: Arial, Helvetica, sans-serif;
}

.topnav {
  overflow: hidden;
  background-color: #333;
}

.topnav a {
  float: left;
  color: #f2f2f2;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
}

.topnav a:hover {
  background-color: #ddd;
  color: black;
}

.topnav a.active {
  background-color: #4CAF50;
  color: white;
}
</style>

</head>

<body>

<div class="topnav">
  <a href="customer_signup.php">Customer Signup</a>
  <a href="clothing_store.php">Guest Checkout</a>
  <a class="active" href="admin_login.php">Admin Login</a>
</div>



<form action="admin_login.php" method ="post">
	<input type ="password" name="password" placeholder ="password"/>
	<input type ="submit" name="submit" value ="Login Admin"/>
</form>

</body>
</html>

<?php

if (isset($_POST['submit']))
{
	$password = $_POST['password'];

	if ($password == "admin123!") {
		print "Password is correct, welcome Admin.";
		header('Location: manager.php');

	}else{
		print "Password is incorrect.";

	}
}

?>
