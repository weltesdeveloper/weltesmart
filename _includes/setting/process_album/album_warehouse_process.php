<?php

require_once '../../../_config/dbinfo.inc.php';
session_start();
// GENERATE THE APPLICATION PAGE
$conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB) or die;

// 1. SET THE CLIENT IDENTIFIER AFTER EVERY CALL
// 2. USING UNIQUE VALUE FOR BACK END USER
switch ($_POST['param']) {
    case "first_load":
        $response = array();
        $sql = "SELECT INV_ID, "
                . "INV_DESC, "
                . "INV_CAT, "
                . "INV_TYPE "
                . "FROM VW_INV_INFO@WELTESMART_WENLOGINV_LINK "
                . "WHERE INV_WM_SELECT = '1'";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        while ($row = oci_fetch_array($parse)) {
            array_push($response, $row);
        }
        echo json_encode($response);
        break;
    case "update_data":
        $inv_id = $_POST['inv_id'];
        if (isset($_FILES["file"]["name"])) {
            $nama_file = $_FILES["file"]["name"];
            $lob_upload = $_FILES["file"]["tmp_name"];

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
    default:
        break;
}