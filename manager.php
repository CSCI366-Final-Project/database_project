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
  <a class="active" href="manager.php">Customers</a>
  <a href="department.php">Department</a>
  <a href="product.php">Product</a>
  <a href="supplier.php">Supplier</a>
  <a href="inventory.php">Full Inventory</a>
</div>

<div style="padding-left:16px">
  <h2>Hello Admin</h2>
  <p>Update customer records here.</p>
</div>

</body>
</html>
<h1 align="center">Customer Records</h1>
<?php

// Remember to replace 'username' and 'password'!
$conn = oci_connect('johollem', 'Feb651997', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=db2.ndsu.edu)(Port=1521)))(CONNECT_DATA=(SID=cs)))');



if (isset($_POST['submit']))
{
  $firstname = $_POST['firstname'];
  $lastname = $_POST['lastname'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $address = $_POST['address'];

  $stid2 = oci_parse($conn, "INSERT INTO Customer (cid, first_name, last_name, email, password, address) VALUES (cidSeq.nextval, :firstname, :lastname, :email, :password, :address)");

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
}

//method to delete customer record
if (isset($_POST['delete'])) {
	$query = "DELETE FROM Customer ";  
	$query .="WHERE cid = '".$_POST["ID"]."' ";  
	$objParse = oci_parse($conn, $query);  
	oci_bind_by_name($query, ':ID', $ID);
	$objExecute = oci_execute($objParse, OCI_DEFAULT);  
	oci_commit($conn); //*** Commit Transaction ***//  
}


//put your query here
$query = "SELECT * FROM Customer ORDER BY cid";
$stid = oci_parse($conn,$query);
oci_execute($stid,OCI_DEFAULT);

	// Format table layout
	print "<table cols=5 border=1>\n";
	print "<tr>\n";
	print "<th>ID</th>\n";
	print "<th>First Name</th>\n";
	print "<th>Last Name</th>\n";
	print "<th>Email</th>\n";
	print "<th>Password</th>\n";
	print "<th>Address</th>\n";
	print "</tr>";

  
//iterate through each row
while ($row = oci_fetch_array($stid,OCI_ASSOC+OCI_RETURN_NULLS)) 
{
    echo "<tr>\n";

    //iterate through each item in the row and echo it  
    foreach ($row as $item)    
    {
        echo "		<td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . " </td>\n";
    }   
	echo "</tr>\n";
}


echo "</table>\n";

oci_free_statement($stid);


oci_close($conn);

?>

<html>
<head>
<style>
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
</head></html>

<html>
<body>
<br>
<input type="button" value="print" onClick="window.print()"/>
<script type="text/javascript">
</script>
</body>
</html>


<hr style="border-bottom: dotted 1px #000" />

<h3> Add Customer </h3>
<form action="manager.php" method="post">
<!--
  <br>  ID:<br>
  <input type="text" name="ID">
  <br>
-->
  First name:<br>
  <input type="text" name="firstname">
  <br>
  Last name:<br>
  <input type="text" name="lastname">
  <br>
  Email:<br>
  <input type="text" name="email">
  <br>
  Password:<br>
  <input type="text" name="password">
  <br>
  Address:<br>
  <input type="text" name="address">
  <br><br>
  <input class="submit" name="submit" type="submit" value="Add Customer">
</form> 

<hr style="border-bottom: dotted 1px #000" />

<h3> Delete Customer </h3>
<form action="manager.php" form method="post">
    Customer ID:<br>
    <input name="ID" type="text" size="25">
    <br><br>
<input name="delete" type="submit" value="Remove"/>
<br><br>
</form>


