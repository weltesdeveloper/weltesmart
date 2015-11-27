<?php
require_once('../../../_config/dbinfo.inc.php');
require_once('../../../_config/misc.func.php');
$conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB) or die;
$todaysDate = date("m/d/y");
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>Stock On Hand Report<small> &nbsp; Periodic Range and Specific Stock Variables</small></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Reports</a></li>
        <li class="active">Stock On Hand</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-3 col-xs-3 col-lg-3">
            <div class="form-group">   
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" class="form-control pull-right" id="period-stock-on-hand">
                </div><!-- /.input group -->
            </div><!-- /.form group -->
        </div>
        <div class="col-md-9 col-xs-9 col-lg-9">
            <div class="input-group input-group-sm">
                <select class="selectpicker form-control" data-live-search='true' multiple title="Select Single or Multiple Categories" data-size="auto" data-style="btn-primary" id='inv-type-select'>
                    <option value="%" selected="">ALL</option>
                    <?php
                    $invCatParse = oci_parse($conn, "SELECT DISTINCT MI.INV_TYPE FROM MASTER_INV@WELTESMART_WENLOGINV_LINK MI WHERE MI.INV_WM_SELECT = '1' ORDER BY MI.INV_TYPE ASC");
                    $invExcErr = oci_execute($invCatParse);
                    if (!$invExcErr) {
                        $e = oci_error($invCatParse);
                        print htmlentities($e['message']);
                        print "\n<pre>\n";
                        print htmlentities($e['sqltext']);
                        printf("\n%" . ($e['offset'] + 1) . "s", "^");
                        print "\n</pre>\n";
                    }

                    while ($row = oci_fetch_array($invCatParse)) {
                        ?>
                        <option value='<?php echo $row['INV_TYPE']; ?>'><?php echo $row['INV_TYPE']; ?></option>
                        <?php
                    }
                    ?>
                </select>
                <span class="input-group-btn">
                    <button class="btn btn-success" type="button" id='showInvButton' onclick="ShowInv();">Show Inventory</button>
                </span>
            </div><!-- /input-group -->
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-xs-12 col-lg-12">
            <div class="box box-default box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><b>MAIN REPORT</b> ~ <span id="specific-inv-info">ALL</span> <small>All Categories Selected</small></h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-xs btn-default" id="export-inv-report-excel"><i class="fa fa-print"></i>&nbsp;EXCEL</button>
                        <button class="btn btn-xs btn-default" id="export-inv-report-pdf"><i class="fa fa-print"></i>&nbsp;PDF</button>
                        <!-- In box-tools add this button if you intend to use the contacts pane -->
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-toggle="tooltip" title="Contacts" data-widget="chat-pane-toggle"><i class="fa fa-comments"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                    <table id="inventory-report-table" class="table table-bordered table-responsive table-striped">
                        <thead>
                            <tr>
                                <th style="width: 7%">INV ID</th>
                                <th>INVENTORY DESCRIPTION</th>
                                <th class="text-center" style="width: 6%">UNIT</th>
                                <th class="text-center" style="width: 10%">ON HAND</th>
                            </tr>
                        </thead>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
</section>
<a href="process/inv_report_print_pdf.php"></a>
<script>
    $('#period-stock-on-hand').daterangepicker();
    $('#inv-type-select').selectpicker();

    $('#export-inv-report-pdf').on('click', function (e) {
        var inv_id = $(this).val();
        e.preventDefault();
        $.ajax({
            type: "POST",
            processData: false,
            contentType: "application/xml; charset=utf-8",
            url: '../_includes/reports/stockonhand/process/inv_report_print_pdf.php',
            success: function (data)
            {
                window.open('../_includes/reports/stockonhand/process/inv_report_print_pdf.php?inv_id=' + inv_id);
            }
        });
    });

    function FirstLoad() {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "../_includes/inventory/process/process.php",
            data: {invType: '%', "action": "load_data"},
            success: function (response) {
                var table = $('#inventory-report-table').DataTable({
                    destroy: true,
                    processing: true,
                    data: response,
                    pageLength: 15,
                    "columns":
                            [
                                {"data": "INV_ID"},
                                {"data": "INV_DESC"},
                                {"data": "INV_UNIT", className: "text-center"},
                                {"data": "INV_STK_QTY", className: "text-center"}
                            ]
                });
            }
        });
    }

    function ShowInv() {
        var inv_type = $('#inv-type-select').val();
        console.log(inv_type);
        $.ajax({
            type: 'POST',
            data: {invType: inv_type, "action": "select_inv"},
            url: "../_includes/reports/stockonhand/process/process_stockhand.php",
            success: function (response, textStatus, jqXHR) {
                console.log(response);
            }
        });
    }


    $(document).ready(function () {
        FirstLoad();
    });
</script>