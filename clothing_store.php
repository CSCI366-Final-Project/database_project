<!DOCTYPE html>
<!-- 
Still needs:
 - input validation in the form
-->
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
  <a class="active" href="clothing_store.php">Submit Order</a>
  <a href="login_page.php">Logout</a>

</div>

<div style="padding-left:16px">
  <h2>Hello Customer</h2>
  <p>Create an order here.</p>
</div>


<h1 align="center">Products</h1>
<form action="clothing_store.php" method="post">
<?php
// Remember to replace 'username' and 'password'!
$conn = oci_connect('johollem', 'Feb651997', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=db2.ndsu.edu)(Port=1521)))(CONNECT_DATA=(SID=cs)))');



if (isset($_POST['submit']))
{
  $stid2 = oci_parse($conn, "SELECT cid FROM Customer WHERE email=:email");
  oci_bind_by_name($stid2, ':email', trim($_POST['email']));
  oci_execute($stid2,OCI_DEFAULT);
  $custId = array_pop(array_reverse(oci_fetch_array($stid2, OCI_ASSOC+OCI_RETURN_NULLS)));
  oci_free_statement(stid2);
  //if($custId==null) {
    echo "<h1>Email ".$custId." not found</h1>";
  //}
  
  //Receipt
  //INSERT INTO Receipt (ridSeq.nextval, 0, $custId)
  $stid2 = oci_parse($conn, "INSERT INTO Receipt Values (ridSeq.nextval, 0, :custId)");
  oci_bind_by_name($stid2, ':custId', $custId);
  oci_execute($stid2,OCI_DEFAULT);
  //$receiptID = SELECT rid FROM Receipt
  $receiptID = array_pop(array_reverse(oci_fetch_array($stid2, OCI_ASSOC+OCI_RETURN_NULLS)));
  oci_free_statement(stid2);
	
  $price = 0.0;
  //OrderedProduct
  echo "<h1>Order Submitted:</h1>";
  foreach($_POST as $key => $value) {
  
    //echo "<h2>Key: ".$key."; Value: ".$value.";</h2>";

	if (is_numeric($key)) {
    //echo "<h2>Key: ".$key."; Value: ".$value.";</h2>";
		if ($value != null){
      //echo "<h2>Key: ".$key."; Value: ".$value.";</h2>";
			if ($value>0) {
        echo "<h4>Product: ".$key."; Quantity: ".$value.";</h2>";
        $stid2 = oci_parse($conn, "SELECT price FROM Product WHERE pid=:key");
				oci_bind_by_name($stid2, ':key', $key);
				oci_execute($stid2,OCI_DEFAULT);
        //$tempPrice = SELECT price FROM Product WHERE pid=$key
        //TODO: $tempPrice is not a number
				$tempPrice = array_pop(array_reverse(oci_fetch_array($stid2, OCI_ASSOC+OCI_RETURN_NULLS)));
        oci_free_statement(stid2);
        
				//echo "<h2>Key: ".$key."; Value: ".$value.";</h2>";
				$price = $price + (floatval($tempPrice) * intval($value));
        //echo "<h2>Key: ".$key."; Value: ".$value.";</h2>";
        
				//INSERT INTO OrderedProduct (opidSeq.nextval, $key, $receiptID, $value)
				$stid2 = oci_parse($conn, "INSERT INTO OrderedProduct Values (opidSeq.nextval, :value, :receiptID, :key)");
				oci_bind_by_name($stid2, ':key', intval($key));
				oci_bind_by_name($stid2, ':receiptID', $receiptID);
				oci_bind_by_name($stid2, ':value', $value);
				oci_execute($stid2,OCI_DEFAULT);
        oci_free_statement(stid2);

        //echo "<h2>Key: ".$key."; Value: ".$value.";</h2>";
			}
		}
	}
  }
  //UPDATE Receipt SET price = $price WHERE rid=$receiptID
  $stid2 = oci_parse($conn, "UPDATE Receipt SET price = :price WHERE rid=:receiptID");
  oci_bind_by_name($stid2, ':price', $price);
  oci_bind_by_name($stid2, ':receiptID', $receiptID);
  oci_execute($stid2,OCI_DEFAULT);
  oci_free_statement(stid2);
  
  //Payment
  if ($_POST['expMonth']>9){
    $expMonth = (string)$_POST['expMonth'];
  } else {
    $expMonth = '0' . (string)$_POST['expMonth'];
  }
  //INSERT INTO Payment VALUES (payment_idSeq.nextval, :ccn, TRUNC(TO_DATE(':expYear/:expMonth/03 00:00:00', 'yyyy/mm/dd hh24:mi:ss')), :cvv, :cardName, :price, :receiptID)
  //INSERT INTO Payment VALUES (payment_idSeq.nextval, '1234', TRUNC(TO_DATE('2005/06/03 00:00:00', 'yyyy/mm/dd hh24:mi:ss')), 123, 'Jordan Holleman', 46.5, 1)
  $stid2 = oci_parse($conn, "INSERT INTO Payment VALUES (payment_idSeq.nextval, :ccn, TRUNC(TO_DATE(':expYear/:expMonth/03 00:00:00', 'yyyy/mm/dd hh24:mi:ss')), :cvv, :cardName, :price, :receiptID)");
  oci_bind_by_name($stid2, ':ccn', $_POST['ccn']);
  oci_bind_by_name($stid2, ':expMonth', $expMonth);
  oci_bind_by_name($stid2, ':expYear', $_POST['expYear']);
  oci_bind_by_name($stid2, ':cvv', $_POST['cvv']);
  oci_bind_by_name($stid2, ':cardName', $_POST['cardName']);
  oci_bind_by_name($stid2, ':price', $price);
  oci_bind_by_name($stid2, ':receiptID', $receiptID);
  oci_execute($stid2,OCI_DEFAULT);
  oci_free_statement(stid2);

  echo "<h1>Payment of $".$price." in process</h1>";
}






//put your query here
$query = "SELECT * FROM Product ORDER BY pid";

$stid = oci_parse($conn,$query);
oci_execute($stid,OCI_DEFAULT);

	// Format table layout
	print "<table cols=6 border=1>\n";
	print "<tr>\n";
	print "<th>ID</th>\n";
	print "<th>Name</th>\n";
	print "<th>Price</th>\n";
	print "<th>In Stock</th>\n";
	print "<th>Department</th>\n";
	print "<th>Quantity to Order</th>\n";
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
	$arr = array_values($row);
	echo '<td><input type="number" name="'.$arr[0].'" value="0" min="0" step="1"></td>';
	echo "</tr>\n";
}
oci_free_statement($stid);
oci_close($conn);

echo "</table>\n";



?>


<br>
<input type="button" name="print" value="print" onClick="window.print()"/>




<hr style="border-bottom: dotted 1px #000" />

<h3> Payment (not secure, for testing only) </h3>
<!--<form action="clothing_store.php" method="post">  moved up to include table inputs-->
<!--
  <br>  ID:<br>
  <input type="text" name="ID">
  <br>
-->
	
  Email:<br/>
  <input type="text" name="email"><br/>
  <br/>
  Credit Card Number:<br/>
  <input type="text" name="ccn"><br/>
  <br/>
  Card Expiration Month:<br/>
  <input type="number" name="expMonth" min="1" step="1" max="12"><br/>
  <br/>
  Card Expiration Year:<br/>
  <input type="number" name="expYear" min="2000" step="1"><br/>
  <br/>
  CVV:<br/>
  <input type="text" name="cvv"><br/>
  <br/>
  Name on Card:<br/>
  <input type="text" name="cardName"><br/>
  <br/><br/>
  <input class="submit" name="submit" type="submit" value="Submit">
</form> 
<br/>
<br/>
</body>
</html>











