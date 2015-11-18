<?php

require_once '../../../_config/dbinfo.inc.php';
require_once '../../../_config/FunctionAct.php';
session_start();
// GENERATE THE APPLICATION PAGE
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);

// 1. SET THE CLIENT IDENTIFIER AFTER EVERY CALL
// 2. USING UNIQUE VALUE FOR BACK END USER
oci_set_client_identifier($conn, $_SESSION['userlogin']);
switch ($_POST['action']) {
    case "get_pr":
        $response = array();
        $sql = "SELECT DISTINCT MART_PR_NO FROM MART_PR_INFO ORDER BY MART_PR_NO ASC";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        while ($row = oci_fetch_array($parse)) {
            array_push($response, $row);
        }
        echo json_encode($response);
        break;

    case "show_revise":
        $response = array();
        $pr_no = $_POST['pr_no'];
        $sql = "SELECT MART_PR_NO, "
                . "MART_PR_DATE, "
                . "MART_PR_SYSDATE, "
                . "MART_PR_STAT, "
                . "MART_PR_DELIV, "
                . "TO_CHAR(MART_PR_REMARKS) MART_PR_REMARKS, "
                . "MART_PR_REV, "
                . "MART_PR_REV, "
                . "MART_PR_DEL_REMARKS, "
                . "MART_INV_ID, "
                . "INV_DESC, "
                . "TO_CHAR(MART_INV_REMARK) MART_INV_REMARK, "
                . "MART_INV_UNIT, "
                . "MART_INV_QTY, "
                . "BRAND_NAME, "
                . "MART_ID_BRAND, "
                . "MART_PR_SIGN "
                . "FROM MART_PR_INFO "
                . "WHERE MART_PR_NO = '$pr_no'";
//        echo "$sql";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        while ($row1 = oci_fetch_assoc($parse)) {
            array_push($response, $row1);
        }
        echo json_encode($response);
        break;
}