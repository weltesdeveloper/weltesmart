<?php

require_once '../../../../_config/dbinfo.inc.php';

switch ($_POST['action']) {
    case "get_job":
        $start = $_POST['start'];
        $end = $_POST['end'];
        $sql = "SELECT DISTINCT MART_WR_JOB "
                . "FROM MART_CHECKOUT_INFO "
                . "WHERE MART_WR_DATE BETWEEN TO_DATE('$start', 'MM/DD/YYYY') "
                . "AND TO_DATE('$end', 'MM/DD/YYYY') "
                . "ORDER BY MART_WR_JOB ASC";
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