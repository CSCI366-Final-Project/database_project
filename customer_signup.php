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

table {
    border-collapse: collapse;
    width: 100%;
}

th, td {
    text-align: left;
    padding: 8px;
}

tr:nth-child(even){background-color: #f2f2f2}

th {
    background-color: #4CAF50;
    color: white;
}

</style>
</head>
<body>

<div class="topnav">
  <a class="active" href="customer_signup.php">Customer Signup</a>
  <a href="clothing_store.php">Guest Checkout</a>
  <a href="admin_login.php">Admin Login</a>


</div>

<h3> Signup </h3>
<form action="customer_signup.php" method="post">
  First name:<br>
  <input type="text" name="firstname">
  <br>
  Last name:<br>
  <input type="text" name="lastname">
  <br>
  Email:<br>
  <input type="email" pattern="+@.com" class=”form-control” name="email">
  <br>
  Password:<br>
  <input type="text" name="password">
  <br>
  Address:<br>
  <input type="text" name="address">
  <br><br>
  <input class="submit" name="submit" type="submit" value="Sign Up">
</form> 
<br>
</form>

</body>
</html>
<?php







// Remember to replace 'username' and 'password'!
$conn = oci_connect('johollem', 'Feb651997', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=db2.ndsu.edu)(Port=1521)))(CONNECT_DATA=(SID=cs)))');

//do these things when submit button is clicked
if (isset($_POST['submit']))
{ 
  //$ID = $_POST['ID'];
  $firstname = $_POST['firstname'];
  $lastname = $_POST['lastname'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $address = $_POST['address'];

  $stid2 = oci_parse($conn, "INSERT INTO Customer (cid, first_name, last_name, email, password, address) VALUES (cidSeq.nextval, :firstname, :lastname, :email, :password, :address)");

  //oci_bind_by_name($stid2, ':ID', $ID);
  oci_bind_by_name($stid2, ':firstname', $firstname);
  oci_bind_by_name($stid2, ':lastname', $lastname);
  oci_bind_by_name($stid2, ':email', $email);
  oci_bind_by_name($stid2, ':password', $password);
  oci_bind_by_name($stid2, ':address', $address);

  $r = oci_execute($stid2, OCI_NO_AUTO_COMMIT);

  // Commit the changes
  $r = oci_commit($conn);
  if (!$r) {
    $e = oci_error($conn);
    trigger_error(htmlentities($e['message']), E_USER_ERROR);
  }

  if (!$r) {    
    $e = oci_error($stid2);
    trigger_error(htmlentities($e['message']), E_USER_ERROR);
  }
header('Location: clothing_store.php');

}

  
//iterate through each row


oci_free_statement($stid);


oci_close($conn);

?>
