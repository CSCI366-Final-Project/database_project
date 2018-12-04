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
  <a href="manager.php">Customers</a>
  <a href="department.php">Department</a>
  <a href="product.php">Product</a>
  <a class="active" href="supplier.php">Supplier</a>
  <a href="inventory.php">Full Inventory</a>
  <a href="login_page.php">Logout</a>

</div>

<div style="padding-left:16px">
  <h2>Hello Admin</h2>
  <p>Update suppliers here.</p>
</div>

</body>
</html>


<h1 align="center" >Supplier Records</h1>


<?php

// Remember to replace 'username' and 'password'!
$conn = oci_connect('johollem', 'Feb651997', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=db2.ndsu.edu)(Port=1521)))(CONNECT_DATA=(SID=cs)))');


if (isset($_POST['submit']))
{
  $suppliername = $_POST['suppliername'];
  $supplieraddress = $_POST['supplieraddress'];
  $productid = $_POST['productid'];

  $stid2 = oci_parse($conn, "INSERT INTO Supplier (sid, s_name, s_address, pid) VALUES (cidSeq.nextval, :suppliername, :supplieraddress, :productid)");

  oci_bind_by_name($stid2, ':suppliername', $suppliername);
  oci_bind_by_name($stid2, ':supplieraddress', $supplieraddress);
  oci_bind_by_name($stid2, ':productid', $productid);

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

if (isset($_POST['delete'])) {
	$query = "DELETE FROM Supplier ";  
	$query .="WHERE sid= '".$_POST["ID"]."' ";  
	$objParse = oci_parse($conn, $query);  
	oci_bind_by_name($query, ':ID', $ID);
	$objExecute = oci_execute($objParse, OCI_DEFAULT);  
	oci_commit($conn); //*** Commit Transaction ***//  
}


//put your query here


$query = "SELECT * FROM Supplier ORDER BY sid";
$stid = oci_parse($conn,$query);
oci_execute($stid,OCI_DEFAULT);


	// Format table layout
	print "<table cols=5 border=1>\n";
	print "<tr>\n";
	print "<th>Supplier ID</th>\n";
	print "<th>Supplier Name</th>\n";
	print "<th>Supplier Address</th>\n";
	print "<th>Product ID</th>\n";
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
<body>
<input type="button" value="print" onClick="window.print()"/>
<script type="text/javascript">
</script>
</body>
</html>

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
<hr style="border-bottom: dotted 1px #000" />

<h3> Add Supplier </h3>
<form action="supplier.php" method="post">
  Supplier Name:<br>
  <input type="text" name="suppliername">
  <br>
  Supplier Address:<br>
  <input type="text" name="supplieraddress"> <br>
  Product ID:<br>
  <input type="text" name="productid">
  <br><br>
  <input class="submit" name="submit" type="submit" value="Add Supplier">
</form> 

<hr style="border-bottom: dotted 1px #000" />

<h3> Delete Supplier </h3>
<form action="supplier.php" form method="post">
    Supplier ID:<br>
    <input name="ID" type="text" size="25">
    <br><br>
<input name="delete" type="submit" value="Remove"/>
<br><br>
</form>

