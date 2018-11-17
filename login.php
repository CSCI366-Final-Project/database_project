<html>
<body>
<?php 
session_start();// session for this user has been started
 $con = oci_connect('holme', 'Apr621997', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=db2.ndsu.edu)(Port=1521)))(CONNECT_DATA=(SID=cs)))');
if (!$con) {
$m = oci_error();
echo $m['message'], "\n";
//error fuction returns an oracle message.
exit; 
$query = "SELECT first_name FROM Customer WHERE email =
 :email and password=:password"; 
//query is sent to the db to fetch row id.
 $stid = oci_parse($con, $query);

if (isset($_POST['email']) || isset($_POST['password']))
{           
$email = $_POST['email'];
$password=$_POST['password'];
}

oci_bind_by_name($stid, ':email', $email);
oci_bind_by_name($stid, ':password', $password);

oci_execute($stid);
$row = oci_fetch_array($stid, OCI_ASSOC);

//oci_fetch_array returns a row from the db.

 if ($row) {
 $_SESSION['name']=$_POST['name'];
 echo"log in successful";
  }
 else 
  {
echo("The person with the email:" . $email . " is not found.
Please check the spelling and try again or check password");
exit;
  }
  }

$ID = $row['ID']; 
oci_free_statement($stid);
oci_close($con);
header('Location: manager.php');
?>
</body>
</html>
