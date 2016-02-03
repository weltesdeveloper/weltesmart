<?php

require_once '../../../_config/dbinfo.inc.php';
switch ($_POST['action']) {
    case "SHOW":
        $sql = "SELECT MI.INV_ID, "
                . "MI.INV_DESC, "
                . "MI.INV_CAT, "
                . "MI.INV_TYPE, "
                . "MI.INV_BRAND, "
                . "MI.INV_UNIT, "
                . "MI.INV_GRD, "
                . "MI.INV_WM_SELECT "
                . "FROM MASTER_INV MI "
                . "WHERE MI.INV_CAT <> 'SERVICES' AND MI.INV_CAT <> 'ACCOMMODATION' "
                . "ORDER BY MI.INV_DESC ASC";
        $parse = oci_parse($conn_wenloginv, $sql);
        oci_execute($parse);
        $response = array();
        while ($row = oci_fetch_array($parse)) {
            array_push($response, $row);
        }
        echo json_encode($response);
        break;
    case 'UPDATE' :
//        $check = $_POST['check_id'];
        $inv_id = $_POST['inv_id'];
        $sql = oci_parse($conn, "UPDATE MASTER_INV@WELTESMART_WENLOGINV_LINK MI SET MI.INV_WM_SELECT = '1' WHERE MI.INV_ID = '$inv_id'");
        $errExc = oci_execute($sql);

        if (!$errExc) {
            $e = oci_error($sql);
            print htmlentities($e['message']);
            print "\n<pre>\n";
            print htmlentities($e['sqltext']);
            printf("\n%" . ($e['offset'] + 1) . "s", "^");
            print "\n</pre>\n";
        } else {
            print 'SUCCESSFULLY UPDATED';
            oci_commit($conn);
            oci_free_statement($sql); // FREE THE STATEMENT
            oci_close($conn); // CLOSE CONNECTION, NEED TO REOPEN
        }
        break;

    case 'REMOVE' :
//        $check = $_POST['check_id'];
        $inv_id = $_POST['inv_id'];
        $sql = oci_parse($conn, "UPDATE MASTER_INV@WELTESMART_WENLOGINV_LINK MI SET MI.INV_WM_SELECT = '0' WHERE MI.INV_ID = '$inv_id'");
        $errExc = oci_execute($sql);

        if (!$errExc) {
            $e = oci_error($sql);
            print htmlentities($e['message']);
            print "\n<pre>\n";
            print htmlentities($e['sqltext']);
            printf("\n%" . ($e['offset'] + 1) . "s", "^");
            print "\n</pre>\n";
        } else {
            print 'SUCCESSFULLY UPDATED';
            oci_commit($conn);
            oci_free_statement($sql); // FREE THE STATEMENT
            oci_close($conn); // CLOSE CONNECTION, NEED TO REOPEN
        }
        break;

    case "KONVERSI":
        $unit_lvl1 = $_POST['unit_lvl1'];
        $qty_lvl1 = $_POST['qty_lvl1'];
        $unit_lvl2 = $_POST['unit_lvl2'];
        $inv_id = $_POST['inv_id'];

        //UPDATE INV MENJADI 1
        $inv_id = $_POST['inv_id'];
        $sql = oci_parse($conn, "UPDATE MASTER_INV@WELTESMART_WENLOGINV_LINK MI SET MI.INV_WM_SELECT = '1' WHERE MI.INV_ID = '$inv_id'");
        $exe = oci_execute($sql);
        if ($exe) {
            echo "SUCCESS UPDATE";
            //INSERT KONVERSI
            $deleteSql = "DELETE FROM MART_UNIT_CONVERS WHERE INV_ID = '$inv_id'";
            $deleteParse = oci_parse($conn, $deleteSql);
            $delete = oci_execute($deleteParse);
            if ($delete) {
                echo json_encode("BERHASIL DELETE");
                for ($i = 0; $i < count($unit_lvl1); $i++) {
                    $insertSql = "INSERT INTO MART_UNIT_CONVERS(INV_ID, UNIT_LVL1, UNIT_AMOUNT, UNIT_LVL2, INPUT_SIGN, INPUT_DATE) "
                            . "VALUES('$inv_id', '$unit_lvl1[$i]', '$qty_lvl1[$i]', '$unit_lvl2', '$username', SYSDATE)";
                    $insertParse = oci_parse($conn, $insertSql);
                    $insert = oci_execute($insertParse);
                    if ($insert) {
                        echo "SUKSES INSERT";
                    } else {
                        echo "GAGAL INSERT";
                    }
                }
                $insertSql = "INSERT INTO MART_UNIT_CONVERS(INV_ID, UNIT_LVL1, UNIT_AMOUNT, UNIT_LVL2, INPUT_SIGN, INPUT_DATE) "
                        . "VALUES('$inv_id', '$unit_lvl2', '1', '$unit_lvl2', '$username', SYSDATE)";
                $insertParse = oci_parse($conn, $insertSql);
                $insert = oci_execute($insertParse);
                if ($insert) {
                    echo "SUKSES INSERT";
                } else {
                    echo "GAGAL INSERT";
                }
            } else {
                oci_rollback($conn);
                echo "GAGAL INSERT";
            }
        } else {
            oci_rollback($conn);
            echo "GAGAL UPDATE";
        }


        break;
}