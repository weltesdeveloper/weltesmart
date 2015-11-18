<?php
require_once('../../_config/dbinfo.inc.php');
require_once('../../_config/misc.func.php');
$conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB) or die;
$globalName = SingleQryFld("SELECT WMS.SETTING_VALUE_STRING FROM MART_SETTINGS WMS WHERE WMS.SETTING_DESC = 'GLOBAL_NAME'", $conn);
$todaysDate = date("m/d/y");
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>LIST PURCHASE REQUISITION<small> &nbsp; Purchase Requisition <?= $globalName ?></small></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Purchase Requisition</a></li>
        <li class="active">Create</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-cubes"></i>&nbsp;&nbsp;MORE DETAILS BELOW </h3>
                    <div class="box-tools pull-right">
                        <h5>List Purchase Requisition</h5>
                    </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" id="start-date">
                                </div><!-- /.input group -->
                            </div><!-- /.form group -->
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" id="end-date">
                                </div><!-- /.input group -->
                            </div><!-- /.form group -->
                        </div>

                        <div class="col-md-5">
                            <div class="input-group">
                                <select class="selectpicker" data-live-search="true" id="pr-no">
                                    <option value="">ALL</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-1">
                            <div class="input-group">
                                <button type="button" class="btn btn-primary" onclick="showPR();">SHOW</button>
                            </div>
                        </div>

                    </div>  

                    <div class="row">
                        <div class="col-md-12">
                            <table id="pr-list" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">PR #</th>
                                        <th class="text-center">PR DATE#</th>
                                        <th class="text-center">INV DESC</th>
                                        <th class="text-center">INV QTY/UNIT</th>
                                        <th class="text-center">REQUEST BY</th>
                                        <th class="text-center">REMARKS PER ITEM</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>

</section>

<script>
    $('.selectpicker').selectpicker();

    $.fn.editable.defaults.mode = 'inline';

    $('#start-date, #end-date').datepicker({
//        singleDatePicker: true,
//        showDropdowns: true
    });

    $(document).ready(function () {
        $.ajax({
            type: 'POST',
            url: "../_includes/purchaserequisition/process_list_pr/list_pr.php",
            data: {"action": ""},
            dataType: 'JSON',
            success: function (response, textStatus, jqXHR) {
                $('#start-date').val(response.tanggal[0].MIN_MART_PR_DATE);
                $('#end-date').val(response.tanggal[0].MAX_MART_PR_DATE);
                $('#pr-no').each(function (index, value) {
                    var initSelectpicker = $(this).selectpicker();
                    initSelectpicker.html('');
                    initSelectpicker.append('<option value="%">ALL</option>');
                    $.each(response.prno, function (key, val)
                    {
                        initSelectpicker.append('<option value=' + val["MART_PR_NO"] + '>' + val["MART_PR_NO"] + '</option>');
                    });
                    initSelectpicker.selectpicker('refresh');
                });
            }
        });
    });

    function showPR() {
        var start = $('#start-date').val();
        var end = $('#end-date').val();
        var prno = $('#pr-no').val();
        var sentReq = {
            start: start,
            end: end,
            prno: prno,
            action: "list_pr"
        };
        console.log(sentReq);
        $.ajax({
            type: 'POST',
            url: "../_includes/purchaserequisition/process_list_pr/list_pr.php",
            data: sentReq,
            dataType: 'JSON',
            success: function (response, textStatus, jqXHR) {
                $('#pr-list').DataTable({
                    destroy: true,
                    processing: true,
                    data: response,
                    pageLength: 15,
                    "columns": [
                        {"data": "MART_PR_NO"},
                        {"data": "MART_PR_DATE"},
                        {"data": "INV_DESC"},
                        {"data": "MART_INV_QTY"},
                        {"data": "MART_PR_SIGN"},
                        {"data": "MART_INV_REMARK"}
                    ],
                    "columnDefs": [
                        {
                            "targets": [0],
                            "visible": false,
                            "className": "text-center"
                        },
                        {
                            "targets": [1],
                            "className": "text-center"
                        },
                        {
                            "targets": [2],
                            "className": "text-center"
                        },
                        {
                            "targets": [3],
                            "className": "text-center"
                        },
                        {
                            "targets": [4],
                            "className": "text-center"
                        },
                        {
                            "targets": [5],
                            "className": "text-center"
                        }
                    ],
                    "drawCallback": function (settings) {
                        var api = this.api();
                        var rows = api.rows({page: 'current'}).nodes();
                        var last = null;

                        api.column(0, {page: 'current'}).data().each(function (group, i) {
                            if (last !== group) {
                                $(rows).eq(i).before(
                                        '<tr class="group"><td colspan="9" style="background-color:#95A68A">'
                                        + group + ' <button type="button" class="btn btn-primary btn-xs" style="float:right;" onclick="printPR(' + "'" + group + "'" + ')">PRINT</button></td></tr>'
                                        );

                                last = group;
                            }
                        });
                    }
                });
            }
        });
    }

    function printPR(param) {
        var prNumber = param;
        var URL = "../_includes/purchaserequisition/process_list_pr/print_pr.php?pr="+prNumber;
        PopupCenter(URL, 'popupInfoMPS', '800', '768');
    }
</script>