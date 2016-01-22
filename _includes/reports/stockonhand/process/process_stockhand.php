<?php

require_once '../../../../_config/dbinfo.inc.php';
require_once '../../../../_config/misc.func.php';

switch ($_POST['action']) {
    case "select_inv":
        $inv_type = $_POST['invType'];
        $type = "'" . implode("','", $inv_type) . "'";
        $typex = str_replace('%', '', $type);
        $query2 = "SELECT MSI.* FROM MART_STOCK_INFO MSI WHERE MSI.INV_TYPE LIKE '%' ORDER BY MSI.INV_DESC ASC";
        echo $query2;
//        $sql = oci_parse($conn, $query2);
//        $errExc = oci_execute($sql);
        break;
}