<?php
require_once('../../_config/dbinfo.inc.php');
require_once('../../_config/misc.func.php');
$conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB) or die;
$todaysDate = date("m/d/y");
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>INVENTORY ADJUSTMENT<small> &nbsp; Adjust stock quantity based on opname</small></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Inventory</a></li>
        <li class="active">Adjust</li>
    </ol>
</section>

<section class="content">
    <br/>
    <div class="row">
        <div class="col-md-12">Most used items &nbsp;
            <button class="btn btn-xs btn-default">BATU GRINDA</button>
            <button class="btn btn-xs btn-default">CUTTING TIP</button>
            <button class="btn btn-xs btn-default">GLOVES</button>
        </div>
    </div><br/>
    <div class="row">
        <div class="col-md-12">
            <div class="input-group input-group-sm">
                <select class="selectpicker form-control" data-live-search='true' id='inv-type-select'>
                    <option value='%' selected>ALL</option>
                    <?php
                    $invCatParse = oci_parse($conn, "SELECT DISTINCT MI.INV_TYPE FROM MASTER_INV@WELTESMART_WENLOGINV_LINK MI WHERE MI.INV_CAT = 'CONSUMABLE' ORDER BY MI.INV_TYPE ASC");
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
                    <button class="btn btn-primary" type="button" id='showInvButton'>Show Inventory</button>
                </span>
            </div><!-- /input-group -->
        </div>
    </div>

    <br/>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><b>MAIN INVENTORY</b> ~ <span id="specific-inv-info">ALL</span> <small>You can set stock adjustment or even make some adjustment to the minimum stock warning</small></h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-xs btn-default"><i class="fa fa-key"></i>&nbsp;TOGGLE ADJUSTMENT</button>
                        <button class="btn btn-xs btn-default"><i class="fa fa-battery-quarter"></i>&nbsp;SET MIN STOCK</button>
                        <!-- In box-tools add this button if you intend to use the contacts pane -->
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-toggle="tooltip" title="Contacts" data-widget="chat-pane-toggle"><i class="fa fa-comments"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                    <table id="inventory-adjust-table" class="table table-bordered table-responsive table-striped">
                        <thead>
                            <tr>
                                <th style="width: 7%">ID</th>
                                <th>DESCRIPTION</th>
                                <th class="text-center" style="width: 6%">UNIT</th>
                                <th class="text-center" style="width: 10%">ON HAND</th>
                                <th class="text-center" style="width: 10%">MINIMUM STOCK</th>
                                <th class="text-center" style="width: 10%">STATUS</th>
                                <th class="text-center" style="width: 16%">DETAILS/ACTIONS</th>
                            </tr>
                        </thead>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>

        <!--MODAL BOOTSTRAP-->
        <div class="modal modal-default fade" id="myModal" role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title text-center">Adjustment History for 
                            <span id="inv-id"></span>
                        </h4>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped table-bordered" id="modal-table">
                            <thead>
                                <tr>
                                    <th class="text-center">DATE</th>
                                    <th class="text-center">INV DESC</th>
                                    <th class="text-center">LOG</th>
                                    <th class="text-center">REMARK</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th class="text-center">DATE</th>
                                    <th class="text-center">INV DESC</th>
                                    <th class="text-center">LOG</th>
                                    <th class="text-center">REMARK</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
        <!-- END MODAL BOOTSTRAP -->    

        <!-- ITEM DETAILS MODAL -->
        <div class="modal modal-default fade" id="inv-detail-modal" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title text-center">Inventory Details For 
                            <span id="inv-id-details"></span>
                        </h4>
                    </div>
                    <div class="modal-body">

                    </div>
                </div>
            </div>
        </div>
        <!-- END ITEM DETAILS MODAL -->

    </div>
</section>
<script>
    $('.selectpicker').selectpicker();

    $('#showInvButton').on('click', function () {
        feedToTable();
    });

    //GRAB JSON FROM ANOTHER FILE
    function listCompJson(handleData) {
        var invType = $('#inv-type-select option:selected').val();
        return $.ajax({
            type: "POST",
            dataType: 'json',
            url: "../_includes/inventory/process/process.php",
            data: {invType: invType, "action": "load_data"},
            success: function (json) {
                handleData(json);
            }
        });
    }

    //FEED JSON DATA TO DATATABLE
    function feedToTable() {
        listCompJson(function (response) {
            var initMinStock = 0;
            var idNO = 0;
            var table = $('#inventory-adjust-table').DataTable({
                destroy: true,
                processing: true,
                data: response,
                pageLength: 15,
                "columns":
                        [
                            {"data": "INV_ID"},
                            {"data": "INV_DESC"},
                            {"data": "INV_UNIT", className: "text-center"}
                        ],
                "columnDefs":
                        [
                            {
                                "visible": true,
                                "targets": [3],
                                "className": 'text-center',
                                "render": function (data, type, row, meta) {
                                    var isi = '<a data-type="number" style="cursor:pointer" class="initStockClass text-center" data-pk="' + row.INV_ID + '">' + row.INV_STK_QTY + '</a>';
                                    return isi;
                                }
                            },
                            {
                                "visible": true,
                                "targets": [4],
                                "className": 'text-center',
                                "render": function (data, type, row, meta) {
                                    var isi = '<a data-type="number" style="cursor:pointer" class="initMinStockClass" data-pk="' + row.INV_ID + '">' + row.INV_STK_MIN + '</a>';
                                    return isi;
                                }
                            },
                            {
                                "visible": true,
                                "targets": [5],
                                "className": 'text-center',
                                "render": function (data, type, row, meta) {
                                    if (row.INV_STK_MIN > row.INV_STK_QTY) {
                                        return '<button type="button" class="btn btn-xs btn-danger" onclick=ordermanagement("CREATE_PR")>NEED TO REORDER</button>';
                                    } else {
                                        return '<button type="button" class="btn btn-xs btn-success" disabled>OK</button>';
                                    }
                                }
                            },
                            {
                                "targets": [6],
                                "data": null,
                                "className": 'text-center',
                                "render": function (data, type, row, meta) {
                                    var isi = "<button class='btn btn-xs btn-default' onclick=Details('" + row.INV_ID + "')>DTLS</button>  \n\
                                                    <button class='btn btn-xs btn-default' onclick=Reserve('" + row.INV_ID + "')>RSVP</button>  \n\
                                                    <button class='btn btn-xs btn-primary' onclick=History('" + row.INV_ID + "')>HIST</button>";
                                                    
                                    return isi;
                                }
                            }
                        ],
                "drawCallback": function (settings) {
                    $('.initStockClass').editable({
                        validate: function (value) {
                            if ($.trim(value) == '') {
                                return 'This field is required';
                            }
                        },
                        success: function (response, newValue) {
                            console.log(newValue);
                            var element = $(this);
                            var inv_id = element.data("pk");
                            console.log(inv_id);
                            $.ajax({
                                type: 'POST',
                                data: {inv_id: inv_id, value: newValue, type: "adjust_stock", "action": "update_data"},
                                url: "../_includes/inventory/process/process.php",
                                success: function (response, textStatus, jqXHR) {
                                    alert(response);
                                }
                            });
                        }
                    });
                    $('.initMinStockClass').editable({
                        validate: function (value) {
                            if ($.trim(value) == '') {
                                return 'This field is required';
                            }
                        },
                        success: function (response, newValue) {
                            console.log(newValue);
                            var element = $(this);
                            var inv_id = element.data("pk");
                            console.log(inv_id);
                            $.ajax({
                                type: 'POST',
                                data: {inv_id: inv_id, value: newValue, type: "adjust_min", "action": "update_data"},
                                url: "../_includes/inventory/process/process.php",
                                success: function (response, textStatus, jqXHR) {
                                    alert(response);
                                }
                            });
                        }
                    });
                },
                "fnCreatedRow": function (nRow, aData, iDataIndex) {
                    $(nRow).attr('id', 'row' + idNO);
                    idNO++;
                }
            });
        });
    }

    function History(param) {
        $('#myModal').modal('show');
        $.ajax({
            type: 'POST',
            url: "../_includes/inventory/process/process.php",
            dataType: "JSON",
            data: {inv_id: param, "action": "show_history"},
            beforeSend: function (xhr) {
                $('#modal-table').DataTable().destroy();
                $('#modal-table tbody').empty();
            },
            success: function (response, textStatus, jqXHR) {
                var content = "";
                $.each(response.value1, function (key, value) {

                    content += "<tr>" +
                            "<td class='text-center'>" + value.INPUT_DATE + "</td>" +
                            "<td class='text-center'>" + value.INV_DESC + "</td>" +
                            "<td class='text-center'>" + value.HIST_ADJUST + "</td>" +
                            "<td class='text-center'>" + (value.HIST_ADJUST + " updated by : " + value.INPUT_SIGN) + "</td>" +
                            "</tr>";
                });
                $('#inv-id').text(response.value2);
                $('#modal-table tbody').append(content);
            },
            complete: function () {
                initModalTableProp();
            }
        });
    }

    function Details(param) {

        $.ajax({
            type: 'POST',
            url: "../_includes/inventory/process/process.php",
            dataType: "JSON",
            data: {inv_id: param, "action": "show_qr"},
            success: function (response, textStatus, jqXHR) {
                $('#inv-detail-modal .modal-body').qrcode({
                    "render": "div",
                    "size": 100,
                    "text": response[0].INV_DESC
                });
                $('#inv-id-details').text(response[0].INV_DESC);
                $('#inv-detail-modal').modal('show');
            }
        });
    }

    function initModalTableProp() {
        $('#modal-table').DataTable({
            "bInfo": false,
            "bPaginate": false,
            "bFilter": false,
            initComplete: function () {
                this.api().columns().every(function () {
                    var column = this;
                    var select = $('<select><option value=""></option></select>')
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
                });
            }
        });
    }

    $(document).ready(function () {
        feedToTable();
    });

</script>