<!DOCTYPE html>
<!-- 
Still needs:
 - order quantities in the table
 - adding the order to the DB
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
  <a class="active" href="manager.php">Customers</a>
  <a href="department.php">Department</a>
  <a href="product.php">Product</a>
  <a href="supplier.php">Supplier</a>
  <a href="inventory.php">Full Inventory</a>
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
/*
  //$ID = $_POST['ID'];
  //$firstname = $_POST['firstname'];
  //$lastname = $_POST['lastname'];
  //$email = $_POST['email'];
  //$password = $_POST['password'];
  //$address = $_POST['address'];

  //$stid2 = oci_parse($conn, "INSERT INTO Customer (cid, first_name, last_name, email, password, address) VALUES (cidSeq.nextval, :firstname, :lastname, :email, :password, :address)");
  //stid
  //oci_bind_by_name
  //oci_execute
  //oci_free_statement
  //oci_bind_by_name($stid2, ':ID', $ID);
  oci_bind_by_name($stid2, ':email', $_POST['email']);
  oci_bind_by_name($stid2, ':ccn', $_POST['ccn']);
  oci_bind_by_name($stid2, ':exp', $_POST['exp']);
  oci_bind_by_name($stid2, ':cvv', $_POST['cvv']);
  oci_bind_by_name($stid2, ':cardName', $_POST['cardName']);
  
*/

  $stid2 = oci_parse($conn, "SELECT cid FROM Customer WHERE email=:email");
  oci_bind_by_name($stid2, ':email', $_POST['email']);
  oci_execute($stid2,OCI_DEFAULT);
  $custId = oci_fetch_array($stid2, OCI_ASSOC+OCI_RETURN_NULLS);
  oci_free_statement(stid2);
  
  //Receipt
  //INSERT INTO Receipt (ridSeq.nextval, 0, $custId)
  $stid2 = oci_parse($conn, "INSERT INTO Receipt (ridSeq.nextval, 0, :custId)");
  oci_bind_by_name($stid2, ':custId', $custId);
  oci_execute($stid2,OCI_DEFAULT);
  //$receiptID = SELECT rid FROM Receipt
  $receiptID = oci_fetch_array($stid2, OCI_ASSOC+OCI_RETURN_NULLS);
  oci_free_statement(stid2);
	
  $price = 0.0;
  //OrderedProduct
  foreach($_POST as $key => $value) {
  
    //echo "<h2>Key: ".$key."; Value: ".$value.";</h2>";

	if (is_numeric($key)) {
    //echo "<h2>Key: ".$key."; Value: ".$value.";</h2>";
		if ($value != null){
      //echo "<h2>Key: ".$key."; Value: ".$value.";</h2>";
			if ($value>-10) {
        echo "<h2>Key: ".$key."; Value: ".$value.";</h2>";
        $stid2 = oci_parse($conn, "SELECT price FROM Product WHERE pid=:key");
				oci_bind_by_name($stid2, ':key', $key);
				oci_execute($stid2,OCI_DEFAULT);
        //$tempPrice = SELECT price FROM Product WHERE pid=$key
        //TODO: $tempPrice is not a number
				$tempPrice = oci_fetch_array($stid2, OCI_ASSOC+OCI_RETURN_NULLS);
        oci_free_statement(stid2);
        
				echo "<h2>Key: ".$key."; Value: ".$value.";</h2>";
				$price = $price + (intval($tempPrice) * intval($value));
        echo "<h2>Key: ".$key."; Value: ".$value.";</h2>";
        
				//INSERT INTO OrderedProduct (opidSeq.nextval, $key, $receiptID, $value)
				$stid2 = oci_parse($conn, "INSERT INTO OrderedProduct (opidSeq.nextval, :key, :receiptID, :value)");
				oci_bind_by_name($stid2, ':key', $key);
				oci_bind_by_name($stid2, ':receiptID', $receiptID);
				oci_bind_by_name($stid2, ':value', $value);
				oci_execute($stid2,OCI_DEFAULT);
        oci_free_statement(stid2);

        echo "<h2>Key: ".$key."; Value: ".$value.";</h2>";
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
  //INSERT INTO Payment (payment_idSeq.nextval, $_POST['ccn'], $_POST['exp'], $_POST['cvv'], $_POST['cardName'], $price, $receiptID)
  $stid2 = oci_parse($conn, "INSERT INTO Payment (payment_idSeq.nextval, :ccn, :exp, :cvv, :cardName'], :price, :receiptID)");
  oci_bind_by_name($stid2, ':ccn', $_POST['ccn']);
  oci_bind_by_name($stid2, ':exp', $_POST['exp']);
  oci_bind_by_name($stid2, ':cvv', $_POST['cvv']);
  oci_bind_by_name($stid2, ':cardName', $_POST['cardName']);
  oci_bind_by_name($stid2, ':price', $price);
  oci_bind_by_name($stid2, ':receiptID', $receiptID);
  oci_execute($stid2,OCI_DEFAULT);
  oci_free_statement(stid2);

  echo "<h1>Payment of $".$price." in process</h1>";
}
/* 
  //get IDs with quantities
  foreach($_POST as $key => $value) {
	if (is_numeric($key)) {
		if ($value != null){
			if ($value>0) {
				
			}
		}
	}
  }

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


//method to delete customer record
if (isset($_POST['delete'])) {
	$query = "DELETE FROM Customer ";  
	$query .="WHERE email = '".$_POST["email"]."' ";  
	$objParse = oci_parse($conn, $query);  
	oci_bind_by_name($query, ':email', $email);
	$objExecute = oci_execute($objParse, OCI_DEFAULT);  
	oci_commit($conn);
}
*/

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

<h3> Add Customer </h3>
<!--<form action="clothing_store.php" method="post">  moved up to include table inputs-->
<!--
  <br>  ID:<br>
  <input type="text" name="ID">
  <br>
-->
	
  Email:<br>
  <input type="text" name="email">
  <br>
  Credit Card Number:<br>
  <input type="text" name="ccn">
  <br>
  Card Expiration:<br>
  <input type="text" name="exp">
  <br>
  CVV:<br>
  <input type="text" name="cvv">
  <br>
  Name on Card:<br>
  <input type="text" name="cardName">
  <br><br>
  <input class="submit" name="submit" type="submit" value="Submit">
</form> 

</body>
</html>











