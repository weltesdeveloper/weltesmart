<?php

require_once '../../../_config/dbinfo.inc.php';
require_once '../../../_config/FunctionAct.php';
session_start();
// GENERATE THE APPLICATION PAGE
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);

// 1. SET THE CLIENT IDENTIFIER AFTER EVERY CALL
// 2. USING UNIQUE VALUE FOR BACK END USER
oci_set_client_identifier($conn, $_SESSION['userlogin']);
$username = htmlentities($_SESSION['userlogin'], ENT_QUOTES);
switch ($_POST['action']) {
    case "getjob":
        $response = array();
        $sql = "SELECT DISTINCT PROJECT_NO FROM VW_PROJ_INFO@WELTESMART_WELTES_LINK ORDER BY PROJECT_NO ASC";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        while ($row = oci_fetch_array($parse)) {
            array_push($response, $row);
        }


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
    case "get_inventory":
        $response = array();
        $sql = "SELECT * FROM MART_STOCK_INFO WHERE INV_STK_QTY <> 0 ORDER BY INV_DESC ASC";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        while ($row = oci_fetch_array($parse)) {
            array_push($response, $row);
        }
        echo json_encode($response);
        break;

    case "submit_data":
        $wh_id = $_POST['wh_id'];
        $tanggal = $_POST['tanggal'];
        $job = $_POST['job'];
        $subjob = $_POST['subjob'];
        $pembawa = str_replace("'", "''", $_POST['pembawa']) ;
        $spv = $_POST['spv'];
        $manager = $_POST['manager'];
        $inv_id = $_POST['inv_id'];
        $qty = $_POST['qty'];
        $remark = $_POST['remark'];
        $rem = $_POST['rem'];
        $sql = "INSERT INTO MART_MST_CHKOUT (MART_WR_ID, MART_WR_DATE, MART_WR_SYSDATE, MART_WR_SIGN, 
            MART_WR_REMARK, MART_WR_JOB, MART_WR_SUBJOB, MART_WR_CARRIER, MART_WR_SPV_SIGN, MART_WR_FM_SIGN ) 
            VALUES ('$wh_id', TO_DATE('$tanggal', 'MM/DD/YYYY'), SYSDATE, '$username', 
                '$rem', '$job', '$subjob', '$pembawa', '$spv', '$manager')";
        $parse = oci_parse($conn, $sql);
        $exe = oci_execute($parse);
        if ($exe) {
            oci_commit($conn);
            echo "SUKSES";
            for ($i = 0; $i < count($inv_id); $i++) {
                $DtlInsertSql = "INSERT INTO MART_DTL_CHKOUT(MART_WR_ID, MART_WR_INV_ID, MART_WR_INV_QTY, MART_WR_INV_REMARK) "
                        . "VALUES('$wh_id', '$inv_id[$i]', '$qty[$i]', '$remark[$i]')";
                $DtlInsertParse = oci_parse($conn, $DtlInsertSql);
                $DtlInsert = oci_execute($DtlInsertParse);
                if ($DtlInsert) {
                    oci_commit($conn);
                    echo "SUKSES";
                } else {
                    oci_rollback($conn);
                    echo "GAGAL";
                }
            }

            for ($i = 0; $i < count($inv_id); $i++) {
                $inv_id_ = $inv_id[$i];
                $qty_ = $qty[$i] * -1;
                $sign_ = $username;
                $type_ = "OUT";
                $adjustSql = "INSERT INTO MART_STK_ADJ_HIST(INV_ID, HIST_ADJUST, INPUT_SIGN, INPUT_DATE, HIST_TYPE, PROPERTIES) "
                        . "VALUES('$inv_id_', '$qty_', '$sign_', SYSDATE, '$type_', '$wh_id')";
                $adjustParse = oci_parse($conn, $adjustSql);
                $adjust = oci_execute($adjustParse);
                if ($adjust) {
                    oci_commit($conn);
                    echo "SUKSES";
                } else {
                    oci_rollback($conn);
                    echo "GAGAL";
                }
            }

            for ($i = 0; $i < count($inv_id); $i++) {
                $query = "SELECT SUM(HIST_ADJUST) FROM MART_STK_ADJ_HIST WHERE INV_ID = '$inv_id[$i]'";
                $total = SingleQryFld("$query", $conn);
                $updateStockSql = "UPDATE MART_STK_ADJ SET INV_STK_QTY = '$total' WHERE INV_ID = '$inv_id[$i]'";
                $updateStockParse = oci_parse($conn, $updateStockSql);
                $exe = oci_execute($updateStockParse);
                if ($exe) {
                    oci_commit($conn);
                    echo "SUKSES";
                } else {
                    oci_rollback($conn);
                    echo "GAGAL";
                }
            }
        } else {
            oci_rollback($conn);
            echo "GAGAL";
        }
        break;

    case "check_max":
        $inv_id = $_POST['inv_id'];
        $sql = "SELECT INV_STK_QTY FROM MART_STK_ADJ WHERE INV_ID = '$inv_id'";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        $response = array();
        while ($row1 = oci_fetch_array($parse)) {
            array_push($response, $row1);
        }
        echo json_encode($response);
        break;
    default:
        break;
}