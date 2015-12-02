<?php
require_once('../../../_config/dbinfo.inc.php');
require_once('../../../_config/misc.func.php');
$conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB) or die;
$todaysDate = date("m/d/y");
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>Warehouse Receipt Report<small> &nbsp; Track your warehouse receipt record</small></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Reports</a></li>
        <li class="active">Warehouse Receipt</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-user-plus"></i><b>&nbsp;&nbsp;LIST CHECKOUT PER JOB</b> </h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-xs btn-default" id="export-customer-report-pdf"><i class="fa fa-print"></i>&nbsp;PRINT CUSTOMER LIST IN PDF</button>
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="email">JOB</label>
                            <div class="col-sm-10">
                                <select class="selectpicker" data-live-search="true" data-width="100%" id="job">
                                    <?php
                                    $sql = "SELECT DISTINCT MART_WR_JOB FROM MART_MST_CHKOUT ORDER BY MART_WR_JOB ASC";
                                    $parse = oci_parse($conn, $sql);
                                    oci_execute($parse);
                                    while ($row = oci_fetch_array($parse)) {
                                        echo "<option value='$row[MART_WR_JOB]'>$row[MART_WR_JOB]</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="pwd">START DATE</label>
                            <div class="col-sm-10"> 
                                <input type="text" class="form-control" id="start" placeholder="Enter password" value="<?php echo date("m/d/Y"); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="pwd">END DATE</label>
                            <div class="col-sm-10"> 
                                <input type="text" class="form-control" id="end" placeholder="Enter password" value="<?php echo date("m/d/Y"); ?>">
                            </div>
                        </div>
                        <div class="form-group"> 
                            <div class="col-sm-offset-2 col-sm-12">
                                <button type="button" class="btn btn-primary col-sm-10" onclick="PrintData();">Print</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-user-plus"></i><b>&nbsp;&nbsp;STOCK AWAL DAN STOCK AKHIR</b> </h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-xs btn-default" id="export-customer-report-pdf"><i class="fa fa-print"></i>&nbsp;PRINT CUSTOMER LIST IN PDF</button>
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="pwd">START DATE</label>
                            <div class="col-sm-10"> 
                                <input type="text" class="form-control" id="stock-start" placeholder="Enter password" value="<?php echo date("m/d/Y"); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="pwd">END DATE</label>
                            <div class="col-sm-10"> 
                                <input type="text" class="form-control" id="stock-end" placeholder="Enter password" value="<?php echo date("m/d/Y"); ?>">
                            </div>
                        </div>
                        <div class="form-group"> 
                            <div class="col-sm-offset-2 col-sm-12">
                                <button type="button" class="btn btn-primary col-sm-10" onclick="PrintData2();">Print</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $('.selectpicker').selectpicker();
    $("#start").datepicker();
    $("#end").datepicker();
    
    $("#stock-start").datepicker();
    $("#stock-end").datepicker();

    function PrintData() {
        var job = $('#job').val();
        var start = $('#start').val();
        var end = $('#end').val();

        window.open("../_resources/tools/PHPToExcel/report_excel.php?job=" + job + "&start=" + start + "&end=" + end);
    }
    
    function PrintData2(){
        var start = $('#start').val();
        var end = $('#end').val();
        window.open("../_resources/tools/PHPToExcel/report_excel2.php?start=" + start + "&end=" + end);
    }
</script>