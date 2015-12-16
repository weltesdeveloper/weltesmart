/*INISIALISASI VARIABEL*/
$('.selectpicker').selectpicker();
$('#tanggal').datepicker();
var cons_checkout_table = $('#inv-checkout-table').dataTable({
    "bFilter": false,
    "bInfo": false,
    "bLengthChange": false,
    "bPaginate": false
});
var counter = 0;
var pembawa = [];
var spv = [];
/*PROSES JAVASCRIPT DAN JQUERY*/
$.ajax({
    type: 'POST',
    url: "../_includes/checkout/process/process_checkout.php",
    data: {"action": "getjob"},
    dataType: 'JSON',
    beforeSend: function (xhr) {
        $('#job').selectpicker().html('');
    },
    success: function (response, textStatus, jqXHR) {
        var option = "<option value='' disabled selected=''>[Select Job]</value>";
        $.each(response.job, function (key, value) {
            option += "<option value=" + value.PROJECT_NO + ">" + value.PROJECT_NO + "</option>";
        });
        $('#job').selectpicker().append(option);
        
        $.each(response.spv, function (key, value){
            spv.push(value.MART_WR_SPV_SIGN);
        });
        
        $.each(response.pembawa, function (key, value){
            pembawa.push(value.MART_WR_CARRIER);
        });
    },
    complete: function (jqXHR, textStatus) {
        $('#job').selectpicker().selectpicker('refresh');
        $("#spv").autocomplete({
            source: spv
        });
        $("#pembawa").autocomplete({
            source: pembawa
        });
    }
});

function ChangeJob() {
    var job = document.getElementById('job').value;
    $.ajax({
        type: 'POST',
        url: "../_includes/checkout/process/process_checkout.php",
        data: {"action": "getsubjob", "job": job},
        dataType: 'JSON',
        beforeSend: function (xhr) {
            $('#subjob').selectpicker().html('');
        },
        success: function (response, textStatus, jqXHR) {
            var option = "<option value='' disabled selected=''>[Select Subjob]</value>";
            $.each(response.subjob, function (key, value) {
                option += "<option value=" + value.PROJECT_NAME_NEW + ">" + value.PROJECT_NAME_NEW + "</option>";
            });
            $('#subjob').selectpicker().append(option);
            $('#wh-receipt').html(response.wh_id);
        },
        complete: function (jqXHR, textStatus) {
            $('#subjob').selectpicker().selectpicker('refresh');
        }
    });
}

function AddItem() {
    var newTargetRow = cons_checkout_table.fnAddData([
        "<select class='selectpicker' data-live-search='true' data-width='100%' title='Select Inventory...' id='inventory-detail-drop" + counter + "' onchange=ChangeInventory('" + counter + "')></select>",
        "<span id='max" + counter + "'></span>",
        "<input type='number' class='form-control' style='width: -moz-available;' value='1' min='0' id='qty" + counter + "' onchange=ChangeQty('" + counter + "')>",
        "<input type='text' class='form-control' style='width: -moz-available;'/></div>",
        "<i class='fa fa-trash fa-fw fa-lg text-danger' style='cursor: pointer;' onclick=DeleteItem(" + counter + ")></i>"
    ]);

    var oSettings = cons_checkout_table.fnSettings();
    var nTr = oSettings.aoData[ newTargetRow[0] ].nTr;

    var row = 'rowtarget' + counter;
    nTr.setAttribute('id', row);

    $('td', nTr)[0].setAttribute('class', 'text-center');
    $('td', nTr)[1].setAttribute('class', 'text-center');
    $('td', nTr)[2].setAttribute('class', 'text-center');
    $('td', nTr)[3].setAttribute('class', 'text-center');
    $('td', nTr)[4].setAttribute('class', 'text-center');
    $('.selectpicker').selectpicker();

    $('#inventory-detail-drop' + counter).each(function (index, value)
    {
        var initSelectpicker = $(this).selectpicker();
        $.ajax({
            type: 'POST',
            url: "../_includes/checkout/process/process_checkout.php",
            data: {"action": "get_inventory"},
            dataType: 'JSON',
            beforeSend: function (xhr) {
                initSelectpicker.html('');
            },
            success: function (response, textStatus, jqXHR) {
                $.each(response, function (key, val)
                {
                    initSelectpicker.append('<option value=' + val["INV_ID"] + '>' + val["INV_DESC"] + '</option>');
                });
            },
            complete: function (jqXHR, textStatus) {
                initSelectpicker.selectpicker('refresh');
            }
        });
    });
    counter++;
}

function DeleteItem(param) {
    var table_targetrem = $('#inv-checkout-table').DataTable();
    table_targetrem.row('#rowtarget' + param).remove().draw(false);
}

function SubmitBonGudang() {
    var wh_id = $('#wh-receipt').text().trim();
    var tanggal = $('#tanggal').val();
    var job = $('#job').val();
    var subjob = $('#subjob').val();
    var pembawa = $('#pembawa').val();
    var spv = $('#spv').val();
    var manager = $('#manager').val();
    var rem = $('#remark').val();
    var inv_id = [];
    var qty = [];
    var remark = [];
    var rows = $('#inv-checkout-table').dataTable().fnGetNodes();
    for (var x = 0; x < rows.length; x++) {
        if ($(rows[x]).find("td:eq(0)").find("select").val() != "") {
            inv_id.push($(rows[x]).find("td:eq(0)").find("select").val());
            qty.push($(rows[x]).find("td:eq(2)").find("input").val());
            remark.push($(rows[x]).find("td:eq(3)").find("input").val());
        }
    }

    var sentReq = {
        wh_id: wh_id,
        tanggal: tanggal,
        job: job,
        subjob: subjob,
        pembawa: pembawa,
        spv: spv,
        manager: manager,
        inv_id: inv_id,
        qty: qty,
        remark: remark,
        rem: rem,
        action: "submit_data"
    };

    console.log(sentReq);
    if (job == "") {
        swal("ENTER JOB FIRST", "ERROR", "error");
        $('#job').focus();
    } else if (pembawa == "") {
        swal("ENTER CARRIER", "ERROR", "error");
        $('#pembawa').focus();
    } else if (spv == "") {
        swal("ENTER SPV", "ERROR", "error");
        $('#spv').focus();
    } else if (inv_id.length == 0) {
        swal("ENTER INVENTORY MINIMUN 1 ITEM", "ERROR", "error");
//        $('#tanggal').focus();
    } else {
        var cf = confirm("DO YOU WANT SUBMIT?");
        if (cf == true) {
            $.ajax({
                type: 'POST',
                data: sentReq,
                url: "../_includes/checkout/process/process_checkout.php",
                success: function (response, textStatus, jqXHR) {
                    if (response.indexOf("GAGAL") > 0) {
                        swal("GAGAL INSERT", response, "error");
                    } else {
                        swal("SUCCESS INSERT", "GOOD JOB", "success");
                        checkout('CHECKOUT');
                    }
                }
            });
        } else {
            return false;
        }
    }
}

function ChangeInventory(param) {
    var inv_id = $('#inventory-detail-drop' + param).val();
    console.log(inv_id);
    $.ajax({
        type: 'POST',
        url: "../_includes/checkout/process/process_checkout.php",
        data: {action: "check_max", inv_id: inv_id},
        dataType: "JSON",
        success: function (response, textStatus, jqXHR) {
            if (response[0].INV_STK_QTY == 0) {
                $('#qty' + param).val("0");

            } else {
                $('#qty' + param).removeAttr("max").attr("max", response[0].INV_STK_QTY);
            }
            $('#max' + param).text(response[0].INV_STK_QTY);
        }
    });
}

function ChangeQty(param) {
    var qty = $('#qty' + param);
    var value = parseInt(qty.val());
    var max = parseInt(qty.attr("max"));
    if (value > max || isNaN(value) || value < 0)
        $('#qty' + param).val("0");

}