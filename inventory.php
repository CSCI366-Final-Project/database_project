<h1>Customer Records</h1>


<?php

// Remember to replace 'username' and 'password'!
$conn = oci_connect('holme', 'Apr621997', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=db2.ndsu.edu)(Port=1521)))(CONNECT_DATA=(SID=cs)))');

//put your query here
$query = "SELECT * FROM Department, Product, Supplier";
$stid = oci_parse($conn,$query);
oci_execute($stid,OCI_DEFAULT);

	// Format table layout
	print "<table cols=5 border=1>\n";
	print "<tr>\n";
	print "<th>Department Name</th>\n";
	print "<th>Product Name</th>\n";
	print "<th>Price</th>\n";
	print "<th>Quantity</th>\n";
	print "<th>Supplier Name</th>\n";
	print "<th>Supplier Address</th>\n";
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
