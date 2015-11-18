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
    case "load_data":
        $invtype = $_POST['invType'];
        
//        $QUERY = "SELECT MI.INV_ID, "
//                . "MI.INV_DESC, "
//                . "MI.INV_UNIT, "
//                . "NVL (MSA.INV_STK_QTY, 0) INV_STK_QTY, "
//                . "NVL (MSA.INV_STK_MIN, 0) INV_STK_MIN "
//                . "FROM MART_STK_ADJ MSA "
//                . "RIGHT OUTER JOIN "
//                . "MASTER_INV@WELTESMART_WENLOGINV_LINK MI "
//                . "ON MI.INV_ID = MSA.INV_ID "
//                . "WHERE MI.INV_CAT != 'JASA' AND MI.INV_TYPE LIKE '$invtype' AND INV_WM_SELECT = 1 "
//                . "ORDER BY INV_DESC ASC";
        
        $query2 = "SELECT MSI.* FROM MART_STOCK_INFO MSI WHERE MSI.INV_TYPE LIKE '$invtype' ORDER BY MSI.INV_DESC ASC";
        
        $sql = oci_parse($conn, $query2);
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
            $listInv = json_encode($res, JSON_PRETTY_PRINT);

            print_r($listInv);

            oci_free_statement($sql); // FREE THE STATEMENT
            oci_close($conn); // CLOSE CONNECTION, NEED TO REOPEN
        }
        break;
    case "update_data" :
        switch ($_POST['type']) {
            case "adjust_stock":
                $inv_id = $_POST['inv_id'];
                $value = $_POST['value'];
                $last_value = SingleQryFld("SELECT NVL(INV_STK_QTY,0) FROM MART_STK_ADJ WHERE INV_ID = '$inv_id'", $conn);
                $selisih = $value - $last_value;

                $insertHistSql = "INSERT INTO MART_STK_ADJ_HIST(INV_ID, HIST_ADJUST, INPUT_SIGN, INPUT_DATE, HIST_TYPE) "
                        . "VALUES('$inv_id', '$selisih', '$username', SYSDATE, 'ADJUST')";
                $insertHistParse = oci_parse($conn, $insertHistSql);
                $insertHist = oci_execute($insertHistParse);
                if ($insertHist) {
                    oci_commit($conn);
                } ELSE {
                    oci_rollback($conn);
                }

                $sql = "BEGIN PROCD_MART_STK_ADJ_INS('$inv_id', '$value', '0', 'ADJUST'); END;";
                $parse = oci_parse($conn, $sql);
                $exe = oci_execute($parse);
                if ($exe) {
                    oci_commit($conn);
                    echo "SUKSES";
                } else {
                    echo "ERROR";
                }
                break;
            case "adjust_min":
                $inv_id = $_POST['inv_id'];
                $value = $_POST['value'];

                $insertHistSql = "INSERT INTO MART_MIN_STOCK_HIST(INV_ID, HIST_MIN, INPUT_SIGN, INPUT_DATE) "
                        . "VALUES('$inv_id', '$value', '$username', SYSDATE)";
                $insertHistParse = oci_parse($conn, $insertHistSql);
                $insertHist = oci_execute($insertHistParse);
                if ($insertHist) {
                    oci_commit($conn);
                } ELSE {
                    oci_rollback($conn);
                }

                $sql = "BEGIN PROCD_MART_STK_ADJ_INS('$inv_id', '', '$value', 'ADJUST MIN'); END;";
                $parse = oci_parse($conn, $sql);
                $exe = oci_execute($parse);
                if ($exe) {
                    oci_commit($conn);
                    echo "SUKSES";
                } else {
                    echo "ERROR";
                }
                break;
            default:
                break;
        }
        break;

    case "show_history" :
        $arrayResp = array();
        $arrayDesc = array();
        $inv_id = $_POST['inv_id'];
        $sql = "SELECT to_char(MSA.INPUT_DATE, 'DD-MM-YYYY HH24:MI:SS') INPUT_DATE, MSA.INV_ID, MSA.HIST_ADJUST, MSA.INPUT_SIGN, MI.INV_DESC "
                . "FROM MART_STK_ADJ_HIST MSA "
                . "INNER JOIN MASTER_INV@WELTESMART_WENLOGINV_LINK MI "
                . "ON MI.INV_ID = MSA.INV_ID "
                . "WHERE MSA.INV_ID = '$inv_id' "
                . "ORDER BY TO_DATE(to_char(MSA.INPUT_DATE, 'DD-MM-YYYY HH24:MI:SS'), 'DD-MM-YYYY HH24:MI:SS') DESC";
//        echo $sql;
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        while ($row = oci_fetch_array($parse)) {
            array_push($arrayResp, $row);
        }
        $inv_desc = SingleQryFld("SELECT INV_DESC FROM MASTER_INV@WELTESMART_WENLOGINV_LINK WHERE INV_ID = '$inv_id'", $conn);
        $array = array(
            "value1" => $arrayResp,
            "value2" => $inv_desc
        );
        echo json_encode($array);
        break;

    case "show_qr":
        $array = array();
        $inv_id = $_POST['inv_id'];
        $sql  ="SELECT INV_ID, INV_DESC FROM MASTER_INV@WELTESMART_WENLOGINV_LINK WHERE INV_ID = '$inv_id'";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        while ($row1 = oci_fetch_array($parse)) {
            array_push($array, $row1);
        }
        echo json_encode($array);
        break;
    default:
        break;
}
