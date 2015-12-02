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
                <th style="border: 1px ridge black;" class='text-center'>STOK AWAL</th>
                <th style="border: 1px ridge black;" class='text-center'>STOK AKHIR</th>
            </tr>
        </thead>
        <tbody> 
            <?php
                $sql = "SELECT "
            ?>
        </tbody>
    </table>
</div>
