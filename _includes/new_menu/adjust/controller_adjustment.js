$(document).ready(function () {
    $('.selectpicker').selectpicker();
    $('#modal-date').datepicker();
    LoadData();
});

function LoadData() {
    var inv_type = $('#inv-type-select').val();
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: "../_includes/new_menu/adjust/model_adjustment.php",
        data: {"action": "load_data", inv_type: inv_type},
        success: function (response, textStatus, jqXHR) {
            var table = $('#inventory-adjust-table').DataTable({
                destroy: true,
                processing: true,
                data: response,
                pageLength: 15,
                "columns":
                        [
                            {"data": "INV_ID", "className": "text-center"},
                            {"data": "INV_DESC", "className": "text-center"},
                            {"data": null},
                            {"data": null},
                            {"data": null},
                            {"data": null}
                        ],
                "columnDefs":
                        [
                            {
                                "orderable": true,
                                "visible": true,
                                "targets": [2],
                                "className": 'text-center',
                                "render": function (data, type, row, meta) {
                                    var isi = "<a style='cursor:pointer;' id='adjust" + row.INV_ID + "' onclick=Adjust('" + row.INV_ID + "')>"+row.QTY+"</a>";
                                    return isi;
                                }
                            },
                            {
                                "orderable": true,
                                "visible": true,
                                "targets": [3],
                                "className": 'text-center',
                                "render": function (data, type, row, meta) {
                                    var isi = 0;
                                    return isi;
                                }
                            },
                            {
                                "orderable": true,
                                "visible": true,
                                "targets": [4],
                                "className": 'text-center',
                                "render": function (data, type, row, meta) {
                                    var isi = "on progress";
                                    return isi;
                                }
                            },
                            {
                                "targets": [5],
                                "data": null,
                                "className": 'text-center',
                                "render": function (data, type, row, meta) {
                                    var isi = "<button class='btn btn-xs btn-default' onclick=Details('" + row.INV_ID + "')>DETAILS</button>  \n\
                                                    <button class='btn btn-xs btn-primary' onclick=History('" + row.INV_ID + "')>HISTORY</button>";

                                    return isi;
                                }
                            }
                        ]
            });
        }
    });
}

function Adjust(param) {
    console.log(param);
//    $.ajax({
//        type: 'POST',
//        data: {inv_id: param, action: "get_inv_stock"},
//        url: "../_includes/new_menu/adjust/model_adjustment.php",
//        dataType: 'JSON',
//        success: function (response, textStatus, jqXHR) {
//            $('#modal-header-invid').text(param);
//            $('#modal-invid').val(param);
//            $.each(response, function (key, value) {
//                $('#modal-invdesc').val(value.INV_DESC);
//            });
//            $('#modal-adjust').modal('show');
//        }
//    });
    $('#modal-adjust').modal('show');
}

function resetForm() {
    $('#modal-invid').val("");
    $('#modal-date').val("");
    $('#modal-qty').val("");
    $('#modal-unit').val("");
    $('#modal-remark').val("");
}

function SubmitAdjust() {
    var inv_id = $('#modal-invid').val();
    var date = $('#modal-date').val();
    var qty = $('#modal-qty').val();
    var unit = $('#modal-unit').val();
    var remark = $('#modal-remark').val();

    var sentReq = {
        inv_id: inv_id,
        date: date,
        qty: qty,
        unit: unit,
        remark: remark,
        action: "stock_adjust"
    };
    console.log(sentReq);

    $.ajax({
        type: 'POST',
        data: sentReq,
        url: "../_includes/new_menu/adjust/model_adjustment.php",
        dataType: 'JSON',
        success: function (response, textStatus, jqXHR) {
            if (response == "BERHASIL INPUT") {
                alert(response);
                $('#myModal').modal('hide');
            } else {
                alert(response);
            }
        }
    });
}