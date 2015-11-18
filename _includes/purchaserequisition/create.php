<?php
require_once('../../_config/dbinfo.inc.php');
require_once('../../_config/misc.func.php');
$conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB) or die;
$globalName = SingleQryFld("SELECT WMS.SETTING_VALUE_STRING FROM MART_SETTINGS WMS WHERE WMS.SETTING_DESC = 'GLOBAL_NAME'", $conn);
$todaysDate = date("m/d/y");
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>CREATE PURCHASE REQUISITION<small> &nbsp; Intended for requesting stock to <?= $globalName ?></small></h1>
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
                        <h5>Purchase Requisition Number # : 
                            <span style="font-weight: bold;">
                                <a href="#" id="pr-number" data-type="text" data-pk="1" data-url="/post" data-title="Enter New PR#">WM-PR-00001</a></span>  
                        </h5>
                        <!--<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>-->
                    </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" id="pr-date" value="<?php echo date("m/d/Y"); ?>">
                                </div><!-- /.input group -->
                            </div><!-- /.form group -->
                        </div>

                        <div class="col-md-4">
                            <select class="selectpicker" id="dlv-loc-pr" data-width="100%" data-title="DELIVERY LOCATION">
                                <option selected="">WAREHOUSE</option>
                                <option>SITE</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                <input type="text" class="form-control" placeholder="PR Remarks" id="remark">
                            </div>
                        </div>

                    </div>  

                    <div class="row">
                        <div class="col-md-12">
                            <table id="pr-list-inv" class="table table-condensed" cellspacing="0" width="100%">
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
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-block btn-default" id="add-pr-item">Add Another Item</button>
                        </div>
                    </div>

                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <button class="btn btn-block btn-success" id="submit-pr">SUBMIT PURCHASE REQUISITION</button>
        </div>
    </div>

</section>

<script>
    $('.selectpicker').selectpicker();

    $.fn.editable.defaults.mode = 'inline';

    $('#pr-date').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true
    });

    $(document).ready(function () {
//        $('#pr-number').editable();
        var counter = 1;
        var prlist_table = $('#pr-list-inv').dataTable(
                {
                    "bFilter": false,
                    "bInfo": false,
                    "bLengthChange": false,
                    "bPaginate": false
                });

        // ADDING ROW
        $('#add-pr-item').on('click', function () {
            var newTargetRow = prlist_table.fnAddData([
                "<select class='selectpicker' data-id='item-name' data-live-search='true' data-width='100%' title='Select Inventory...' id='inventory-detail-drop" + counter + "'></select>",
                "<input type='number' class='form-control' style='width: -moz-available;' value='1'/>",
                "<select class='selectpicker' data-id='item-unit' data-live-search='true' data-width='100%' title='Select Unit' id='item-unit" + counter + "'></select>",
                "<select id=brand-unit" + counter + " class='selectpicker form-control' multiple data-live-search='true'></select>",
                "<input type='text' class='form-control' style='width: -moz-available;'/></div>",
                "<i class='fa fa-trash fa-fw fa-lg text-danger' style='cursor: pointer;' onclick=DeleteItem(" + counter + ")></i>"
            ]);
            var oSettings = prlist_table.fnSettings();
            var nTr = oSettings.aoData[ newTargetRow[0] ].nTr;

            var row = 'rowtarget' + counter;
            nTr.setAttribute('id', row);

            $('td', nTr)[0].setAttribute('class', 'text-center');
            $('td', nTr)[1].setAttribute('class', 'text-center');
            $('td', nTr)[2].setAttribute('class', 'text-center');
            $('td', nTr)[3].setAttribute('class', 'text-center');
            $('td', nTr)[4].setAttribute('class', 'text-center');
            $('td', nTr)[5].setAttribute('class', 'text-center');
            $('.selectpicker').selectpicker();

            // FILLING INVENTORY DROPDOWN SELECTPICKER
            $('#inventory-detail-drop' + counter).each(function (index, value)
            {
                var initSelectpicker = $(this).selectpicker();
                $.getJSON('../_includes/purchaserequisition/process/inventory_list.php', function (data)
                {
                    initSelectpicker.html('');
                    $.each(data, function (key, val)
                    {
                        initSelectpicker.append('<option value=' + val["INV_ID"] + '>' + val["INV_DESC"] + '</option>');
                    });
                    initSelectpicker.selectpicker('refresh');
                });
            });
            $('#item-unit' + counter).each(function (index, value)
            {
                var initSelectpicker = $(this).selectpicker();
                $.getJSON('../_includes/purchaserequisition/process/unit_list.php', function (data)
                {
                    initSelectpicker.html('');
                    $.each(data, function (key, val)
                    {
                        initSelectpicker.append('<option value=' + val["INV_UNIT"] + '>' + val["INV_UNIT"] + '</option>');
                    });
                    initSelectpicker.selectpicker('refresh');
                });
            });

            $('#brand-unit' + counter).each(function (index, value)
            {
                var initSelectpicker = $(this).selectpicker();
                $.getJSON('../_includes/purchaserequisition/process/brand_list.php', function (data)
                {
                    initSelectpicker.html('');
                    $.each(data, function (key, val)
                    {
                        initSelectpicker.append('<option value=' + val["BRAND_ID"] + '>' + val["BRAND_NAME"] + '</option>');
                    });
                    initSelectpicker.selectpicker('refresh');
                });
            });
            counter++;
        });

        $.ajax({
            type: 'POST',
            url: "../_includes/purchaserequisition/process/process_pr.php",
            data: {
//                "job": $('#job-dropdown-pr').val(),
//                "subjob": $('#subjob-dropdown-pr').val(),
                "action": "get_pr_no"
            },
            success: function (response, textStatus, jqXHR) {
                $('#pr-number').text("WM-PR-" + response);
            }
        });

        $('#submit-pr').on('click', function () {
            var inv_id = [];
            var inv_qty = [];
            var inv_unit = [];
            var inv_brand = [];
            var inv_remark = [];
            var rows = $('#pr-list-inv').dataTable().fnGetNodes();
            for (var x = 0; x < rows.length; x++) {
                inv_id.push($(rows[x]).find("td:eq(0)").find("select").val());
                inv_qty.push($(rows[x]).find("td:eq(1)").find("input").val());
                inv_unit.push($(rows[x]).find("td:eq(2)").find("select").val());
                inv_brand.push($(rows[x]).find("td:eq(3)").find("select").val());
                inv_remark.push($(rows[x]).find("td:eq(4)").find("input").val());
            }
            var pr_no = $('#pr-number').text().trim();
            var date = $('#pr-date').val();
            var location = $('#dlv-loc-pr').val();
            var remark = $('#remark').val();

            var sentReq = {
                action: "submit_pr",
                pr_no: pr_no,
                date: date,
                location: location,
                remark: remark,
                inv_id: inv_id,
                inv_qty: inv_qty,
                inv_remark: inv_remark,
                inv_unit: inv_unit,
                inv_brand: inv_brand
            };
            if (inv_id.length == 0) {
                swal("TOLONG ISIKAN MINIMAL 1 BARANG", "!!!!", "error")
            } else {
                console.log(sentReq);
                var cf = confirm("DO YOU WANT SUBMIT THIS PR ?");
                if (cf == true) {
                    $.ajax({
                        type: 'POST',
                        url: "../_includes/purchaserequisition/process/process_pr.php",
                        data: sentReq,
                        success: function (response, textStatus, jqXHR) {
                            if (response.indexOf("GAGAL") >= 0) {
                                swal("GAGAL INSERT", response, "success");
                            } else {
                                swal("SUKSES INSERT", "good job", "success");
                                pr('CREATE_PR');
                            }
                        }
                    });
                } else {
                    return false;
                }
            }
        });
    });
    function DeleteItem(param) {
        var table_targetrem = $('#pr-list-inv').DataTable();
        table_targetrem.row('#rowtarget' + param).remove().draw(false);
    }
</script>