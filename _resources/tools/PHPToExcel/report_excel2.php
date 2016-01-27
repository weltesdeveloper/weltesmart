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
                <th colspan="<?php echo "6"; ?>">LAPORAN STOCK AWAL DAN STOCK AKHIR GUDANG PERIODE <?php echo "$tanggalMulai S/D $tanggalSelesai"; ?></th>
            </tr>
            <tr>
                <th style="border: 1px ridge black;">NO</th>
                <th style="border: 1px ridge black;">NAMA BARANG</th>
                <th style="border: 1px ridge black;" class='text-center'>STOK AWAL</th>
                <th style="border: 1px ridge black;" class='text-center'>STOK IN</th>
                <th style="border: 1px ridge black;" class='text-center'>STOK OUT</th>
                <th style="border: 1px ridge black;" class='text-center'>STOK AKHIR</th>
            </tr>
        </thead>
        <tbody> 
            <?php
            $sql = "SELECT MSI.INV_ID,
         MSI.INV_DESC,
         MSI.INV_STK_QTY AS STOCK_AKHIR,
         X.MASUK,
         X.KELUAR,
         X.SELISIH,
         MSI.INV_STK_QTY - X.SELISIH AS STOCK_AWAL
    FROM MART_STOCK_INFO MSI
         INNER JOIN
         (WITH MASUK
               AS (  SELECT MART_CHECKIN_INV_ID,
                            SUM (MART_CHECKIN_INV_QTY) MART_CHECKIN_INV_QTY--,
                            --MART_CHECKIN_DATE,
                            --MART_CHECKIN_SYSDATE
                       FROM MART_DTL_CHKIN MDC
                            INNER JOIN MART_MST_CHECKIN MMC
                               ON MMC.MART_CHECKIN_ID = MDC.MART_CHECKIN_ID
                      WHERE MART_CHECKIN_DATE BETWEEN TO_DATE ('$start',
                                                               'MM/DD/YYYY')
                                                  AND TO_DATE ('$end',
                                                               'MM/DD/YYYY')
                   GROUP BY MART_CHECKIN_INV_ID--,
                            --MART_CHECKIN_DATE,
                            --MART_CHECKIN_SYSDATE
                            ),
               KLR
               AS (  SELECT NVL (SUM (MART_WR_INV_QTY), 0) KELUAR,
                            MART_WR_INV_ID,
                            INV_DESC
                       FROM MART_CHECKOUT_INFO
                      WHERE MART_WR_DATE BETWEEN TO_DATE ('$start',
                                                          'MM/DD/YYYY')
                                             AND TO_DATE ('$end',
                                                          'MM/DD/YYYY')
                   GROUP BY MART_WR_INV_ID, INV_DESC
                   ORDER BY MART_WR_INV_ID)
          SELECT KLR.MART_WR_INV_ID,
                 KLR.INV_DESC,
                 NVL (MASUK.MART_CHECKIN_INV_QTY, 0) MASUK,
                 KLR.KELUAR,
                 NVL (MASUK.MART_CHECKIN_INV_QTY, 0) - KLR.KELUAR AS SELISIH
            FROM KLR
                 FULL OUTER JOIN MASUK
                    ON MASUK.MART_CHECKIN_INV_ID = KLR.MART_WR_INV_ID) X
            ON MSI.INV_ID = X.MART_WR_INV_ID
ORDER BY MSI.INV_DESC ASC";
//            echo "$sql";
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
                            <?php echo "$row[MASUK]";?>
                        </td>
                        <td style="border: 1px ridge black; text-align: center;">
                            <?php echo "-$row[KELUAR]";?>
                        </td>
                        <td style="border: 1px ridge black; text-align: center;">
                            <?php echo "$row[STOCK_AKHIR]";?>
                        </td>
                    </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>
