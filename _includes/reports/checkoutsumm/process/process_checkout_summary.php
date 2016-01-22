<?php
require_once '../../../../_config/dbinfo.inc.php';

$sql = oci_parse($conn, "SELECT MMC.MART_WR_ID, "
        . "MMC.MART_WR_DATE, "
        . "MMC.MART_WR_JOB, "
        . "MMC.MART_WR_SUBJOB, "
        . "MMC.MART_WR_CARRIER, "
        . "TO_CHAR(MMC.MART_WR_REMARK) MART_WR_REMARK "
        . "FROM MART_MST_CHKOUT MMC "
        . "ORDER BY MMC.MART_WR_ID ASC");
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