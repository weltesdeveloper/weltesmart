<?php
require_once '../../../_config/dbinfo.inc.php';
require_once '../../../_config/misc.func.php';
session_start();
// GENERATE THE APPLICATION PAGE
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);

// 1. SET THE CLIENT IDENTIFIER AFTER EVERY CALL
// 2. USING UNIQUE VALUE FOR BACK END USER

$sql = oci_parse($conn, "SELECT DISTINCT MSI.INV_UNIT FROM MART_STOCK_INFO MSI");
$errExc = oci_execute($sql);

if (!$errExc){
    $e = oci_error($sql);
        print htmlentities($e['message']);
        print "\n<pre>\n";
        print htmlentities($e['sqltext']);
        printf("\n%".($e['offset']+1)."s", "^");
        print  "\n</pre>\n";
} else {
    
    $res = array();
    while ($row = oci_fetch_assoc($sql)){
        $res[] = $row;
    } 
    $listInv = json_encode($res, JSON_PRETTY_PRINT);
    
    print_r($listInv);
    
    oci_free_statement($sql); // FREE THE STATEMENT
    oci_close($conn); // CLOSE CONNECTION, NEED TO REOPEN
}