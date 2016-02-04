<?php

require_once '../../../_config/dbinfo.inc.php';
require_once '../../../_config/misc.func.php';

switch ($_POST['action']) {
    case "loading_first":
        $inv1 = array();
        $sql = "SELECT DISTINCT INV_ID, INV_DESC FROM MART_INV_INFO ORDER BY INV_DESC ASC";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        while ($row = oci_fetch_array($parse)) {
            array_push($inv1, $row);
        }
        
        $inv2 = array();
        $spvSql = "SELECT MUC.*, MII.INV_DESC FROM MART_UNIT_CONVERS MUC INNER JOIN MART_INV_INFO MII ON MII.INV_ID = MUC.INV_ID ORDER BY MII.INV_DESC ASC";
        $spvParse = oci_parse($conn, $spvSql);
        oci_execute($spvParse);
        while ($row2 = oci_fetch_array($spvParse)) {
            array_push($inv2, $row2);
        }
        $response = array(
            "inv1" => $inv1,
            "inv2" => $inv2
        );
        echo json_encode($response);
        break;
        
    case "show_inventory":
        $inv_id = $_POST['inv_id'];
        if ($inv_id == "") {
            $inv_id = '%';
        }
        $sql = "SELECT MUC.*, MII.INV_DESC "
                . "FROM MART_UNIT_CONVERS MUC "
                . "INNER JOIN MART_INV_INFO MII "
                . "ON MII.INV_ID = MUC.INV_ID "
                . "WHERE MII.INV_ID LIKE '$inv_id' "
                . "ORDER BY MII.INV_DESC ASC";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        $response = array();
        while ($row = oci_fetch_array($parse)) {
            array_push($response, $row);
        }
        echo json_encode($response);
        break;
    default:
        break;
}