<?php
require_once('../../_config/dbinfo.inc.php');
require_once('../../_config/misc.func.php');
$conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB) or die;
$globalName = SingleQryFld("SELECT WMS.SETTING_VALUE_STRING FROM MART_SETTINGS WMS WHERE WMS.SETTING_DESC = 'GLOBAL_NAME'", $conn);
$todaysDate = date("m/d/y");
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>REVISE PURCHASE REQUISITION<small> &nbsp; Revise for requesting stock to <?= $globalName ?></small></h1>
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
                        <h5>Select PR No From DropDown Below
                            <span style="font-weight: bold;">
                                <!--<a href="#" id="pr-number" data-type="text" data-pk="1" data-url="/post" data-title="Enter New PR#">WM-PR-00001</a>-->
                            </span>  
                        </h5>
                        <!--<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>-->
                    </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group">
                                <select class="selectpicker" id="pr-no" onchange="ChangePrNo();">
                                </select>
                            </div>
                        </div>
                    </div>  
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-block btn-primary" id="show-item">Show Data</button>
                        </div>
                    </div>

                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-cubes"></i>LIST DETAIL ITEM <span id="span-pr-no"></span></h3>
                    <div class="box-tools pull-right">
                        <h5>Purchase Requisition Number # : 
                            <span id="pr-no-edit" style="font-weight: bold;">

                            </span>
                        </h5>
                    </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                    <table id="pr-list-edit" class="table table-condensed" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th style="width: 50%;" class="text-center">INVENTORY</th>
                                <th class="text-center">QUANTITY</th>
                                <th class="text-center">UNIT</th>
                                <th class="text-center">BRANDS</th>
                                <th class="text-center">REMARKS</th>
                                <th style="width: 6%;" class="text-center">DELETE</th>
                            </tr>
                        </thead>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
</section>

<script>
    $('.selectpicker').selectpicker();
    $(document).ready(function () {
        var initSelectpicker = $('#pr-no').selectpicker();
        $.ajax({
            type: 'POST',
            url: '../_includes/purchaserequisition/revise/process.php',
            data: {action: "get_pr"},
            dataType: 'JSON',
            beforeSend: function (xhr) {
                initSelectpicker.html('');
            },
            success: function (response, textStatus, jqXHR) {
                initSelectpicker.append('<option value="" disabled selected></option>');
                $.each(response, function (key, val)
                {
                    initSelectpicker.append('<option value=' + val["MART_PR_NO"] + '>' + val["MART_PR_NO"] + '</option>');
                });
                initSelectpicker.selectpicker('refresh');
            }
        });

        $('#show-item').on('click', function () {
            var pr_no = $('#pr-no').val();
            $.ajax({
                type: 'POST',
                url: '../_includes/purchaserequisition/revise/process.php',
                data: {"action": "show_revise", pr_no: pr_no},
                dataType: 'JSON',
                beforeSend: function (xhr) {
                    $('#pr-list-edit').DataTable().destroy();
                    $('#pr-list-edit tbody').empty();
                },
                success: function (response, textStatus, jqXHR) {
                    var content = "";
                    $.each(response, function (key, value) {
                        content += "<tr>" +
                                "<td>" + value.INV_DESC + "</td>" +
                                "<td>" + "<input type='number' class='form-control' style='width: -moz-available;' value='" + value.MART_INV_QTY + "'/>" + "</td>" +
                                "<td>" + value.MART_INV_UNIT + "</td>" +
                                "<td>" + value.BRAND_NAME + "</td>" +
                                "<td>" + '<select class="form-control"><option>Mustard</option><option>Ketchup</option><option>Relish</option></select>' + "</td>" +
                                "<td>" + "<i class='fa fa-trash fa-fw fa-lg text-danger' style='cursor: pointer;' onclick=DeleteItem(" + key + ")></i>" + "</td>" +
                                "</tr>";
                    });
                    $('#pr-list-edit tbody').append(content);
                },
                complete: function () {
                    $('#pr-list-edit').DataTable({
                    });
                }
            });
        });
    });

    function ChangePrNo() {
        var prno = $('#pr-no').val();
        $('#pr-no-edit').text(prno);
    }
</script>