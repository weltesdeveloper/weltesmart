<?php

// All connections to the database use these credentials
// DO NOT MODIFY THIS PAGE UNLESS YOU WANT TO ADD MORE CREDENTIALS
define("ORA_CON_UN", "WELTESADMIN");
define("ORA_CON_PW", "weltespass");


// koneksi ke weltes
define("ORA_CON_DB3", "192.168.100.70/WELTES");
$conn_weltes = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB3) or die;

// koneksi ke logistic
define("ORA_CON_DB2", "192.168.100.68/WENLOGINV");
$conn_wenloginv = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB2) or die;

// koneksi ke wenmart
define("ORA_CON_DB", "192.168.100.71/WENMART");
$conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB) or die;


/* $con = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
  if($con)
  echo 'success';
  else
  echo 'fail';

  $sql = "select count(inv_desc) from MASTER_INV where inv_desc = 'MIKO H'";
  $r = oci_parse($con, $sql);
  oci_execute($r);
  $row = oci_fetch_array($r);
  echo $row[0]; */

session_start();

if (!isset($_SESSION['userlogin'])) {
    echo <<< EOD
   <h1>You are UNAUTHORIZED !</h1>
   <p>INVALID usernames/passwords<p>
   <p><a href="/WeltesMart/_includes/login/login_view.php">LOGIN PAGE</a></p>

EOD;
    exit;
}
// 1. SET THE CLIENT IDENTIFIER AFTER EVERY CALL
// 2. USING UNIQUE VALUE FOR BACK END USER
oci_set_client_identifier($conn, $_SESSION['userlogin']);
$username = htmlentities($_SESSION['userlogin'], ENT_QUOTES);
$user_role = $_SESSION['rolelogin'];
?>
