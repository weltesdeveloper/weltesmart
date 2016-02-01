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
        $spv = array();
        $spvSql = "SELECT DISTINCT UPPER(MART_WR_SPV_SIGN) MART_WR_SPV_SIGN FROM MART_MST_CHKOUT ORDER BY UPPER(MART_WR_SPV_SIGN) ASC";
        $spvParse = oci_parse($conn, $spvSql);
        oci_execute($spvParse);
        while ($row2 = oci_fetch_array($spvParse)) {
            array_push($spv, $row2);
        }

        $pembawa = array();
        $pembawaSql = "SELECT DISTINCT UPPER(MART_WR_CARRIER) MART_WR_CARRIER FROM MART_MST_CHKOUT ORDER BY UPPER(MART_WR_CARRIER) ASC";
        $pembawaParse = oci_parse($conn, $pembawaSql);
        oci_execute($pembawaParse);
        while ($row3 = oci_fetch_array($pembawaParse)) {
            array_push($pembawa, $row3);
        }
        $response = array(
            "job" => $job,
            "spv" => $spv,
            "pembawa" => $pembawa
        );
        echo json_encode($response);
        break;
    case "getsubjob":
        $job = $_POST['job'];
        $subjob = array();
        $sql = "SELECT DISTINCT PROJECT_NAME_NEW FROM VW_PROJ_INFO@WELTESMART_WELTES_LINK WHERE PROJECT_NO = '$job' ORDER BY PROJECT_NAME_NEW  ASC";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        while ($row = oci_fetch_array($parse)) {
            array_push($subjob, $row);
        }
        $query = SingleQryFld("SELECT MAX(MART_WR_ID) FROM MART_MST_CHKOUT WHERE MART_WR_JOB = '$job'", $conn);
        $nomer = 0;
        if ($query != "null") {
            $nomer = str_replace("WM-WH-$job-", "", $query);
            $nomer = intval($nomer) + 1;
        } else {
            $nomer = intval($nomer) + 1;
        }
        $id_checkout = "WM-WH-$job-" . str_pad($nomer, 5, 0, STR_PAD_LEFT);
        $response = array(
            "subjob" => $subjob,
            "wh_id" => $id_checkout
        );
        echo json_encode($response);
        break;

    case "show_history":
        $start = $_POST['start'];
        $end = $_POST['end'];
        $job = $_POST['job'];
        $spv = $_POST['spv'];
        $pembawa = $_POST['pembawa'];
        if ($job == "") {
            $job = '%';
        }
        if ($spv == "") {
            $spv = '%';
        }
        if ($pembawa == "") {
            $pembawa = '%';
        }
        $sql = "SELECT MCI.*, XXX.UNIT_LVL2
                FROM MART_CHECKOUT_INFO MCI
                     INNER JOIN
                     (SELECT DISTINCT INV_ID, UNIT_LVL2 FROM MART_UNIT_CONVERS) XXX
                        ON XXX.INV_ID = MCI.MART_WR_INV_ID
               WHERE     MCI.MART_WR_DATE BETWEEN TO_DATE ('$start', 'MM/DD/YYYY')
                                              AND TO_DATE ('$end', 'MM/DD/YYYY')
                     AND MCI.MART_WR_JOB LIKE '$job'
                     AND MCI.MART_WR_SPV_SIGN LIKE '$spv'
                     AND MCI.MART_WR_CARRIER LIKE '$pembawa'
                ORDER BY MCI.MART_WR_JOB ASC";
//        echo "$sql";
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