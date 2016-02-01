<?php
require_once('../../../_config/dbinfo.inc.php');
require_once('../../../_config/misc.func.php');
$conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB) or die;
$start = $_GET['start'];
$end = $_GET['end'];
$formattedFileName = date("m/d/Y_h:i", time());
$tanggalMulai = date("d F Y", strtotime($start));
$tanggalSelesai = date("d F Y", strtotime($end));

header("Content-type: application/octet-stream");
header('Content-Disposition: attachment;filename="GeneralReportFor ' . "Laporan Stock Awal dan Stock Akhir $tanggalMulai~$tanggalSelesai" . '.xls"');
header("Pragma: no-cache");
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
            $sql = "WITH INV
                        AS (SELECT MII.INV_ID, MII.INV_DESC
                              FROM MART_INV_INFO MII),
                        S_NOW
                        AS (  SELECT INV_ID, INV_DESC, SUM (TRANS_QTY) AS STOCK_SEKARANG
                                FROM VW_STOCK_HIST VSI
                               WHERE TRANS_DATE <= TO_DATE ('$end', 'MM/DD/YYYY')
                            GROUP BY INV_ID, INV_DESC
                            ORDER BY INV_DESC),
                        S_OUT
                        AS (  SELECT MART_WR_INV_ID INV_ID,
                                     INV_DESC,
                                     SUM (MART_WR_INV_QTY) AS STOCK_OUT
                                FROM MART_CHECKOUT_INFO
                               WHERE MART_WR_DATE BETWEEN TO_DATE ('$start', 'MM/DD/YYYY')
                                                      AND TO_DATE ('$end', 'MM/DD/YYYY')
                            GROUP BY MART_WR_INV_ID, INV_DESC
                            ORDER BY INV_DESC),
                        S_IN
                        AS (  SELECT MART_CHECKIN_INV_ID INV_ID,
                                     INV_DESC,
                                     SUM (MART_CHECKIN_INV_QTY) AS STOCK_IN
                                FROM MART_CHECKIN_INFO
                               WHERE MART_CHECKIN_DATE BETWEEN TO_DATE ('$start',
                                                                        'MM/DD/YYYY')
                                                           AND TO_DATE ('$end',
                                                                        'MM/DD/YYYY')
                            GROUP BY MART_CHECKIN_INV_ID, INV_DESC
                            ORDER BY INV_DESC)
                   SELECT INV.INV_ID,
                          INV.INV_DESC,
                          NVL (S_NOW.STOCK_SEKARANG, 0) STOCK_SEKARANG,
                          NVL (S_IN.STOCK_IN, 0) STOCK_IN,
                          -1 * NVL (S_OUT.STOCK_OUT, 0) AS STOCK_OUT,
                            NVL (S_NOW.STOCK_SEKARANG, 0)
                          - NVL (S_IN.STOCK_IN, 0)
                          + NVL (S_OUT.STOCK_OUT, 0)
                             AS STOCK_AWAL
                     FROM INV
                          LEFT OUTER JOIN S_NOW ON S_NOW.INV_ID = INV.INV_ID
                          LEFT OUTER JOIN S_IN ON S_IN.INV_ID = INV.INV_ID
                          LEFT OUTER JOIN S_OUT ON S_OUT.INV_ID = INV.INV_ID
                          ORDER BY INV.INV_DESC ASC";
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
                            <?php echo "$row[STOCK_IN]";?>
                        </td>
                        <td style="border: 1px ridge black; text-align: center;">
                            <?php echo "$row[STOCK_OUT]";?>
                        </td>
                        <td style="border: 1px ridge black; text-align: center;">
                            <?php echo "$row[STOCK_SEKARANG]";?>
                        </td>
                    </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>
