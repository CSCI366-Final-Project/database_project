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
  <a class="active" href="product.php">Product</a>
  <a href="supplier.php">Supplier</a>
  <a href="inventory.php">Full Inventory</a>
  <a href="login_page.php">Logout</a>



</div>

<div style="padding-left:16px">
  <h2>Hello Admin</h2>
  <p>Update products here.</p>
</div>

</body>
</html>


<h1 align="center">Product Records</h1>


<?php

// Remember to replace 'username' and 'password'!
$conn = oci_connect('johollem', 'Feb651997', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=db2.ndsu.edu)(Port=1521)))(CONNECT_DATA=(SID=cs)))');


if (isset($_POST['submit']))
{
  $productname = $_POST['productname'];
  $price = $_POST['price'];
  $quantity = $_POST['quantity'];
  $departmentid = $_POST['departmentid'];


  $stid2 = oci_parse($conn, "INSERT INTO Product (pid, product_name, price, quantity, did) VALUES (cidSeq.nextval, :productname, :price, :quantity, :departmentid)");

  oci_bind_by_name($stid2, ':productname', $productname);
  oci_bind_by_name($stid2, ':price', $price);
  oci_bind_by_name($stid2, ':quantity', $quantity);
  oci_bind_by_name($stid2, ':departmentid', $departmentid);

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
	$query = "DELETE FROM Product ";  
	$query .="WHERE pid = '".$_POST["productid"]."' ";  
	$objParse = oci_parse($conn, $query);  
	oci_bind_by_name($query, ':productid', $productid);
	$objExecute = oci_execute($objParse, OCI_DEFAULT);  
	oci_commit($conn); //*** Commit Transaction ***//  
}


//put your query here

$query = "SELECT * FROM Product ORDER BY pid";

$stid = oci_parse($conn,$query);
oci_execute($stid,OCI_DEFAULT);

	// Format table layout
	print "<table cols=5 border=1>\n";
	print "<tr>\n";
	print "<th>Product ID</th>\n";
	print "<th>Product Name</th>\n";
	print "<th>Price</th>\n";
	print "<th>Quantity</th>\n";
	print "<th>Department ID</th>\n";
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
oci_free_statement($stid);
oci_close($conn);

echo "</table>\n";



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

<h3> Add Product </h3>
<form action="product.php" method="post">

  Product name:<br>
  <input type="text" name="productname">
  <br>
  Price:<br>
  <input type="text" name="price">
  <br>
  Quanity:<br>
  <input type="text" name="quantity">
  <br>
  Department ID:<br>
  <input type="text" name="departmentid">
  <br><br>
  <input class="submit" name="submit" type="submit" value="Add Product">
</form> 

<hr style="border-bottom: dotted 1px #000" />

<h3> Delete Product </h3>
<form action="product.php" form method="post">
    Product ID:<br>
    <input name="productid" type="text" size="25">
    <br><br>
<input name="delete" type="submit" value="Remove"/>
<br><br>
</form>




