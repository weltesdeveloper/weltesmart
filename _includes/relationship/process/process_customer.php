<?php
require_once '../../../_config/dbinfo.inc.php';

$sql = oci_parse($conn, "SELECT DISTINCT VPI.PROJECT_NO, VPI.CLIENT_NAME, VPI.CLIENT_ID, VPI.CLIENT_INIT FROM VW_PROJ_INFO@WELTESMART_WELTES_LINK VPI ORDER BY VPI.PROJECT_NO");
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
    $listCust = json_encode($res, JSON_PRETTY_PRINT);

    print_r($listCust);

    oci_free_statement($sql); // FREE THE STATEMENT
    oci_close($conn); // CLOSE CONNECTION, NEED TO REOPEN
}