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
  <a class="active" href="login_page.php">Sign In</a>
</div>

<input type = "email" id=”email” pattern="+@.com" class=”form-control” placeholder=email name=”email”/>
<input type = "password" id=”password” placeholder = "password" password = "password"/>
<form action="manager.php">
	<input type="submit" value ="Login Admin"/>
</form>
<form action="clothing_store.php">
	<input type="submit" value="Login Customer"/>
</form>

<a href="customer_signup.php">Register</a>
</body>
</html>
