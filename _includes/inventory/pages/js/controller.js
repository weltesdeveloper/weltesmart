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
                        {"data": "INV_ID"},
                        {"data": "INV_DESC"},
                        {"data": "INV_UNIT", className: "text-center"}
                    ],
            "columnDefs":
                    [
                        {
                            "orderable": true,
                            "visible": true,
                            "targets": [1],
                            "className": 'text-center',
                            "render": function (data, type, row, meta) {
                                var isi = '<a style="cursor:pointer" class="text-center" onclick="ViewDetailsQr()"> <i class="fa fa-envelope-o"></i> </a>';
                                return isi;
                            }
                        },
                        {
                            "orderable": true,
                            "visible": true,
                            "targets": [4],
                            "className": 'text-center',
                            "render": function (data, type, row, meta) {
                                var isi = '<a data-type="number" style="cursor:pointer" class="initStockClass text-center" data-pk="' + row.INV_ID + '">' + row.INV_STK_QTY + '</a>';
                                return isi;
                            }
                        },
                        {
                            "visible": true,
                            "targets": [5],
                            "className": 'text-center',
                            "render": function (data, type, row, meta) {
                                var isi = '<a data-type="number" style="cursor:pointer" class="initMinStockClass" data-pk="' + row.INV_ID + '">' + row.INV_STK_MIN + '</a>';
                                return isi;
                            }
                        },
                        {
                            "visible": true,
                            "targets": [6],
                            "className": 'text-center',
                            "render": function (data, type, row, meta) {
                                var isi = '<a data-type="number" style="cursor:pointer" class="initMaxStockClass" data-pk="' + row.INV_ID + '">' + row.INV_STK_MAX + '</a>';
                                return isi;
                            }
                        },
                        {
                            "visible": true,
                            "targets": [7],
                            "className": 'text-center',
                            "render": function (data, type, row, meta) {
                                var isi = '<a data-type="number" style="cursor:pointer" class="initSafeStockClass" data-pk="' + row.INV_ID + '">' + row.INV_STK_SAFE + '</a>';
                                return isi;
                            }
                        },
                        {
                            "visible": true,
                            "targets": [8],
                            "className": 'text-center',
                            "render": function (data, type, row, meta) {
                                if (row.REORDERSTATUS == 'NOT SET') {
                                    return '<button type="button" class="btn btn-xs btn-warning" value=' + row.REORDERSTATUS + ' disabled>' + row.REORDERSTATUS + '</button>';
                                } else if (row.REORDERSTATUS == 'NEED TO REORDER') {
                                    return '<button type="button" class="btn btn-xs btn-danger" value=' + row.REORDERSTATUS + ' onclick=ordermanagement("CREATE_PR")>' + row.REORDERSTATUS + '</button>';
                                } else {
                                    return '<button type="button" class="btn btn-xs btn-success" value=' + row.REORDERSTATUS + ' disabled>' + row.REORDERSTATUS + '</button>';
                                }
                            }
                        },
                        {
                            "targets": [9],
                            "data": null,
                            "className": 'text-center',
                            "render": function (data, type, row, meta) {
                                var isi = "<button class='btn btn-xs btn-default' onclick=Details('" + row.INV_ID + "')>DETAILS</button>  \n\
                                                    <button class='btn btn-xs btn-primary' onclick=History('" + row.INV_ID + "')>HISTORY</button>";

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
    var plus = 0;
    var minus = 0;
    var jumlahplus = 0;
    var jumlahminus = 0;
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
            content = "";
            plus = 0;
            minus = 0;
            jumlahplus = 0;
            jumlahminus = 0;
            $.each(response.value1, function (key, value) {
                if (value.HIST_ADJUST >= 0) {
                    plus = value.HIST_ADJUST;
                } else {
                    plus = "-";
                }
                if (value.HIST_ADJUST < 0) {
                    minus = value.HIST_ADJUST;
                } else {
                    minus = "-";
                }
                content += "<tr>" +
                        "<td class='text-center'>" + value.INPUT_DATE + "</td>" +
                        "<td class='text-center'>" + value.INV_DESC + "</td>" +
                        "<td class='text-center'>" + plus + "</td>" +
                        "<td class='text-center'>" + minus + "</td>" +
                        "<td class='text-center'>" + value.HIST_TYPE + "</td>" +
                        "<td class='text-center'>" + value.PROPERTIES + "</td>" +
                        "<td class='text-cente r'>" + value.INPUT_SIGN + "</td>" +
                        "</tr>";
                if (plus == '-') {
                    plus = 0;
                }
                if (minus == '-') {
                    minus = 0;
                }
                jumlahplus += parseInt(plus);
                jumlahminus += parseInt(minus);
            });
            $('#inv-id').text(response.value2);
            $('#modal-table tbody').append(content);
        },
        complete: function () {
//            initModalTableProp();
//            $('#modal-table').DataTable({});
            $('#in').text(jumlahplus);
            $('#out').text(jumlahminus);
            $('#jumlah').text(jumlahplus + jumlahminus);
            $('#modal-table').DataTable({
//                initComplete: function () {
//                    this.api().columns().every(function () {
//                        var column = this;
//                        var select = $('<select><option value=""></option></select>')
//                                .appendTo($(column.footer()).empty())
//                                .on('change', function () {
//                                    var val = $.fn.dataTable.util.escapeRegex(
//                                            $(this).val()
//                                            );
//
//                                    column
//                                            .search(val ? '^' + val + '$' : '', true, false)
//                                            .draw();
//                                });
//
//                        column.data().unique().sort().each(function (d, j) {
//                            select.append('<option value="' + d + '">' + d + '</option>')
//                        });
//                    });
//                }
            });
        }
    });
}

function Details(param) {
    $.ajax({
        type: "POST",
        url: "../_includes/inventory/pages/modal/modalDetails.php",
        data: {},
        dataType: "text",
        cache: false,
        success: function (data) {
            $('#modalDetails').html(data);
        },
        complete: function () {
            showQrCode(param);
        }
    });
}

function showQrCode(param) {
    $.ajax({
        type: 'POST',
        url: "../_includes/inventory/process/process.php",
        dataType: "JSON",
        data: {inv_id: param, "action": "show_qr"},
        success: function (response, textStatus, jqXHR) {
            $('#inv-qr-details').empty();
            $('#inv-qr-details').qrcode({
                "render": "div",
                "size": 100,
                "text": response[0].INV_DESC
            });
            $('#inv-id-details').text(response[0].INV_DESC);
            $('#inv-detail-modal').modal('show');
        }
    });
}

function ViewDetailsQr() {
    $.ajax({
        type: "POST",
        url: "../_includes/inventory/pages/modal/modalViewQrCode.php",
        data: {},
        dataType: "text",
        cache: false,
        success: function (data) {
            $('#modalDetailsQrCode').html(data);
        },
        complete: function () {
            $('#modalViewQrCOde').modal('show');
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

function PrintStock() {
    window.open("../_includes/inventory/process/print_stock_uptodate.php");
}

$(document).ready(function () {
    feedToTable();
});