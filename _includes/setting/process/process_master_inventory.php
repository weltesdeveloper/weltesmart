<?php

require_once '../../../_config/dbinfo.inc.php';

$sql = oci_parse($conn, "SELECT MI.INV_ID, "
        . "MI.INV_DESC, "
        . "MI.INV_CAT, "
        . "MI.INV_TYPE, "
        . "MI.INV_BRAND, "
        . "MI.INV_UNIT, "
        . "MI.INV_GRD, "
        . "MI.INV_WM_SELECT "
        . "FROM MASTER_INV@WELTESMART_WENLOGINV_LINK MI "
        . "WHERE MI.INV_CAT <> 'SERVICES' AND MI.INV_CAT <> 'ACCOMMODATION' "
        . "ORDER BY MI.INV_DESC ASC");
$errExc = oci_execute($sql);

if (!$errExc) {
    $e = oci_error($sql);
    print htmlentities($e['message']);
    print "\n<pre>\n";
    print htmlentities($e['sqltext']);
    printf("\n%" . ($e['offset'] + 1) . "s", "^");
    print "\n</pre>\n";
} else {

    $res = array();
    while ($row = oci_fetch_assoc($sql)) {
        $res[] = $row;
    }
    $list = json_encode($res, JSON_PRETTY_PRINT);

    print_r($list);

    oci_free_statement($sql); // FREE THE STATEMENT
    oci_close($conn); // CLOSE CONNECTION, NEED TO REOPEN
}