$(document).ready(function () {
    $('.selectpicker').selectpicker();
    $('#modal-date').datepicker();
    $('#modal-qty').autoNumeric('init');
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
                                "targets": [0]
                            },
                            {
//                                "orderable": true,
                                "visible": true,
                                "targets": [2],
                                "className": 'text-center',
                                "render": function (data, type, row, meta) {
                                    var isi = "<a style='cursor:pointer;' id='adjust" + row.INV_ID + "' onclick=Adjust('" + row.INV_ID + "')>" + row.QTY + "</a>";
                                    return isi;
                                }
                            },
                            {
//                                "orderable": true,
                                "visible": true,
                                "targets": [3],
                                "className": 'text-center',
                                "render": function (data, type, row, meta) {
                                    var isi = 0;
                                    return isi;
                                }
                            },
                            {
//                                "orderable": true,
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
    $('#modal-remark').val("");
    $.ajax({
        type: 'POST',
        data: {inv_id: param, action: "get_inv_stock"},
        url: "../_includes/new_menu/adjust/model_adjustment.php",
        dataType: 'JSON',
        success: function (response, textStatus, jqXHR) {
            $.each(response, function (key, value) {
                $('#modal-invid').val(value.INV_ID);
                $('#modal-invdesc').val(value.INV_DESC);
                $('#modal-qty').val(value.QTY);
                $('#modal-header-invid').text(value.INV_DESC);
            });
            $('#modal-adjust').modal('show');
        }
    });
}

function SubmitAdjust() {
    var inv_id = $('#modal-invid').val();
    var date = $('#modal-date').val();
    var qty = $('#modal-qty').autoNumeric('get');
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

    if (qty == "") {
        alert("QTY TIDAK BOLEH KOSONG");
    } else if (remark == "") {
        alert("REMARK TIDAK BOLEH KOSONG");
    } else {
        $.ajax({
            type: 'POST',
            data: sentReq,
            url: "../_includes/new_menu/adjust/model_adjustment.php",
            dataType: 'JSON',
            success: function (response, textStatus, jqXHR) {
                if (response.status == "BERHASIL INPUT") {
                    alert(response.status);
                    $('#modal-adjust').modal('hide');
                    $('#adjust' + inv_id).text(response.qty);
                } else {
                    alert(response.status);
                }
            }
        });
    }
}