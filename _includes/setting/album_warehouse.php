<?php
require_once('../../_config/dbinfo.inc.php');
require_once('../../_config/misc.func.php');
$conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB) or die;
$todaysDate = date("m/d/y");
$globalName = SingleQryFld("SELECT WMS.SETTING_VALUE_STRING FROM MART_SETTINGS WMS WHERE WMS.SETTING_DESC = 'GLOBAL_NAME'", $conn);
$var1sql = "SELECT WMS.SETTING_VALUE FROM MART_SETTINGS WMS WHERE WMS.SETTING_DESC = 'SESSION_TIMEOUT'";
$var1 = SingleQryFld($var1sql, $conn);
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>Inventory Library</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-gear"></i> Settings</a></li>
        <li class="active">Master Inventory</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-xs-12">
            <div class="box box-default box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-gear"></i><b>&nbsp;&nbsp;INPUT PHOTO AND LOCATION FOR WAREHOUSE INVENTORY</b> </h3>
                    <div class="box-tools pull-right">
                        <!-- In box-tools add this button if you intend to use the contacts pane -->
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered table-striped" id="master-inv" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center">INV ID</th>
                                <th class="text-center">INV DESC</th>
                                <th class="text-center">INV TYPE</th>
                                <th class="text-center">PHOTO</th>
                                <th class="text-center">LOCATION</th>
                                <th class="text-center">UPDATE</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>
    </div>
</section>

<script>
    function loadDataFromTable(param) {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: "../_includes/setting/process_album/album_warehouse_process.php",
            data: {param: param},
            success: function (response, textStatus, jqXHR) {
//                alert(response);
                var table = $('#master-inv').DataTable({
//                    initComplete: columnSelectProp,
                    processing: true,
                    data: response,
                    pageLength: 18,
                    columns:
                            [
                                {"data": "INV_ID"},
                                {"data": "INV_ID"},
                                {"data": "INV_TYPE"}
                            ],
                    "columnDefs": [
                        {
                            "visible": false,
                            "targets": 0
                        },
                        {
                            "visible": true,
                            "targets": 1
                        },
                        {
                            "visible": false,
                            "targets": 2
                        },
                        {
                            "visible": true,
                            "targets": [3],
                            "className": "text-center",
                            "render": function (data, type, row, meta) {
                                var isi = "<input id='img" + row.INV_ID + "' type='file' class='file' data-preview-file-type='text'>";
                                return isi;
                            }
                        },
                        {
                            "visible": true,
                            "className": "text-center",
                            "targets": [4],
                            "render": function (data, type, row, meta) {
                                var isi = "<input type='text' class='form-control' style='width:100%;' onkeyup=UpperCase('" + row.INV_ID + "'); id='location" + row.INV_ID + "'>";
                                return isi;
                            }
                        },
                        {
                            "visible": true,
                            "className": "text-center",
                            "targets": [5],
                            "render": function (data, type, row, meta) {
                                var isi = "<button type='button' class='btn btn-success btn-xs' onclick=UpdateData('" + row.INV_ID + "');>INSERT</button>";
                                return isi;
                            }
                        }
                    ],
                    "drawCallback": function (settings) {
                        var api = this.api();
                        var rows = api.rows({page: 'current'}).nodes();
                        var last = null;

                        api.column(3, {page: 'current'}).data().each(function (group, i) {
                            if (last !== group) {
                                $(rows).eq(i).before(
                                        '<tr class="group"><td colspan="5" style="background-color:#D9BBA9;">' + group + '</td></tr>'
                                        );

                                last = group;
                            }
                        });
                    }
                });
            }
        });
    }
    $(document).ready(function () {
        loadDataFromTable("first_load");
//        $(".file").fileinput();
    });

    function UpdateData(param) {
        var location = $('#location' + param).val();
        var file = $('#img' + param)[0].files[0];
        var formData = new FormData();
        formData.append('file', file);
        formData.append("param", "update_data");
        formData.append("inv_id", param);
        formData.append("location", location);
        console.log(formData);
        $.ajax({
            type: 'POST',
            url: "../_includes/setting/process_album/album_warehouse_process.php",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function (response, textStatus, jqXHR) {
                if (response != "") {
                    alert(response);
                } else {
                    alert("SUKSES INSERT");
                }
            }
        });
    }

    function UpperCase(param) {
        var input = $('#location' + param).val();
        var upper = input.toUpperCase();
        $('#location' + param).val(upper);
    }
</script>