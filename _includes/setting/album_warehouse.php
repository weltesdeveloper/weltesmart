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
    <h1>Inventory Library</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-gear"></i> Settings</a></li>
        <li class="active">Master Inventory</li>
    </ol>
</section>

<section class="content" id="sec-album">
    <div class="row">
        <div class="col-md-12">
            <div class="input-group input-group-sm">
                <select class="selectpicker form-control" data-live-search='true' id='inv_type'>
                    <option value='%' selected>ALL</option>
                    <?php
                    $invCatParse = oci_parse($conn, "SELECT DISTINCT MSI.INV_TYPE FROM MART_STOCK_INFO MSI ORDER BY MSI.INV_TYPE ASC");
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
                        <option value='<?php echo $row['INV_TYPE']; ?>'>
                            <?php echo $row['INV_TYPE']; ?>
                        </option>
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
        <div class="col-lg-12 col-md-12 col-xs-12">
            <div class="box box-default box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-gear"></i><b>&nbsp;&nbsp;INPUT PHOTO AND LOCATION FOR WAREHOUSE INVENTORY</b> </h3>
                    <div class="box-tools pull-right">                        
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered table-striped" id="tbl_mst_inv" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center"><input type="checkbox" id="chk_all" />&nbsp;</th>
                                <th class="text-center">INV ID</th>
                                <th class="text-center">INV DESC</th>
                                <th class="text-center">INV TYPE</th>
                                <th class="text-center">PHOTO</th>
                                <th class="text-center">LOCATION</th>
                                <th class="text-center">UPDATE</th>
                                <th class="text-center">SHOW IMAGE</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="8"  style="text-align: left !important; background-color: graytext;">
                                    <button class="btn btn-xs btn-default" id="btn_print_album">
                                        <i class="fa fa-print text-warning fa-lg"></i>&nbsp;PRINT ALBUM SELECTED ITEM
                                    </button>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content modal-lg">                
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    var parent = $('#sec-album');
    var tbl_element = parent.find('#tbl_mst_inv');
    var tbl_data = tbl_element.DataTable();


    parent.find('select#inv_type').selectpicker();
    parent.find('#showInvButton').click(function () {
        var inv_type = parent.find('select#inv_type').val();
        loadDataFromTable(inv_type);
    });

    parent.find('#chk_all').on('change', function () {
        updateDataTableSelectAllCtrl($(this));
    });

    parent.find('#btn_print_album').click(function () {
        $.ajax({
            url: "../_includes/setting/process_album/album_warehouse_process.php",
            type: 'POST',
            data: {
                param: 'show_modal_print_album'
            },
            beforeSend: function (xhr) {
                parent.find('#myModal').find('.modal-content').empty();
            },
            success: function (resp, textStatus, jqXHR) {
                parent.find('#myModal').find('.modal-content').html(resp);
            },
            complete: function (jqXHR, textStatus) {
                parent.find('#myModal').modal('show');
            }
        });
    });

    // kumpulan Fungsi
    function loadDataFromTable(inv_type) {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: "../_includes/setting/process_album/album_warehouse_process.php",
            data: {
                param: 'show_data',
                inv_type: inv_type
            },
            beforeSend: function (xhr) {
                tbl_element.DataTable({
                    destroy: true,
                    language: {
                        "emptyTable": "Please Wait..."
                    }
                }).clear().draw();
                parent.find('#chk_all').prop('checked', false);
            },
            success: function (response, textStatus, jqXHR) {
                var i = 0;
                tbl_data = tbl_element.DataTable({
                    destroy: true,
                    processing: true,
                    language: {
                        "processing": "Please Wait...",
                        "emptyTable": "No data available in table"
                    },
                    data: response,
                    pageLength: 25,
                    columns:
                            [
                                {"data": null},
                                {"data": "INV_ID"},
                                {"data": "INV_DESC"},
                                {"data": "INV_TYPE"},
                                {"data": null},
                                {"data": null},
                                {"data": null},
                                {"data": null}
                            ],
                    "columnDefs": [
                        {
                            "visible": true,
                            "targets": 0,
                            "className": "text-center",
                            //"orderable": false,
                            "render": function (data, type, row, meta) {
                                var isi = "<input type='checkbox' value='" + row.INV_ID + "'>";
                                return isi;
                            }
                        },
                        {
                            "visible": true,
                            "targets": 1
                        },
                        {
                            "visible": true,
                            "targets": 2
                        },
                        {
                            "visible": false,
                            "targets": 3
                        },
                        {
                            "visible": true,
                            "targets": [4],
                            "className": "text-center",
                            "render": function (data, type, row, meta) {
                                var isi = "<input id='img" + row.INV_ID + "' type='file' class='file' data-preview-file-type='text'>";
                                return isi;
                            }
                        },
                        {
                            "visible": true,
                            "className": "text-center",
                            "targets": [5],
                            "render": function (data, type, row, meta) {
                                var isi = "<input type='text' class='form-control' style='width:100%;' onkeyup='this.value=" + 'this.value.toUpperCase();' + "' id='location" + row.INV_ID + "' value='" + row.INV_WH_LOC + "'>";
                                return isi;
                            }
                        },
                        {
                            "visible": true,
                            "className": "text-center",
                            "targets": [6],
                            "render": function (data, type, row, meta) {
                                var isi = "<button type='button' class='btn btn-success btn-xs' onclick=UpdateData('" + row.INV_ID + "');>INSERT</button>";
                                return isi;
                            }
                        },
                        {
                            "visible": true,
                            "className": "text-center",
                            "targets": [7],
                            "render": function (data, type, row, meta) {
                                var isi = "<i class='fa fa-times' style='color:red;'></i>";
                                if (row.JML_GMBAR > 0) {
                                    isi = "<i class='fa fa-check' style='cursor:pointer;' onclick=showUploadImage('" + row.INV_ID + "');></i>";
                                }
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
                                        '<tr class="group"><td colspan="7" style="background-color:#D9BBA9;">' + group + '</td></tr>'
                                        );

                                last = group;
                            }
                        });
                    },
                    "fnCreatedRow": function (nRow, aData, iDataIndex) {
                        //$(nRow).attr('id', 'baris_' + i);
                        i++;
                    }
                });
            }
        });
    }
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
                    $('#showInvButton').click();
                }
            }
        });
    }

    function printAlbum() {
        var judul = $('#txt_judul_album').val().trim();
        var inv_id = "";

        var baris = tbl_data.rows().nodes();
        $('input[type = "checkbox"]:checked', baris).each(function (i, rows) {
            var tr_row = $(this).closest('tr')[0];
            var td_row = $(tr_row).find('td:eq(0)');
            //console.log($(this));       
            inv_id += "'" + $(this).val() + "'*";
        });



        var url_vars = '?judul=' + encodeURIComponent(judul) + '&inv_id=' + encodeURIComponent(inv_id);
        var URL = '../_includes/setting/process_album/album_warehouse_PRINT.php' + url_vars;
        PopupCenter(URL, 'popupEQP_RENT', '1100', '700');

    }

    function showUploadImage(param) {
        console.log(param);
        $('#inventory-id').text(param);
        $("#myModal").modal('show');
    }

    function updateDataTableSelectAllCtrl(chkbox_select_all) {
        var cells = tbl_data.rows();
        if (chkbox_select_all.prop('checked') === true) {
            cells.every(function () {
                var tr = this.nodes();
                var data = this.data();
                //console.log(data);
                $(tr).find('input[type = "checkbox"]').prop('checked', true);
            });
        } else {
            cells.every(function () {
                var tr = this.nodes();
                var data = this.data();
                //console.log(data);
                $(tr).find('input[type = "checkbox"]').prop('checked', false);
            });
        }
    }

</script>