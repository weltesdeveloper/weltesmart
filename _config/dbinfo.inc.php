<?php
    // All connections to the database use these credentials
    // DO NOT MODIFY THIS PAGE UNLESS YOU WANT TO ADD MORE CREDENTIALS
    define("ORA_CON_UN", "WELTESADMIN");
    define("ORA_CON_PW", "weltespass");
    define("ORA_CON_DB", "192.168.100.195/WENMART");
    
    define("ORA_CON_DB2", "192.168.100.195/WENLOGINV");
    
    
    $conn_wenloginv = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB2) or die;
    
    
   /*$con = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
    if($con)
        echo 'success';
    else
        echo 'fail';
    
    $sql = "select count(inv_desc) from MASTER_INV where inv_desc = 'MIKO H'";
    $r = oci_parse($con, $sql);
    oci_execute($r);
    $row = oci_fetch_array($r);
    echo $row[0];*/
?>
