var elmnt_utama = $('#sect_checkout');
var job = elmnt_utama.find('#selectJob');

var tbl_element = elmnt_utama.find('#tble-checkout');
var table_init = tbl_element.DataTable({
    paging: false,
    filter: false
});
var counter = 0;
var already_inv_id = [];
already_inv_id.push('-');

$(document).ready(function () {
    showJob();
    show_autocomplete();
    job.selectpicker();
    job.on('change', function () {
        //var selected = $(this).find("option:selected").val();
        elmnt_utama.find('#spv').focus();
    });
    setTimeout(function (){
        elmnt_utama.find('button[data-id=selectJob]').click();
    },1000);
    elmnt_utama.find('#spv').on('keypress', function (e) {
        var code = e.keyCode || e.which;
        if (code == 13) { //Enter keycode
            elmnt_utama.find('#pembawa').focus();
        }
    });
    elmnt_utama.find('#pembawa').on('keypress', function (e) {
        var code = e.keyCode || e.which;
        if (code == 13) { //Enter keycode
            elmnt_utama.find('#txt_scanid').focus();
        }
    });

    elmnt_utama.find('#tgl_ambil').datepicker({
        todayBtn: true,
        autoclose: true,
        todayHighlight: true
    }).on('changeDate', function () {
        elmnt_utama.find('button[data-id=selectJob]').click();
    });

    

    elmnt_utama.find('#txt_scanid').on('keypress', function (e) {
        var code = e.keyCode || e.which;
        if (code == 13) { //Enter keycode
            if ($(this).val().trim() != '') {
                addToTable($(this).val().trim());
            } else {
                elmnt_utama.find('#submit-checkout').click();
            }
            //Do something            
        }
    });
});

// kumpulan fungsi
function showJob() {
    $.ajax({
        url: "checkout_barcode/model_checkout.php",
        type: 'POST',
        dataType: 'json',
        data: {
            action: 'show_job'
        },
        beforeSend: function (xhr) {
            job.empty();
        },
        success: function (respon, textStatus, jqXHR) {
            var optgrop = "<option selected disabled></option>";
            $.each(respon, function (i, row) {
                optgrop += '<option value="' + row.PROJECT_NO + '">' + row.PROJECT_NO + '</option>';
            });
            job.append(optgrop);
        },
        complete: function () {
            job.selectpicker('refresh');
        }
    });
}
function show_autocomplete() {
    var spv = [];
    var pembawa = [];

    $.ajax({
        type: 'POST',
        url: "checkout_barcode/model_checkout.php",
        data: {"action": "autocomplete"},
        dataType: 'JSON',
        success: function (response, textStatus, jqXHR) {

            $.each(response.spv, function (key, value) {
                spv.push(value.MART_WR_SPV_SIGN);
            });

            $.each(response.pembawa, function (key, value) {
                pembawa.push(value.MART_WR_CARRIER);
            });
        },
        complete: function (jqXHR, textStatus) {
            elmnt_utama.find("#spv")
                    .autocomplete({
                        source: spv
                    })
                    .on('focus keyup', function (e) {
                        if (e.type == 'focus') {
                            $(this).autocomplete("search", this.value);
                        }
                        $(this).val(this.value.toUpperCase());
                    });

            elmnt_utama.find("#pembawa")
                    .autocomplete({
                        source: pembawa
                    })
                    .on('focus keyup', function (e) {
                        if (e.type == 'focus') {
                            $(this).autocomplete("search", this.value);
                        }
                        $(this).val(this.value.toUpperCase());
                    });
        }
    });
}
function show_modal_inv(inv_id, stk_qty, stk_unit, callback) {
    $.ajax({
        url: "checkout_barcode/model_checkout.php",
        type: 'POST',
        async: false,
        data: {
            action: 'show_modal_inv_dtl',
            inv_id: inv_id,
            stk_qty: stk_qty,
            stk_unit: stk_unit,
            counter: counter
        },
        beforeSend: function (xhr) {
            elmnt_utama.find('#myModal .modal-dialog')
                    .css('width', '500px');
            elmnt_utama.find('#myModal .modal-content')
                    .empty();
        },
        success: function (respon, textStatus, jqXHR) {
            elmnt_utama.find('#myModal .modal-content').html(respon);
        },
        complete: callback
    });
}


function addToTable(inv_id) {
    var sentData = {
        action: "show_inv_dtl",
        inv_id: inv_id,
        already_inv_id: already_inv_id
    };
    //console.log(sentData);
    $.ajax({
        type: 'POST',
        url: "checkout_barcode/model_checkout.php",
        data: sentData,
        dataType: 'JSON',
        success: function (response, textStatus, jqXHR) {
            //console.log(response);
            if (response[0] != false) {
                show_modal_inv(inv_id, response[0].INV_STK_QTY, response[0].UNIT, function (resp) {
                    elmnt_utama.find('#myModal').modal('show');
                    elmnt_utama.find('#myModal').find('#modal_txt_qty').autoNumeric('init', {
                        vMin: 0,
                        vMax: response[0].INV_STK_QTY
                    });

                    elmnt_utama.find('#myModal').find('#modal_txt_qty').on('keypress', function (e) {
                        var code = e.keyCode || e.which;
                        if (code == 32) { //Enter keycode
                            elmnt_utama.find('#myModal').find('#btn_submit_qty').click();
                        }
                    });

                    setTimeout(function () {
                        elmnt_utama.find('#myModal').find('#modal_txt_qty').focus().select();
                    }, 500);
                });

                var new_row = table_init.row.add([
                    response[0].INV_DESC + '<input type="hidden" id="inv_id" value="' + response[0].INV_ID + '" />',
                    "<span>" + response[0].INV_STK_QTY + "</span>",
                    "<input type='text' class='form-control' value='1' min='0' id='qty" + counter + "' />",
                    "<span>" + response[0].UNIT + "</span>",
                    "<i class='fa fa-trash fa-fw fa-lg text-danger' style='cursor: pointer;' onclick=DeleteItem(" + counter + ")></i>"
                ]).draw(false);
            }
        },
        complete: function (resp, jqXHR, textStatus) {
            var row = resp.responseJSON[0];
            //console.log(row);
            if (row != false) {
                $('#qty' + counter).autoNumeric('init', {
                    vMin: 0,
                    vMax: row.INV_STK_QTY
                });

                already_inv_id.push(row.INV_ID);
                //console.log(already_inv_id);          
                counter++;
            }
            elmnt_utama.find('#txt_scanid').val('');
        }
    });
}
function updateQty(param) {
    var txt = elmnt_utama.find('#myModal').find('#modal_txt_qty').val();
    if (txt == '0' || txt == '') {
        alert('QTY HARUS DI ISI.');
        elmnt_utama.find('#myModal').find('#modal_txt_qty').focus();
    } else {
        $('#qty' + param).val(txt);
        elmnt_utama.find('#txt_scanid').focus().val('');
        elmnt_utama.find('#myModal').modal('hide');
    }
}
function DeleteItem(param) {
    var tr = $('#qty' + param).closest('tr')[0];
    table_init.row(tr).remove().draw(false);

    already_inv_id = [];
    already_inv_id.push('-');
    var baris = table_init.rows().nodes();
    for (var x = 0; x < baris.length; x++) {
        if ($(baris[x]).find("td:eq(0)").find("input").val() != "") {
            already_inv_id.push($(baris[x]).find("td:eq(0)").find("input").val());
        }
    }
    elmnt_utama.find('#txt_scanid').focus().val('');
}
function SubmitBonGudang() {
    var tanggal = $('#tgl_ambil').val();
    var job = $('#selectJob').val();
    var subjob = '-';
    var pembawa = $('#pembawa').val();
    var spv = $('#spv').val();
    var manager = $('#manager').val();
    var rem = $('#remark').val();
    var inv_id = [];
    var qty = [];
    var unit = [];

    var baris = table_init.rows().nodes();
    for (var x = 0; x < baris.length; x++) {
        if ($(baris[x]).find("td:eq(0)").find("input").val() != "") {
            inv_id.push($(baris[x]).find("td:eq(0)").find("input").val());
            qty.push($(baris[x]).find("td:eq(2)").find("input").autoNumeric('get'));
            unit.push($(baris[x]).find("td:eq(3)").find("span").text().trim());
        }
    }

    var sentReq = {
        tanggal: tanggal,
        job: job,
        subjob: subjob,
        pembawa: pembawa,
        spv: spv,
        manager: manager,
        rem: rem,
        inv_id: inv_id,
        qty: qty,
        unit: unit,
        action: "submit_data"
    };

    console.log(sentReq);
    if (job == null) {
        alert("ENTER JOB FIRST");
        elmnt_utama.find('#selectJob').focus();
    } else if (spv == "") {
        alert("ENTER SPV FIRST");
        elmnt_utama.find('#spv').focus();
    } else if (pembawa == "") {
        alert("ENTER CARRIER FIRST");
        elmnt_utama.find('#pembawa').focus();
    } else if (inv_id.length == 0) {
        alert("ENTER INVENTORY MINIMUN 1 ITEM");
        elmnt_utama.find('#txt_scanid').focus();
    } else {
        var cf = confirm("DO YOU WANT SUBMIT?");
        if (cf == true) {
            $.ajax({
                type: 'POST',
                data: sentReq,
                url: "checkout_barcode/model_checkout.php",
                success: function (response, textStatus, jqXHR) {
                    if (response.indexOf("GAGAL") > 0) {
                        alert("GAGAL INSERT" + response);
                    } else {
                        alert("SUCCESS INSERT");
                        checkout('CHECKOUT_BARCODE');
                    }
                }
            });
        } else {
            return false;
        }
    }
}
