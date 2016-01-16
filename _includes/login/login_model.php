<?php

require_once('../../_config/Dbconfig.php');
//require_once('../../_config/misc.func.php');
require_once('../../_config/hash.pwd.php');
$dbconfig = new Dbconfig();
$conn = $dbconfig->WenmartConn();
//$conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB) or die;

$pass = $_POST['password'];
$username = $_POST['username'];

oci_set_client_identifier($conn, 'admin');
$sql = oci_parse($conn, "SELECT WMU.MART_PASS HASHPASS,
                            WMU.MART_FULL_NAME FULLNAME,
                            WMR.MART_ROLE_DESC COMP_ROLE_COMPLETE
                       FROM MART_USER WMU
                            INNER JOIN MART_ROLE WMR
                               ON WMR.MART_ROLE_ID = WMU.MART_ROLE_ID
                      WHERE WMU.MART_FULL_NAME = :finemail");

oci_bind_by_name($sql, ":finemail", $username);
oci_define_by_name($sql, "COMP_ROLE_COMPLETE", $role);
oci_define_by_name($sql, "HASHPASS", $hashpass);

oci_execute($sql);

$r = oci_fetch_array($sql, OCI_ASSOC);

$passMatchInt = validate_password($pass, $hashpass);
if ($passMatchInt == 1) {
    session_start();
    $_SESSION['userlogin'] = $username;
    $_SESSION['rolelogin'] = $role;
    echo ('<script>location.href="../main.php"</script>');
} else {
    session_destroy();
    echo ('<script>alert("LOGIN FAILED !!! \nPLEASE ENTER APPROPRIATE USER NAME AND PASSWORD")</script>');
    echo ('<script>location.href="../../index.php"</script>');
}

$globalName = SingleQryFld("SELECT WMS.SETTING_VALUE_STRING FROM MART_SETTINGS WMS WHERE WMS.SETTING_DESC = 'GLOBAL_NAME'", $conn);
$_SESSION['globalname'] = $globalName;
