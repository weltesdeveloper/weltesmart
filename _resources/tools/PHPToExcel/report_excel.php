<?php
require_once('../../../_config/dbinfo.inc.php');
require_once('../../../_config/misc.func.php');
$conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB) or die;
$start = $_GET['start'];
$end = $_GET['end'];
$job = $_GET['job'];
header("Content-type: application/octet-stream");
$formattedFileName = date("m/d/Y_h:i", time());
// simpan file excel dengan nama umr2013.xls
//saat file berhasil di buat, otomatis pop up download akan muncul
$tanggalMulai = date("d F Y", strtotime($start));
$tanggalSelesai = date("d F Y", strtotime($end));
header('Content-Disposition: attachment;filename="GeneralReportFor ' . "Report Consumable Periode $tanggalMulai~$tanggalSelesai ~~ $job" . '.xls"');
header("Pragma: no-cache");
header("Expires: 0");
$selisih = date_diff(date_create($end), date_create($start));
$s = $selisih->format("%a")
?>
<div class="col-sm-12">
    <table class="table table-striped table-bordered" style="border: 1px ridge black;">
        <thead>  
            <tr>
                <th colspan="<?php echo "$s"; ?>">LAPORAN PEMAKAIAN KOSUMABLE PER HARI/PER JOB, UNTUK DIBUATKAN PO PERIODE <?php echo "$tanggalMulai~$tanggalSelesai"; ?></th>
            </tr>
            <tr>
                <th colspan="<?php echo "$s"; ?>">JOB : <?php echo "$job"; ?></th>
            </tr>
            <tr>
                <th colspan="<?php echo "$s"; ?>"></th>
            </tr>
            <tr>
                <th style="border: 1px ridge black;">NAMA BARANG</th>
                <?php
                $arrayTangal = array();
                $dateSql = "SELECT TO_CHAR(TO_DATE ('$start', 'mm/dd/yyyy') + ROWNUM - 1,'DD-MM-YYYY') as tanggal "
                        . "FROM all_objects "
                        . "WHERE ROWNUM <= TO_DATE ('$end', 'mm/dd/yyyy') - TO_DATE ('$start', 'mm/dd/yyyy') + 1";
                $dateParse = oci_parse($conn, $dateSql);
                oci_execute($dateParse);
                while ($row = oci_fetch_array($dateParse)) {
                    echo "<th style='border: 1px ridge black;' class='text-center'>$row[TANGGAL]</th>";
                    array_push($arrayTangal, $row['TANGGAL']);
                }
                ?>
                <th style="border: 1px ridge black;" class='text-center'>TOTAL</th>
            </tr>
        </thead>
        <tbody> 
            <?php
            $sql = "SELECT DISTINCT INV_ID, INV_DESC "
                    . "FROM MASTER_INV@WELTESMART_WENLOGINV_LINK "
                    . "WHERE INV_WM_SELECT = '1' "
                    . "ORDER BY INV_DESC ASC";
            $parse = oci_parse($conn, $sql);
            oci_execute($parse);

            while ($row1 = oci_fetch_array($parse)) {
                $total = 0;
                echo "<tr>";
                echo "<td style='border: 1px ridge black;'>$row1[INV_DESC]</td>";
                for ($index = 0; $index < count($arrayTangal); $index++) {
                    $querySQl = "SELECT nvl(SUM( MART_WR_INV_QTY),0) "
                            . "FROM MART_CHECKOUT_INFO "
                            . "WHERE MART_WR_JOB = '$job' "
                            . "AND TO_CHAR(MART_WR_DATE, 'DD-MM-YYYY') = '$arrayTangal[$index]' "
                            . "AND MART_WR_INV_ID = '$row1[INV_ID]'";
                    $query = intval(SingleQryFld("$querySQl", $conn));
                    echo "<td style='border: 1px ridge black;'>$query</td>";
                    $total+=intval($query);
                }
                echo "<td style='border: 1px ridge black;'>$total</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>
