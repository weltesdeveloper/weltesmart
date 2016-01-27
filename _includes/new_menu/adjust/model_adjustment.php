<?php

require_once '../../../_config/dbinfo.inc.php';
require_once '../../../_config/misc.func.php';

switch ($_POST['action']) {
    case "load_data":
        $response = array();
        $invtype = $_POST['inv_type'];
        $sql = "SELECT VSH.INV_ID, VSH.INV_DESC, SUM (VSH.TRANS_QTY) AS QTY
                        FROM VW_STOCK_HIST  VSH INNER JOIN MART_INV_INFO MII ON MII.INV_ID = VSH.INV_ID
                    GROUP BY VSH.INV_ID, VSH.INV_DESC
                    ORDER BY VSH.INV_DESC";
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
        $sql = "SELECT VSH.INV_ID, VSH.INV_DESC, SUM (VSH.TRANS_QTY) AS QTY
    FROM VW_STOCK_HIST  VSH INNER JOIN MART_INV_INFO MII ON MII.INV_ID = VSH.INV_ID
    WHERE VSH.INV_ID = '$inv_id'
GROUP BY VSH.INV_ID, VSH.INV_DESC
ORDER BY VSH.INV_DESC";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        $response = array();
        while ($row1 = oci_fetch_array($parse)) {
            array_push($response, $row1);
        }
        echo json_encode($response);
        break;

    case "stock_adjust":
        $inv_id = $_POST['inv_id'];
        $date = $_POST['date'];
        $qty = $_POST['qty'];
        $unit = $_POST['unit'];
        $remark = $_POST['remark'];
        $stock_sekarang = SingleQryFld("SELECT SUM(TRANS_QTY) AS QTY FROM VW_STOCK_HIST WHERE INV_ID = '$inv_id'", $conn);
        $selisih_stock = $qty - $stock_sekarang;

        $sql = "INSERT INTO MART_STOCK_ADJUST(ADJUST_ID, INV_ID, ADJUST_DATE, ADJUST_SYSDATE, ADJUST_SIGN, ADJUST_REMARK, ADJUST_QTY, ADJUST_UNIT) "
                . "VALUES(SEQ_ADJUST_ID.NEXTVAL, '$inv_id', TO_DATE('$date', 'MM/DD/YYYY'), SYSDATE, '$username', '$remark', '$selisih_stock', '$unit')";
        $parse = oci_parse($conn, $sql);
        $exe = oci_execute($parse);
        if ($exe) {
            oci_commit($conn);
            $response = array(
                "qty" => number_format($qty, 0),
                "status" => "BERHASIL INPUT"
            );
            echo json_encode($response);
        } else {
            oci_rollback($conn);
            $response = array(
//                "qty" => $qty//,
                "status" => "GAGAL INPUT" . oci_error()
            );
            echo json_encode($response);
        }
        break;
    default:
        break;
}
