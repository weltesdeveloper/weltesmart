<?php

require_once '../../../_config/dbinfo.inc.php';
require_once '../../../_config/misc.func.php';

switch ($_POST['action']) {
    case "loading_first":
        $job = array();
        $sql = "SELECT DISTINCT PROJECT_NO FROM VW_PROJ_INFO@WELTESMART_WELTES_LINK ORDER BY PROJECT_NO ASC";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        while ($row = oci_fetch_array($parse)) {
            array_push($job, $row);
        }
        
//        $spv = array();
//        $spvSql = "SELECT DISTINCT UPPER(MART_WR_SPV_SIGN) MART_WR_SPV_SIGN FROM MART_MST_CHKOUT ORDER BY UPPER(MART_WR_SPV_SIGN) ASC";
//        $spvParse = oci_parse($conn, $spvSql);
//        oci_execute($spvParse);
//        while ($row2 = oci_fetch_array($spvParse)) {
//            array_push($spv, $row2);
//        }
//
//        $pembawa = array();
//        $pembawaSql = "SELECT DISTINCT UPPER(MART_WR_CARRIER) MART_WR_CARRIER FROM MART_MST_CHKOUT ORDER BY UPPER(MART_WR_CARRIER) ASC";
//        $pembawaParse = oci_parse($conn, $pembawaSql);
//        oci_execute($pembawaParse);
//        while ($row3 = oci_fetch_array($pembawaParse)) {
//            array_push($pembawa, $row3);
//        }
        $response = array(
            "job" => $job
//            "spv" => $spv,
//            "pembawa" => $pembawa
        );
        echo json_encode($response);
        break;
        
    case "show_history":
        $start = $_POST['start'];
        $end = $_POST['end'];
        $job = $_POST['job'];
        if ($job == "") {
            $job = '%';
        }
        $sql = "SELECT TO_CHAR(MCI.MART_CHECKIN_DATE,'DD-MONTH-YYYY') MART_CHECKIN_DATE, "
                . "MCI.INV_DESC, "
                . "MCI.MART_CHECKIN_INV_QTY || ' ' || MCI.MART_CHECKIN_INV_UNIT AS UNIT_ASLI,  "
                . "MCI.QTY_TERKECIL || ' ' || MCI.UNIT_TERKECIL AS UNIT_TERKECIL,"
                . "VSI.PROJECT_NO  "
                . "FROM MART_CHECKIN_INFO MCI "
                . "INNER JOIN VW_STOCK_INFO@WELTESMART_WENLOGINV_LINK VSI "
                . "ON VSI.TRANSACTION_ID = MCI.MART_CHECKIN_ID "
                . "WHERE MCI.MART_CHECKIN_DATE "
                . "BETWEEN TO_DATE('$start', 'MM/DD/YYYY') "
                . "AND TO_DATE('$end', 'MM/DD/YYYY') "
                . "ORDER BY VSI.PROJECT_NO ASC";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        $response = array();
        while ($row = oci_fetch_array($parse)) {
            array_push($response, $row);
        }
        echo json_encode($response);
        break;
    default:
        break;
}