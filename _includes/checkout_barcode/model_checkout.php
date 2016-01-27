<?php
require_once('../../_config/dbinfo.inc.php');
require_once('../../_config/misc.func.php');

switch ($_POST['action']) {
    case 'show_job':
        $sql = "SELECT DISTINCT PROJECT_NO FROM VW_PROJ_INFO ORDER BY PROJECT_NO ";
        $parse = oci_parse($conn_weltes, $sql);
        oci_execute($parse);

        $arr = array();
        while ($row = oci_fetch_assoc($parse)) {
            array_push($arr, $row);
        }

        echo json_encode($arr);
        break;

    case 'autocomplete':

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
            "spv" => $spv,
            "pembawa" => $pembawa
        );
        echo json_encode($response);
        break;

    case 'show_inv_dtl':
        $inv_id = $_POST['inv_id'];
        $already_inv_id = $_POST['already_inv_id'];

        $str_alrdy = "";
        for ($i = 0; $i < count($already_inv_id); $i++) {
            $str_alrdy .= "'$already_inv_id[$i]',";
        }
        $str_alrdy = substr($str_alrdy, 0, (strlen($str_alrdy) - 1));

        $sql = "SELECT * FROM MART_STOCK_INFO WHERE INV_ID = '$inv_id' AND INV_ID NOT IN ($str_alrdy)";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);

        $arr = array();
        $row = oci_fetch_assoc($parse);
        array_push($arr, $row);

        echo json_encode($arr);

        break;

    case 'submit_data':

        function gagal__($param, $conn) {

            $sql = "DELETE FROM MART_MST_CHKOUT WHERE MART_WR_ID = '$param'";
            $parse = oci_parse($conn, $sql);
            $exe = oci_execute($parse);
            if ($exe) {
                oci_commit($conn);
            }
        }

        $id = SingleQryFld("SELECT MART_SEQ_MST_CHKOUT.NEXTVAL FROM DUAL", $conn);
        $tanggal = $_POST['tanggal'];
        $job = $_POST['job'];
        $subjob = $_POST['subjob'];
        $pembawa = str_replace("'", "''", $_POST['pembawa']);
        $spv = $_POST['spv'];
        $manager = $_POST['manager'];
        $inv_id = $_POST['inv_id'];
        $qty = $_POST['qty'];
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
                $DtlInsertSql = "INSERT INTO MART_DTL_CHKOUT(MART_WR_ID, MART_WR_INV_ID, MART_WR_INV_QTY) "
                        . "VALUES('$id', '$inv_id[$i]', '$qty[$i]')";
                $DtlInsertParse = oci_parse($conn, $DtlInsertSql);
                $DtlInsert = oci_execute($DtlInsertParse);
                if ($DtlInsert) {
                    oci_commit($conn);
                    echo "SUKSES";
                } else {
                    oci_rollback($conn);
                    gagal__($id, $conn);
                    echo "GAGAL";
                }
            }

            for ($i = 0; $i < count($inv_id); $i++) {
                $inv_id_ = $inv_id[$i];
                $qty_ = $qty[$i] * -1;
                $sign_ = $username;
                $type_ = "OUT";
                $adjustSql = "INSERT INTO MART_STK_ADJ_HIST(INV_ID, HIST_ADJUST, INPUT_SIGN, INPUT_DATE, HIST_TYPE, PROPERTIES) "
                        . "VALUES('$inv_id_', '$qty_', '$sign_', SYSDATE, '$type_', '$id')";
                $adjustParse = oci_parse($conn, $adjustSql);
                $adjust = oci_execute($adjustParse);
                if ($adjust) {
                    oci_commit($conn);
                    echo "SUKSES";
                } else {
                    oci_rollback($conn);
                    gagal__($id, $conn);
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
                    gagal__($id, $conn);
                    echo "GAGAL";
                }
            }
        } else {
            oci_rollback($conn);
            echo "GAGAL";
        }
        break;

    case 'show_modal_inv_dtl':
        $inv_id = $_POST['inv_id'];
        $stk_qty = $_POST['stk_qty'];
        $inv_desc = SingleQryFld("SELECT INV_DESC FROM MART_STOCK_INFO WHERE INV_ID = '$inv_id' ", $conn);
        $counter = $_POST['counter'];
        ?>
        <div class="modal-header">
            <h4 class="modal-title text-center">ISI JUMLAH YANG DI AMBIL</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-12">
                    <label class="text-aqua fa-2x"><?= $inv_desc ?></label>
                </div>
            </div>
            <div class="row">
                <div class='col-sm-12'>
                    <div class="col-sm-4">
                        <label>SISA STOK</label>
                    </div>
                    <div class="col-sm-8">
                        <label><?= round($stk_qty) ?></label>
                    </div>
                </div>
                <div class='col-sm-12'>
                    <div class="col-sm-4">
                        <label>QTY DIAMBIL</label>
                    </div>
                    <div class="col-sm-8">
                        <input class="form-control" type="text" value="1" id="modal_txt_qty" />
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-success" onclick="updateQty(<?= $counter ?>)" id="btn_submit_qty">Submit</button>
            <button type="button" class="btn btn-default" data-dismiss="modal" onclick="DeleteItem(<?= $counter ?>)">Cancel</button>
        </div>        
        <?php
        break;
}

