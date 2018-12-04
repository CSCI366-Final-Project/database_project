<?php
        session_start();
        global $conn;
	$conn = oci_connect('johollem', 'Feb651997', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=db2.ndsu.edu)(Port=1521)))(CONNECT_DATA=(SID=cs)))');
        if(isset($_POST['submit'])){
            $email = $_POST['email'];
            $password = $_POST['password'];
            $s = oci_parse($conn, "select email,password from Customer where email='$email' and password='$password'");       
            oci_execute($s);
            $row = oci_fetch_all($s, $res);
            if($row){
                    $_SESSION['email']=$email;
                    $_SESSION['time_start_login'] = time();
                    header("location: manager.php");
            }else{

                echo "Incorrect username or password";
            }
        }



     ?>