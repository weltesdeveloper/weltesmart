<?php

require_once '../../../_config/dbinfo.inc.php';
require_once '../../../_config/misc.func.php';
session_start();
// GENERATE THE APPLICATION PAGE
$conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB) or die;

// 1. SET THE CLIENT IDENTIFIER AFTER EVERY CALL
// 2. USING UNIQUE VALUE FOR BACK END USER
oci_set_client_identifier($conn, $_SESSION['userlogin']);
$username = htmlentities($_SESSION['userlogin'], ENT_QUOTES);

switch ($_POST['action']) {
    case "load_data":
        $response = array();
        $invtype = $_POST['inv_type'];
        $sql = "SELECT MI.INV_ID,
         MI.INV_DESC,
         MI.INV_TYPE
    FROM VW_INV_CONS_INFO@WELTESMART_WENLOGINV_LINK MI
   WHERE MI.INV_WM_SELECT = 1 AND INV_TYPE LIKE '$invtype'
ORDER BY INV_DESC ASC";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        while ($row = oci_fetch_array($parse)) {
            array_push($response, $row);
        }
        echo json_encode($response);
        break;
    case "get_inv_stock":
        $response = array();
        $inv_id = $_POST['inv_id'];
        $jumlahinv = SingleQryFld("SELECT COUNT(*) FROM STOCK_ADJUST WHERE INV_ID = '$inv_id'", $conn);
        if ($jumlahinv == 0) {
            $sql = "SELECT INV_ID,
       INV_DESC,
       INV_CAT,
       INV_TYPE
  FROM MASTER_INV@WELTESMART_WENLOGINV_LINK
 WHERE INV_WM_SELECT = '1' AND INV_ID = '$inv_id'";
            $parse = oci_parse($conn, $sql);
            oci_execute($parse);
            while ($row = oci_fetch_array($parse)) {
                array_push($response, $row);
            }
        } else {
            $sql = "SELECT * FROM STOCK_ADJUST WHERE INV_ID = '$inv_id'";
            $parse = oci_parse($conn, $sql);
            oci_execute($parse);
            while ($row = oci_fetch_array($parse)) {
                array_push($response, $row);
            }
        }
        echo json_encode($response);
        break;
//    case "update_data" :
//        switch ($_POST['type']) {
//            case "adjust_stock":
//                $inv_id = $_POST['inv_id'];
//                $value = $_POST['value'];
//                $last_value = SingleQryFld("SELECT NVL(INV_STK_QTY,0) FROM MART_STK_ADJ WHERE INV_ID = '$inv_id'", $conn);
//                $selisih = $value - $last_value;
//
//                $insertHistSql = "INSERT INTO MART_STK_ADJ_HIST(INV_ID, HIST_ADJUST, INPUT_SIGN, INPUT_DATE, HIST_TYPE, PROPERTIES) "
//                        . "VALUES('$inv_id', '$selisih', '$username', SYSDATE, 'ADJUST', 'MANUAL')";
//                $insertHistParse = oci_parse($conn, $insertHistSql);
//                $insertHist = oci_execute($insertHistParse);
//                if ($insertHist) {
//                    oci_commit($conn);
//                } ELSE {
//                    oci_rollback($conn);
//                }
//
//                $sql = "BEGIN PROCD_MART_STK_ADJ_INS('$inv_id', '$value', '0', 'ADJUST'); END;";
//                $parse = oci_parse($conn, $sql);
//                $exe = oci_execute($parse);
//                if ($exe) {
//                    oci_commit($conn);
//                    echo "SUKSES";
//                } else {
//                    echo "ERROR";
//                }
//                break;
//            case "adjust_min":
//                $inv_id = $_POST['inv_id'];
//                $value = $_POST['value'];
//
//                $insertHistSql = "INSERT INTO MART_MIN_STOCK_HIST(INV_ID, HIST_MIN, INPUT_SIGN, INPUT_DATE) "
//                        . "VALUES('$inv_id', '$value', '$username', SYSDATE)";
//                $insertHistParse = oci_parse($conn, $insertHistSql);
//                $insertHist = oci_execute($insertHistParse);
//                if ($insertHist) {
//                    oci_commit($conn);
//                } ELSE {
//                    oci_rollback($conn);
//                }
//
//                $sql = "BEGIN PROCD_MART_STK_ADJ_INS('$inv_id', '', '$value', 'ADJUST MIN'); END;";
//                $parse = oci_parse($conn, $sql);
//                $exe = oci_execute($parse);
//                if ($exe) {
//                    oci_commit($conn);
//                    echo "SUKSES";
//                } else {
//                    echo "ERROR";
//                }
//                break;
//            case "adjust_unit":
//                $inv_id = $_POST['inv_id'];
//                $value = $_POST['value'];
//                $tableName = "MART_STK_ADJ";
//                $setValue = array(
//                    "INV_STK_UNIT" => $value
//                );
//                $setWhere = array(
//                    "INV_ID" => $inv_id
//                );
//                $update = $updatedb->UpdateTerstruktur($tableName, $setValue, $setWhere);
//                echo "$update";
//                $parse = oci_parse($conn, $update);
//                $exe = oci_execute($parse);
//                if ($exe) {
//                    oci_commit($conn);
//                } else {
//                    oci_rollback($conn);
//                }
//                break;
//            default:
//                break;
//        }
//        break;
//
//    case "show_history" :
//        $arrayResp = array();
//        $arrayDesc = array();
//        $inv_id = $_POST['inv_id'];
//        $sql = "SELECT to_char(MSA.INPUT_DATE, 'DD-MM-YYYY HH24:MI:SS') INPUT_DATE, "
//                . "MSA.INV_ID, "
//                . "MSA.HIST_ADJUST, "
//                . "MSA.INPUT_SIGN, "
//                . "MI.INV_DESC,"
//                . "MSA.HIST_TYPE,"
//                . "MSA.PROPERTIES "
//                . "FROM MART_STK_ADJ_HIST MSA "
//                . "INNER JOIN MASTER_INV@WELTESMART_WENLOGINV_LINK MI "
//                . "ON MI.INV_ID = MSA.INV_ID "
//                . "WHERE MSA.INV_ID = '$inv_id' "
//                . "ORDER BY TO_DATE(to_char(MSA.INPUT_DATE, 'DD-MM-YYYY HH24:MI:SS'), 'DD-MM-YYYY HH24:MI:SS') DESC";
////        echo $sql;
//        $parse = oci_parse($conn, $sql);
//        oci_execute($parse);
//        while ($row = oci_fetch_array($parse)) {
//            array_push($arrayResp, $row);
//        }
//        $inv_desc = SingleQryFld("SELECT INV_DESC FROM MASTER_INV@WELTESMART_WENLOGINV_LINK WHERE INV_ID = '$inv_id'", $conn);
//        $array = array(
//            "value1" => $arrayResp,
//            "value2" => $inv_desc
//        );
//        echo json_encode($array);
//        break;
//
//    case "show_qr":
//        $array = array();
//        $inv_id = $_POST['inv_id'];
//        $sql = "SELECT INV_ID, INV_DESC FROM MASTER_INV@WELTESMART_WENLOGINV_LINK WHERE INV_ID = '$inv_id'";
//        $parse = oci_parse($conn, $sql);
//        oci_execute($parse);
//        while ($row1 = oci_fetch_array($parse)) {
//            array_push($array, $row1);
//        }
//        echo json_encode($array);
//        break;
//
//    case "select_inv":
//        $invtype = $_POST['invType'];
//
//        $query2 = "SELECT MSI.* FROM MART_STOCK_INFO MSI WHERE MSI.INV_TYPE LIKE '$invtype' ORDER BY MSI.INV_DESC ASC";
//
//        $sql = oci_parse($conn, $query2);
//        break;
    default:
        break;
}
