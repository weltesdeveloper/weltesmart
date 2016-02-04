/* global conter */
var counter = 1;
function listMasterInvJson(handleData) {
    return $.ajax({
        type: "POST",
        dataType: 'json',
        data: {"action": "SHOW"},
        url: "../_includes/new_menu/setting_inv/model_setting_inv.php",
        success: function (json) {
            handleData(json);
        }
    });
}
function feedToMasterInvtable() {
    listMasterInvJson(function (response) {
        var table = $('#master-inv-selection').DataTable({
            initComplete: columnSelectProp,
            processing: true,
            data: response,
            pageLength: 18,
            columns:
                    [
                        {"data": "INV_CAT"},
                        {"data": "INV_TYPE"},
                        {"data": "INV_ID"},
                        {"data": "INV_DESC"},
                        {"data": "INV_BRAND"},
                        {"data": "INV_UNIT"},
                        {"data": "INV_GRD"},
                        {"data": "INV_GRD"}
                    ],
            columnDefs:
                    [
                        {
                            visible: true,
                            targets: 7,
                            className: 'text-center',
                            render: function (data, type, row, meta) {
                                var checked = "";
                                if (row.INV_WM_SELECT == '1') {
                                    checked = 'checked=""';
                                }
                                var content = '<div class="form-group">\n\
                                                        <label>\n\
                                                            <input type="checkbox" ' + checked + ' class="flat-red" id="register-check' + row.INV_ID + '" onclick=registerelement("' + row.INV_ID + '");>\n\
                                                        </label>\n\
                                                   </div>';
                                return content;
                            }
                        }
                    ],
            drawCallback: function (settings) {

            }
        });
    });
}
function registerelement(param) {
    if ($('#register-check' + param).is(':checked')) {
        $('#table-konversi').DataTable().destroy();
        $('#table-konversi tbody').empty();
        $('#modal-inv-name').text(" " + param);
        $('#modal-setting').modal('show');
        var inv_id = param;
        $('#inv-id').val(param);
        $('#register-check' + inv_id).prop('checked', false);
    } else {
        var cf = confirm("APA ANDA INGIN REMOVE INVENTORY DARI LIST INVENTORY GUDANG?");
        if (cf == true) {
            var inv_id = param;
            var check_id = 'REMOVE';
            $.ajax({
                type: 'POST',
                data: {inv_id: inv_id, action: check_id},
                url: "../_includes/new_menu/setting_inv/model_setting_inv.php",
                success: function () {
                    $('#register-check' + param).prop('unchecked', true);
                }
            });
        } else {
            $('#register-check' + param).prop('checked', true);
        }
    }
}
function columnSelectProp() {
    var arr = [0, 1, 4, 6];
    this.api().columns(arr).every(function () {
        var column = this;
        var select = $('<select class="selectpicker" data-width="100%" data-live-search="true" data-style="btn-primary" title="Filter.."><option value=""></option></select>')
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
        $('.selectpicker').selectpicker();
    });
}
function RubahUnitTerkecil() {
    $('#table-konversi').DataTable().destroy();
    $('#table-konversi tbody').empty();
    /**/
    var unit_min = $('#unit-terkecil').val();
    var table = $('#table-konversi').dataTable();
    var newTargetRow = table.fnAddData([
        "<select data-width='100%' class='selectpicker' data-live-search='true' onchange=CheckUnitLvl1('" + counter + "') id='unitlvl1'" + counter + " class=''form-control><option value='Dos'>Dos</option><option value='Box'>Box</option><option value='Dz'>Dz</option></select>",
        "<input type='number' id='qtylvl2'" + counter + " class='form-control' style='width:100px;' value='1'>",
        unit_min,
        "<button type='button' class='btn btn-danger' onclick=RemoveItem('" + counter + "')><i class='fa fa-minus'></i></button>"
    ]);
    var oSettings = table.fnSettings();
    var nTr = oSettings.aoData[ newTargetRow[0] ].nTr;
    var rowtarget = 'row' + counter;
    var qtylvl1 = 'qtylvl1_' + counter;
    var unitlvl1 = 'unitlvl1_' + counter;
    var qtylvl2 = 'qtylvl2_' + counter;
    var unitlvl2 = 'unitlvl2_' + counter;
    nTr.setAttribute('id', rowtarget);
    nTr.setAttribute('id', rowtarget);

    //ID
    $('td', nTr)[0].setAttribute('id', qtylvl1);
    $('td', nTr)[1].setAttribute('id', unitlvl1);
    $('td', nTr)[2].setAttribute('id', qtylvl2);
    $('td', nTr)[3].setAttribute('id', unitlvl2);

    //CLASS
    $('td', nTr)[0].setAttribute('class', "text-center");
    $('td', nTr)[1].setAttribute('class', "text-center");
    $('td', nTr)[2].setAttribute('class', "text-center");
    $('td', nTr)[3].setAttribute('class', "text-center");

    $('#add-items').prop("disabled", false);
    $('.selectpicker').selectpicker();
    counter++;
}
function AddUnit() {
    var unit_min = $('#unit-terkecil').val();
    var table = $('#table-konversi').dataTable();
    var newTargetRow = table.fnAddData([
        "<select data-width='100%' class='selectpicker' data-live-search='true' onchange=CheckUnitLvl1('" + counter + "') id='unitlvl1'" + counter + " class=''form-control><option value='Dos'>Dos</option><option value='Box'>Box</option><option value='Dz'>Dz</option></select>",
        "<input type='number' id='qtylvl2'" + counter + " class='form-control' style='width:100px;' value='1'>",
        unit_min,
        "<button type='button' class='btn btn-danger' onclick=RemoveItem('" + counter + "')><i class='fa fa-minus'></i></button>"
    ]);
    var oSettings = table.fnSettings();
    var nTr = oSettings.aoData[ newTargetRow[0] ].nTr;
    var rowtarget = 'row' + counter;
    var qtylvl1 = 'qtylvl1_' + counter;
    var unitlvl1 = 'unitlvl1_' + counter;
    var qtylvl2 = 'qtylvl2_' + counter;
    var unitlvl2 = 'unitlvl2_' + counter;
    nTr.setAttribute('id', rowtarget);
    nTr.setAttribute('id', rowtarget);

    //ID
    $('td', nTr)[0].setAttribute('id', qtylvl1);
    $('td', nTr)[1].setAttribute('id', unitlvl1);
    $('td', nTr)[2].setAttribute('id', qtylvl2);
    $('td', nTr)[3].setAttribute('id', unitlvl2);

    //CLASS
    $('td', nTr)[0].setAttribute('class', "text-center");
    $('td', nTr)[1].setAttribute('class', "text-center");
    $('td', nTr)[2].setAttribute('class', "text-center");
    $('td', nTr)[3].setAttribute('class', "text-center");

    $('#add-items').prop("disabled", false);
    $('.selectpicker').selectpicker();
    counter++;
}
function CheckUnitLvl1(param) {
    var unit = $('#CheckUnitLvl1').val();
    var unitSelect = "";
    var rows = $('#table-konversi').dataTable().fnGetNodes();
    for (var x = 0; x < rows.length; x++)
    {
        if (x != param)
            unitSelect += $(rows[x]).find("td:eq(0)").find("select").val();
    }
    if (unitSelect.indexOf(unit) != -1) {
        alert("PILIH UNIT LAIN");
        $('#submit-konversi').prop("disabled", true);
    } else {
        $('#submit-konversi').prop("disabled", false);
    }

}
function RemoveItem(param) {
    var table = $('#table-konversi').DataTable();
    table.row('#row' + param).remove().draw(false);
}
function SubmitKonversi() {
    var unit_lvl1 = [];
    var qty_lvl1 = [];
    var unit_lvl2 = $('#unit-terkecil').val();
    var inv_id = $("#inv-id").val();

    var rows = $('#table-konversi').dataTable().fnGetNodes();
    for (var x = 0; x < rows.length; x++)
    {
        unit_lvl1.push($(rows[x]).find("td:eq(0)").find("select").val());
    }
    var sorted_arr = unit_lvl1.sort();
    var duplikat = 0;
    var string_duplikat = "";
    for (var i = 0; i < sorted_arr.length - 1; i++) {
        if (sorted_arr[i] == sorted_arr[i + 1]) {
            duplikat = 1;
            string_duplikat = sorted_arr[i];
            break;
        }
    }

    if (duplikat == 1) {
        alert("UNIT 1 TIDAK BOLEH DUPLIKAT!!!");
        $('#register-check' + inv_id).prop('checked', false);
    } else {
        var cf = confirm("APA ANDA YAKIN DENGAN KONVERSI SATUAN DI BAWAH INI?");
        if (cf == true) {
            unit_lvl1 = [];
            qty_lvl1 = [];
            for (var x = 0; x < rows.length; x++)
            {
                unit_lvl1.push($(rows[x]).find("td:eq(0)").find("select").val());
                qty_lvl1.push($(rows[x]).find("td:eq(1)").find("input").val());
            }
            var sentReq = {
                unit_lvl1: unit_lvl1,
                qty_lvl1: qty_lvl1,
                unit_lvl2: unit_lvl2,
                inv_id: inv_id,
                action: "KONVERSI"
            };
            console.log(sentReq);
            $.ajax({
                type: 'POST',
                url: "../_includes/new_menu/setting_inv/model_setting_inv.php",
                data: sentReq,
//                dataType: 'JSON',
                success: function (response, textStatus, jqXHR) {
                    if (response.indexOf("GAGAL") > 0) {
                        alert("GAGAL MENAMBAHKAN UNIT BARU!!");
                        $('#register-check' + inv_id).prop('checked', false);
                    } else {
                        alert("BERHASIL MENAMBAHKAN UNIT BARU");
                        $('#modal-setting').modal('hide');
                        $('#register-check' + inv_id).prop('checked', true);
                    }
                }
            });
        } else {
            return false;
        }
    }

}
$(document).ready(function () {
    feedToMasterInvtable();
    $('#add-items').prop("disabled", true);
    
});