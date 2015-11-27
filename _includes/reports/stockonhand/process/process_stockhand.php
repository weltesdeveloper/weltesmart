<?php

require_once '../../../../_config/dbinfo.inc.php';
require_once '../../../../_config/FunctionAct.php';
session_start();
// GENERATE THE APPLICATION PAGE
$conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB) or die;

// 1. SET THE CLIENT IDENTIFIER AFTER EVERY CALL
// 2. USING UNIQUE VALUE FOR BACK END USER
oci_set_client_identifier($conn, $_SESSION['userlogin']);
$username = htmlentities($_SESSION['userlogin'], ENT_QUOTES);
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