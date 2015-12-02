<?php

require_once '../../../_config/dbinfo.inc.php';
require_once '../../../_config/misc.func.php';
session_start();
// GENERATE THE APPLICATION PAGE
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);

// 1. SET THE CLIENT IDENTIFIER AFTER EVERY CALL
// 2. USING UNIQUE VALUE FOR BACK END USER
oci_set_client_identifier($conn, $_SESSION['userlogin']);
switch ($_POST['action']) {
    case "list_pr":
        $start = $_POST['start'];
        $end = $_POST['end'];
        $prno = $_POST['prno'];
        $response = array();
        $sql = "SELECT DISTINCT MART_PR_DATE, "
                . "MART_PR_NO, MART_INV_ID, "
                . "INV_DESC, "
                . "MART_INV_QTY||'/'||MART_INV_UNIT MART_INV_QTY, "
                . "MART_PR_SIGN,"
                . "TO_CHAR(MART_INV_REMARK)MART_INV_REMARK "
                . "FROM MART_PR_INFO "
                . "WHERE MART_PR_DATE BETWEEN TO_DATE('$start', 'MM/DD/YYYY') "
                . "AND TO_DATE('$end', 'MM/DD/YYYY') "
                . "AND MART_PR_NO LIKE '$prno' "
                . "ORDER BY MART_PR_NO ASC";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        while ($row2 = oci_fetch_array($parse)) {
            array_push($response, $row2);
        }
        echo json_encode($response);
        break;

    default:
        $arrayTanggal = array();
        $tanggalSql = "SELECT TO_CHAR(MIN (MART_PR_DATE), 'MM/DD/YYYY') MIN_MART_PR_DATE, "
                . "TO_CHAR(MAX (MART_PR_DATE), 'MM/DD/YYYY') MAX_MART_PR_DATE "
                . "FROM MART_MST_PR";
        $tanggalParse = oci_parse($conn, $tanggalSql);
        oci_execute($tanggalParse);
        while ($row = oci_fetch_array($tanggalParse)) {
            array_push($arrayTanggal, $row);
        }

        $arrayPR = array();
        $prItemSql = "SELECT MART_PR_NO FROM MART_MST_PR ORDER BY MART_PR_NO ASC";
        $prItemParse = oci_parse($conn, $prItemSql);
        oci_execute($prItemParse);
        while ($row1 = oci_fetch_array($prItemParse)) {
            array_push($arrayPR, $row1);
        }

        $response = array(
            "tanggal" => $arrayTanggal,
            "prno" => $arrayPR
        );

        echo json_encode($response);
        break;
}

