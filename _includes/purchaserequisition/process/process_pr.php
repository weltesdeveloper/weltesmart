<?php

require_once '../../../_config/dbinfo.inc.php';
require_once '../../../_config/FunctionAct.php';
session_start();
// GENERATE THE APPLICATION PAGE
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);

// 1. SET THE CLIENT IDENTIFIER AFTER EVERY CALL
// 2. USING UNIQUE VALUE FOR BACK END USER
oci_set_client_identifier($conn, $_SESSION['userlogin']);
$username = $_SESSION['userlogin'];
switch ($_POST['action']) {
    case "get_pr_no":
        $nomerpr = "SELECT TO_NUMBER(MAX(REPLACE(MART_PR_NO, 'WM-PR-', ''))) FROM MART_MST_PR";
        $hasil = SingleQryFld($nomerpr, $conn);
        if ($hasil == "") {
            $hasil ++;
            $hasil = str_pad($hasil, 5, 0, STR_PAD_LEFT);
        } else {
            $hasil ++;
            $hasil = str_pad($hasil, 5, 0, STR_PAD_LEFT);
        }
        echo "$hasil";
        break;

    case "submit_pr":
        $pr_no = $_POST['pr_no'];
        $date = $_POST['date'];
        $location = $_POST['location'];
        $remark = $_POST['remark'];
        $inv_id = $_POST['inv_id'];
        $inv_qty = $_POST['inv_qty'];
        $inv_unit = $_POST['inv_unit'];
        $inv_brand = $_POST['inv_brand'];
        $inv_remark = $_POST['inv_remark'];
        $insertMStSql = "INSERT INTO MART_MST_PR(MART_PR_NO, MART_PR_DATE, MART_PR_SIGN, MART_PR_SYSDATE, MART_PR_STAT, MART_PR_DELIV, MART_PR_REMARKS, MART_PR_REV, MART_PR_DEL_REMARKS) "
                . "VALUES('$pr_no', TO_DATE('$date', 'MM/DD/YYYY'), '$username', SYSDATE, 'ACTIVE', '$location', '$remark', '0', '')";
        $insertMStParse = oci_parse($conn, $insertMStSql);

        $insertMSt = oci_execute($insertMStParse);
        if ($insertMSt) {
            oci_commit($conn);
            for ($i = 0; $i < count($inv_id); $i++) {
                $insertDtlSql = "INSERT INTO MART_DTL_PR(MART_PR_NO, MART_INV_ID, MART_INV_REMARK, MART_INV_UNIT, MART_INV_QTY, MART_PR_REV) "
                        . "VALUES('$pr_no', '$inv_id[$i]', '$inv_remark[$i]', '$inv_unit[$i]', '$inv_qty[$i]', '0')";
                $insertDtlParse = oci_parse($conn, $insertDtlSql);
                $insertDtl = oci_execute($insertDtlParse);
                if ($insertDtl) {
                    oci_commit($conn);
                    echo 'SUKSES';
                } else {
                    ocirollback($conn);
                    echo "GAGAL";
                }
                if ($inv_brand[$i] != NULL) {
                    for ($j = 0; $j < count($inv_brand[$i]); $j++) {
                        $idBrand = $inv_brand[$i][$j];
                        $insertBrandSql = "INSERT INTO MART_BRAND_PR(MART_PR_NO, MART_INV_ID, MART_ID_BRAND, MART_PR_REV) "
                                . "VALUES('$pr_no', '$inv_id[$i]', '$idBrand', '0')";
                        $insertBrandParse = oci_parse($conn, $insertBrandSql);
                        $insertBrand = oci_execute($insertBrandParse);
                        if ($insertBrand) {
                            oci_commit($conn);
                            echo 'SUKSES';
                        } else {
                            oci_rollback($conn);
                            echo "GAGAL";
                        }
                    }
                } else {
                    echo "dkasnjdksajd";
                    $insertBrandSql = "INSERT INTO MART_BRAND_PR(MART_PR_NO, MART_INV_ID, MART_ID_BRAND) "
                            . "VALUES('$pr_no', '$inv_id[$i]', '0')";
                    $insertBrandParse = oci_parse($conn, $insertBrandSql);
                    $insertBrand = oci_execute($insertBrandParse);
                    if ($insertBrand) {
                        oci_commit($conn);
                        echo 'SUKSES';
                    } else {
                        oci_rollback($conn);
                        echo "GAGAL";
                    }
                }
            }
        } else {
            oci_rollback($conn);
            echo "GAGAL";
        }
        break;
    default:
        break;
}