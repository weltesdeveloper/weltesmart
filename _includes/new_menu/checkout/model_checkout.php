<?php

require_once '../../../_config/dbinfo.inc.php';
require_once '../../../_config/misc.func.php';

switch ($_POST['action']) {
    case "getjob":
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
    case "get_inventory":
        $response = array();
        $sql = "SELECT INV_ID, "
                . "SUM (TRANS_QTY) TRANS_QTY, "
                . "INV_DESC "
                . "FROM VW_STOCK_HIST "
                . "GROUP BY INV_ID, "
                . "INV_DESC "
                . "HAVING SUM (TRANS_QTY) > 0 "
                . "ORDER BY INV_DESC ASC";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        while ($row = oci_fetch_array($parse)) {
            array_push($response, $row);
        }
        echo json_encode($response);
        break;

    case "submit_data":
        $id = SingleQryFld("SELECT MART_SEQ_MST_CHKOUT.NEXTVAL FROM DUAL", $conn);
        $wh_id = $_POST['wh_id'];
        $tanggal = $_POST['tanggal'];
        $job = $_POST['job'];
        $subjob = $_POST['subjob'];
        $pembawa = str_replace("'", "''", $_POST['pembawa']);
        $spv = $_POST['spv'];
        $manager = $_POST['manager'];
        $inv_id = $_POST['inv_id'];
        $qty = $_POST['qty'];
        $unit = $_POST['unit'];
        $remark = $_POST['remark'];
        $rem = $_POST['rem'];
        $sql = "INSERT INTO MART_MST_CHKOUT (MART_WR_ID, MART_WR_DATE, MART_WR_SYSDATE, MART_WR_SIGN, 
            MART_WR_REMARK, MART_WR_JOB, MART_WR_SUBJOB, MART_WR_CARRIER, MART_WR_SPV_SIGN, MART_WR_FM_SIGN ) 
            VALUES ('$id', TO_DATE('$tanggal', 'MM/DD/YYYY'), SYSDATE, '$username', 
                '$rem', '$job', '$subjob', '$pembawa', '$spv', '$manager')";
        $parse = oci_parse($conn, $sql);
        $exe = oci_execute($parse);
        if ($exe) {
            oci_commit($conn);
            echo "SUKSES";
            for ($i = 0; $i < count($inv_id); $i++) {
                $DtlInsertSql = "INSERT INTO MART_DTL_CHKOUT(MART_WR_ID, MART_WR_INV_ID, MART_WR_INV_QTY, MART_WR_INV_REMARK, MART_WR_INV_UNIT) "
                        . "VALUES('$id', '$inv_id[$i]', '$qty[$i]', '$remark[$i]', '$unit[$i]')";
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
        
        } else {
            oci_rollback($conn);
            echo "GAGAL";
        }
        break;

    case "check_max":
        $inv_id = $_POST['inv_id'];
        $sql = "WITH AA
                AS (SELECT DISTINCT INV_ID, UNIT_LVL2
                      FROM MART_UNIT_CONVERS
                     WHERE INV_ID = '$inv_id'),
                BB
                AS (SELECT SUM (TRANS_QTY) TRANS_QTY
                      FROM VW_STOCK_HIST
                     WHERE INV_ID = '$inv_id')
          SELECT AA.INV_ID, AA.UNIT_LVL2, BB.TRANS_QTY FROM AA, BB";
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