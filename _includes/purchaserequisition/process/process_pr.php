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
    case "get_pr_no":
//        $job = $_POST['job'];
//        $subjob = $_POST['subjob'];
        $nomerpr = "SELECT TO_NUMBER(MAX(REPLACE(MART_PR_NO, 'WM-PR-', ''))) FROM MART_MST_PR";
//        echo "$nomerpr";
        $hasil = SingleQryFld($nomerpr, $conn);
        if ($hasil == "") {
            $hasil ++;
            $hasil = str_pad($hasil, 5, 0, STR_PAD_LEFT);
        } else {
            $hasil = str_pad($hasil, 5, 0, STR_PAD_LEFT);
        }
        echo "$hasil";
        break;

    case "submit_pr":
        $job = $_POST['job'];
        $sub_job = $_POST['sub_job'];
        $pr_no = $_POST['pr_no'];
        $date = $_POST['date'];
        $location = $_POST['location'];
        $spv = $_POST['spv'];
        $remark = $_POST['remark'];
        $inv_id = $_POST['inv_id'];
        $inv_qty = $_POST['inv_qty'];
        $inv_unit = $_POST['inv_unit'];
        $inv_remark = $_POST['inv_remark'];
        $action = $_POST['submit_pr'];
        $insertMStSql = "INSERT INTO MART_MST_PR(MART_PR_NO, MART_PR_DATE, MART_PR_SIGN, MART_PR_SYSDATE, MART_PR_STAT, MART_PR_REMARKS, MART_JOB, MART_SUBJOB, MART_SPV) "
                . "VALUES('$pr_no', TO_DATE('$date', 'MM/DD/YYYY'), '$username', SYSDATE, 'ACTIVE', '$remark', '$job', '$sub_job', '$spv')";
        $insertMStParse = oci_parse($conn, $insertMStSql);

        $insertMSt = oci_execute($insertMStParse);
        if ($insertMSt) {
            oci_commit($conn);
            FOR ($i = 0; $i < count($inv_id); $i++) {
                $insertDtlSql = "INSERT INTO MART_DTL_PR(MART_PR_NO, MART_INV_ID, MART_INV_REMARK, MART_INV_UNIT, MART_INV_QTY) "
                        . "VALUES('$pr_no', '$inv_id[$i]', '$inv_remark[$i]', '$inv_unit[$i]', '$inv_qty[$i]')";
                $insertDtlParse = oci_parse($conn, $insertDtlSql);
                $insertDtl = oci_execute($insertDtlParse);
                if ($insertDtl) {
                    oci_commit($conn);
                } else {
                    ocirollback($conn);
                }
            }
        } else {
            oci_rollback($conn);
        }
        break;
    default:
        break;
}