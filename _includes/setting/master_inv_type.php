<?php
require_once('../../_config/dbinfo.inc.php');
require_once('../../_config/misc.func.php');

$todaysDate = date("m/d/y");
$globalName = SingleQryFld("SELECT WMS.SETTING_VALUE_STRING FROM MART_SETTINGS WMS WHERE WMS.SETTING_DESC = 'GLOBAL_NAME'", $conn);
$var1sql = "SELECT WMS.SETTING_VALUE FROM MART_SETTINGS WMS WHERE WMS.SETTING_DESC = 'SESSION_TIMEOUT'";
$var1 = SingleQryFld($var1sql, $conn);
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1><?= $globalName ?> MASTER INVENTORY SELECTION<small> &nbsp; Adjust master inventory for <?= $globalName ?></small></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Settings</a></li>
        <li class="active">Master Inventory</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-xs-12">

            <div class="box box-default box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-truck"></i><b>&nbsp;&nbsp;SELECT MASTER INVENTORY LIST</b> </h3>
                    <div class="box-tools pull-right">
                        <!-- In box-tools add this button if you intend to use the contacts pane -->
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-condensed table-responsive" id="master-inv-selection" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>CATEGORY</th>
                                <th>TYPE</th>
                                <th>ID</th>
                                <th>INVENTORY NAME</th>
                                <th>BRAND</th>
                                <th>UNIT</th>
                                <th>GRADE</th>
                                <th>REGISTER</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>CATEGORY</th>
                                <th>TYPE</th>
                                <th></th>
                                <th></th>
                                <th>BRAND</th>
                                <th></th>
                                <th>GRADE</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>
</section>

<script>
    function listMasterInvJson(handleData) {
        return $.ajax({
            type: "POST",
            dataType: 'json',
            url: "../_includes/setting/process/process_master_inventory.php",
            success: function (json) {
                handleData(json);
            }
        });
    }

    function feedToMasterInvtable() {
        listMasterInvJson(function (response) {
            var table = $('#master-inv-selection').DataTable({
                initComplete: columnSelectProp,
                processing: true,
                data: response,
                pageLength: 18,
                columns:
                        [
                            {"data": "INV_CAT"},
                            {"data": "INV_TYPE"},
                            {"data": "INV_ID"},
                            {"data": "INV_DESC"},
                            {"data": "INV_BRAND"},
                            {"data": "INV_UNIT"},
                            {"data": "INV_GRD"},
                            {"data": "INV_GRD"}
                        ],
                columnDefs:
                        [
                            {
                                visible: true,
                                targets: 7,
                                className: 'text-center',
                                render: function (data, type, row, meta) {
                                    var checked="";
                                    if(row.INV_WM_SELECT=='1'){
                                        checked='checked=""';
                                    }
                                    var content = '<div class="form-group">\n\
                                                        <label>\n\
                                                            <input type="checkbox" '+checked+' class="flat-red" id="register-check' + row.INV_ID + '" onclick=registerelement("' + row.INV_ID + '");>\n\
                                                        </label>\n\
                                                   </div>';
                                    return content;
                                }
                            }
                        ],
                drawCallback: function (settings) {

                }
            });
        });
    }

    function registerelement(param) {
        if ($('#register-check' + param).is(':checked')){
            var inv_id = param;
            var check_id = 'UPDATE';
            $.ajax({
                type : 'POST',
                data : {inv_id, check_id},
                url : '../_includes/setting/process/process_select_inventory.php'
            });
        } else {
            swal({
                title: 'Remove ' + param + '?',
                text: "You are about to remove this inventory !",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, remove it!",
                cancelButtonText: "No, cancel!",
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function(isConfirm){
                if (isConfirm) {
                    var inv_id = param;
                    var check_id = 'REMOVE';
                    $.ajax({
                        type : 'POST',
                        data : {inv_id, check_id},
                        url : '../_includes/setting/process/process_select_inventory.php',
                        success : function(){
                            $('#register-check' + param).prop('unchecked', true);
                        }
                    });
                } else {
                    $('#register-check' + param).prop('checked', true);
                }
            }); // END OF SWAL
        }
    }


    function columnSelectProp() {
        var arr = [0, 1, 4, 6];
        this.api().columns(arr).every(function () {
            var column = this;
            var select = $('<select class="selectpicker" data-width="100%" data-live-search="true" data-style="btn-primary" title="Filter.."><option value=""></option></select>')
                    .appendTo($(column.footer()).empty())
                    .on('change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                                );

                        column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                    });
            column.data().unique().sort().each(function (d, j) {
                select.append('<option value="' + d + '">' + d + '</option>')
            });
            $('.selectpicker').selectpicker();
        });
    }



    $(document).ready(function () {
        feedToMasterInvtable();
    });
</script>