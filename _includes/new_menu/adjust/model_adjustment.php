<?php

require_once '../../../_config/dbinfo.inc.php';
require_once '../../../_config/misc.func.php';

switch ($_POST['action']) {
    case "load_data":
        $response = array();
        $invtype = $_POST['inv_type'];
        $sql = "SELECT VSI.INV_ID, VII.INV_DESC, SUM (VSI.QTY) QTY, VII.INV_TYPE
    FROM VW_STOCK_INFO VSI
         INNER JOIN VW_INV_INFO@WELTESMART_WENLOGINV_LINK VII
            ON VII.INV_ID = VSI.INV_ID
   WHERE VII.INV_WM_SELECT = 1 AND VII.INV_TYPE LIKE '$invtype'
GROUP BY VSI.INV_ID, VII.INV_DESC, INV_TYPE
ORDER BY VSI.INV_ID";
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

    case "stock_adjust":
        $inv_id = $_POST['inv_id'];
        $date = $_POST['date'];
        $qty = $_POST['qty'];
        $unit = $_POST['unit'];
        $remark = $_POST['remark'];
        $sql = "INSERT INTO STOCK_ADJUST(ADJUST_ID, INV_ID, ADJUST_DATE, ADJUST_SYSDATE, ADJUST_SIGN, ADJUST_REMARK, ADJUST_QTY, ADJUST_UNIT) "
                . "VALUES(SEQ_ADJUST_ID.NEXTVAL, '$inv_id', TO_DATE('$date', 'MM/DD/YYYY'), SYSDATE, '$username', '$remark', '$qty', '$unit')";
        $parse = oci_parse($conn, $sql);
        $exe = oci_execute($parse);
        if ($exe) {
            oci_commit($conn);
            echo json_encode("BERHASIL INPUT");
        } else {
            oci_rollback($conn);
            echo json_encode("GAGAL INPUT".  oci_error());
        }
        break;
    default:
        break;
}
