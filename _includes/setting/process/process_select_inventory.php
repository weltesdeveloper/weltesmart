<?php

require_once '../../../_config/dbinfo.inc.php';

$check = $_POST['check_id'];
$inv_id = $_POST['inv_id'];

switch ($check) {
    case 'UPDATE' :
        $sql = oci_parse($conn, "UPDATE MASTER_INV@WELTESMART_WENLOGINV_LINK MI SET MI.INV_WM_SELECT = '1' WHERE MI.INV_ID = '$inv_id'");
        $errExc = oci_execute($sql);
        
        if (!$errExc) {
            $e = oci_error($sql);
            print htmlentities($e['message']);
            print "\n<pre>\n";
            print htmlentities($e['sqltext']);
            printf("\n%" . ($e['offset'] + 1) . "s", "^");
            print "\n</pre>\n";
        } else {
            print 'SUCCESSFULLY UPDATED';
            oci_commit($conn);
            oci_free_statement($sql); // FREE THE STATEMENT
            oci_close($conn); // CLOSE CONNECTION, NEED TO REOPEN
        }
    break;

    case 'REMOVE' :
        $sql = oci_parse($conn, "UPDATE MASTER_INV@WELTESMART_WENLOGINV_LINK MI SET MI.INV_WM_SELECT = '0' WHERE MI.INV_ID = '$inv_id'");
        $errExc = oci_execute($sql);
        
        if (!$errExc) {
            $e = oci_error($sql);
            print htmlentities($e['message']);
            print "\n<pre>\n";
            print htmlentities($e['sqltext']);
            printf("\n%" . ($e['offset'] + 1) . "s", "^");
            print "\n</pre>\n";
        } else {
            print 'SUCCESSFULLY UPDATED';
            oci_commit($conn);
            oci_free_statement($sql); // FREE THE STATEMENT
            oci_close($conn); // CLOSE CONNECTION, NEED TO REOPEN
        }
    break;
}