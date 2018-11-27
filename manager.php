<h1>Customer Records</h1>


<?php

// Remember to replace 'username' and 'password'!
$conn = oci_connect('holme', 'Apr621997', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=db2.ndsu.edu)(Port=1521)))(CONNECT_DATA=(SID=cs)))');

//put your query here
$query = "SELECT * FROM Customer";
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

$ID = $_POST['ID'];
$first_name = $_POST['firstname'];
$last_name = $_POST['lastname'];
$email = $_POST['email'];
$password = $_POST['password'];
$address = $_POST['address'];

$stid2 = oci_parse($conn, "INSERT INTO Customer (cid,first_name,last_name,email,password,address) VALUES ('$ID', '$first_name', '$last_name', '$email', '$password', $'address')");

// The OCI_NO_AUTO_COMMIT flag tells Oracle not to commit the INSERT immediately
// Use OCI_DEFAULT as the flag for PHP <= 5.3.1.  The two flags are equivalent
$r = oci_execute($stid, OCI_NO_AUTO_COMMIT);
if (!$r) {    
    $e = oci_error($stid);
    trigger_error(htmlentities($e['message']), E_USER_ERROR);
}

// Commit the changes
$r = oci_commit($conn);
if (!$r) {
    $e = oci_error($conn);
    trigger_error(htmlentities($e['message']), E_USER_ERROR);
}


oci_free_statement($stid);
oci_close($conn);

echo "</table>\n";



?>
<html>
<body>
<br>
<input type="button" value="print" onClick="window.print()"/>
<script type="text/javascript">
</script>
</body>
</html>

<form action="manager.php">
  <br>
  ID:<br>
  <input type="text" name="ID">
  <br>
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
  Addresss:<br>
  <input type="text" name="address">
  <br><br>
  <input type="submit" value="Add Customer">
</form> 
