<?php
require_once('../../../_config/dbinfo.inc.php');
require_once('../../../_config/misc.func.php');
$conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB) or die;
$start = $_GET['start'];
$end = $_GET['end'];
header("Content-type: application/octet-stream");
$formattedFileName = date("m/d/Y_h:i", time());
// simpan file excel dengan nama umr2013.xls
//saat file berhasil di buat, otomatis pop up download akan muncul
$tanggalMulai = date("d F Y", strtotime($start));
$tanggalSelesai = date("d F Y", strtotime($end));
header('Content-Disposition: attachment;filename="GeneralReportFor ' . "Laporan Stock Awal dan Stock Akhir $tanggalMulai~$tanggalSelesai" . '.xls"');
header("Pragma: no-cache");
header("Expires: 0");
$selisih = date_diff(date_create($end), date_create($start));
$s = $selisih->format("%a")
?>
<div class="col-sm-12">
    <table class="table table-striped table-bordered" style="border: 1px ridge black;">
        <thead>  
            <tr>
                <th colspan="<?php echo "3"; ?>">LAPORAN STOCK AWAL DAN STOCK AKHIR GUDANG PERIODE <?php echo "$tanggalMulai S/D $tanggalSelesai"; ?></th>
            </tr>
            <tr>
                <th style="border: 1px ridge black;">NO</th>
                <th style="border: 1px ridge black;">NAMA BARANG</th>
                <th style="border: 1px ridge black;" class='text-center'>STOK AWAL</th>
                <th style="border: 1px ridge black;" class='text-center'>STOK AKHIR</th>
            </tr>
        </thead>
        <tbody> 
            <?php
            $sql = "WITH STOK_AKHIR
     AS (  SELECT INV_ID, INV_STK_QTY HIST_ADJUST, INV_DESC
             FROM MART_STOCK_INFO
         --GROUP BY INV_ID
         ORDER BY INV_ID ASC),
     OUT
     AS (  SELECT NVL (SUM (MART_WR_INV_QTY), 0) KELUAR, MART_WR_INV_ID, INV_DESC
             FROM MART_CHECKOUT_INFO
            WHERE MART_WR_DATE BETWEEN TO_DATE ('$start', 'MM/DD/YYYY')
                                   AND TO_DATE ('$end', 'MM/DD/YYYY')
         GROUP BY MART_WR_INV_ID,INV_DESC
         ORDER BY MART_WR_INV_ID)
SELECT STOK_AKHIR.INV_ID,
       NVL (OUT.MART_WR_INV_ID, 0) MART_WR_INV_ID,
       STOK_AKHIR.HIST_ADJUST,
       NVL (OUT.KELUAR, 0) KELUAR,
       STOK_AKHIR.INV_DESC,
       STOK_AKHIR.HIST_ADJUST + NVL (OUT.KELUAR, 0) STOCK_AWAL
  FROM STOK_AKHIR
       FULL OUTER JOIN OUT ON OUT.MART_WR_INV_ID = STOK_AKHIR.INV_ID";
            $parse = oci_parse($conn, $sql);
            oci_execute($parse);
            $i=1;
            while ($row = oci_fetch_array($parse)) {
                ?>
                    <tr>
                        <td>
                            <?php echo $i++;?>
                        </td>
                        <td style="border: 1px ridge black;">
                            <?php echo "$row[INV_DESC]";?>
                        </td>
                        <td style="border: 1px ridge black; text-align: center;">
                            <?php echo "$row[STOCK_AWAL]";?>
                        </td>
                        <td style="border: 1px ridge black; text-align: center;">
                            <?php echo "$row[HIST_ADJUST]";?>
                        </td>
                    </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>
