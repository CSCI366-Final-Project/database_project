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
  <a class="active" href="department.php">Department</a>
  <a href="product.php">Product</a>
  <a href="supplier.php">Supplier</a>
  <a href="inventory.php">Full Inventory</a>
  <a href="admin_login.php">Logout</a>



</div>

<div style="padding-left:16px">
  <h2>Hello Admin</h2>
  <p>Update departments here.</p>
</div>

</body>
</html>


<h1 align="center">Department Records</h1>


<?php

// Remember to replace 'username' and 'password'!
$conn = oci_connect('johollem', 'Feb651997', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=db2.ndsu.edu)(Port=1521)))(CONNECT_DATA=(SID=cs)))');


if (isset($_POST['submit']))
{
  $departmentname = $_POST['departmentname'];

  $stid2 = oci_parse($conn, "INSERT INTO Department (did, dep_name) VALUES (cidSeq.nextval, :departmentname)");

  oci_bind_by_name($stid2, ':departmentname', $departmentname);

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
	$query = "DELETE FROM Department ";  
	$query .="WHERE did= '".$_POST["ID"]."' ";  
	$objParse = oci_parse($conn, $query);  
	oci_bind_by_name($query, ':ID', $ID);
	$objExecute = oci_execute($objParse, OCI_DEFAULT);  
	oci_commit($conn); //*** Commit Transaction ***//  
}



//put your query here
$query = "SELECT * FROM Department ORDER BY did";

$stid = oci_parse($conn,$query);
oci_execute($stid,OCI_DEFAULT);

	// Format table layout
	print "<table cols=5 border=1>\n";
	print "<tr>\n";
	print "<th>Department ID</th>\n";
	print "<th>Department Name</th>\n";
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

<h3> Add Department </h3>
<form action="department.php" method="post">
  Department Name:<br>
  <input type="text" name="departmentname">
  <br><br>
  <input class="submit" name="submit" type="submit" value="Add Department">
</form> 

<hr style="border-bottom: dotted 1px #000" />

<h3> Delete Department </h3>
<form action="department.php" form method="post">
    Department ID:<br>
    <input name="ID" type="text" size="25">
    <br><br>
<input name="delete" type="submit" value="Remove"/>
<br><br>
</form>