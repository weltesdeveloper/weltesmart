<?php
require_once '../../../_config/dbinfo.inc.php';
session_start();
// GENERATE THE APPLICATION PAGE
$conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB) or die;

// 1. SET THE CLIENT IDENTIFIER AFTER EVERY CALL
// 2. USING UNIQUE VALUE FOR BACK END USER
switch ($_POST['param']) {
    case "show_data":
        $inv_type = $_POST['inv_type'];
        $response = array();
        $sql = "SELECT "
                . "INV_ID, "
                . "INV_DESC, "
                . "INV_TYPE, "
                . "NVL(INV_WH_LOC, '-')INV_WH_LOC,"
                . "(SELECT COUNT(MG.INV_ID) FROM MASTER_INV_IMG MG WHERE MG.INV_ID = MI.INV_ID) JML_GMBAR "
                . "FROM VW_INV_INFO MI "
                . "WHERE INV_WM_SELECT = '1' AND INV_TYPE like '$inv_type' "
                . "ORDER BY INV_DESC ASC";
//        echo $sql;
        $parse = oci_parse($conn_wenloginv, $sql);
        oci_execute($parse);
        while ($row = oci_fetch_array($parse)) {
            array_push($response, $row);
        }
        echo json_encode($response);
        break;
    case "show_modal_print_album":
        ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title text-center">List Image for Inventory <span id="inventory-id"></span></h4>
        </div>
        <div class="modal-body">
            <div class="col-sm-6">
                <table class="table table-striped table-bordered" id="table-image">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-sm-6">
                <table class="table table-striped table-bordered">
                    <tr>
                        <td>
                            <input id="input-repl-2" type="file" class="file-loading" accept="image/*" multiple data-show-upload="true">
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        <?php
        break;

    case "update_data":
        $inv_id = $_POST['inv_id'];
        if (isset($_FILES["file"]["name"])) {
            $nama_file = $_FILES["file"]["name"];
            $lob_upload = $_FILES["file"]["tmp_name"];

            // Delete First Image
            $insertImageSql = "DELETE FROM MASTER_INV_IMG WHERE INV_ID='$inv_id'";
            $insertImageParse = oci_parse($conn_wenloginv, $insertImageSql);
            oci_execute($insertImageParse);

//        INSERT IMAGE
            $lob = oci_new_descriptor($conn_wenloginv, OCI_D_LOB);
            $insertImageSql = "INSERT INTO MASTER_INV_IMG (INV_ID, INV_IMG) "
                    . "VALUES('$inv_id', EMPTY_BLOB()) returning INV_IMG into :IMG";
            $insertImageParse = oci_parse($conn_wenloginv, $insertImageSql);
            oci_bind_by_name($insertImageParse, ':IMG', $lob, -1, OCI_B_BLOB);
            oci_execute($insertImageParse, OCI_DEFAULT);
            $lob->savefile($lob_upload);
            oci_commit($conn_wenloginv);

            $lob->free();
            oci_free_statement($insertImageParse);
            oci_close($conn_wenloginv);
        }
        if (isset($_POST['location'])) {
//        UPDATE LOCATION
            $location = $_POST['location'];
            $updateLocSql = "UPDATE MASTER_INV SET INV_WH_LOC = '$location' WHERE INV_ID = '$inv_id'";
            $updateLocParse = oci_parse($conn_wenloginv, $updateLocSql);
            $updateLoc = oci_execute($updateLocParse);
            if (!$updateLoc) {
                echo "Error Update Location";
            }
        }

        break;

    case "test123":
        $nama_file = $_FILES["file_data"]["name"];
        $lob_upload = $_FILES["file_data"]["tmp_name"];
        $inv_id = $_POST['inv_id'];
        echo json_encode($inv_id);
        break;
    default:
        break;
}