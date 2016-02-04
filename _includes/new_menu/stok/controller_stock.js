$(document).ready(function () {
    $('.selectpicker').selectpicker();
    $('#start-date, #end-date').datepicker();
    /*PROSES JAVASCRIPT DAN JQUERY*/
    dt();
    ShowInv();
    $.ajax({
        type: 'POST',
        url: "../_includes/new_menu/stok/model_stock.php",
        data: {"action": "loading_first"},
        dataType: 'JSON',
        beforeSend: function (xhr) {
            $('#inventory').selectpicker().html('');
        },
        success: function (response, textStatus, jqXHR) {
            var inventory = "<option value='%' selected=''>ALL INVENTORY</value>";

            $.each(response.inv1, function (key, value) {
                inventory += "<option value=" + value.INV_ID + ">" + value.INV_DESC + "</option>";
            });
            $('#inventory').selectpicker().append(inventory);
        },
        complete: function (jqXHR, textStatus) {
            $('#inventory').selectpicker().selectpicker('refresh');
        }
    });
});

function ShowInv() {
    var inv_id = $('#inventory').val();
    var sentReq = {
        "action": "show_inventory",
        inv_id:inv_id
    };

    console.log(sentReq);

    $.ajax({
        type: 'POST',
        url: "../_includes/new_menu/stok/model_stock.php",
        dataType: 'JSON',
        data: sentReq,
        success: function (response, textStatus, jqXHR) {
            var nomer = 1;
            $('#history-checkout').DataTable({
                destroy: true,
                processing: true,
                data: response,
                pageLength: 25,
                "columns":
                        [
                            {"data": "INV_ID", "className": "text-center"},
                            {"data": "INV_DESC", "className": "text-center"},
                            {"data": "UNIT_LVL1", "className": "text-center"},
                            {"data": "UNIT_AMOUNT", "className": "text-center"},
                            {"data": "UNIT_LVL1", "className": "text-center"}
                        ],
                "columnDefs":
                        [
                            {"visible": false, "targets": 0}
                        ],
                "drawCallback": function (settings) {
                    var api = this.api();
                    var rows = api.rows({page: 'current'}).nodes();
                    var last = null;

                    api.column(0, {page: 'current'}).data().each(function (group, i) {
                        if (last !== group) {
                            $(rows).eq(i).before(
                                    '<tr class="group"><td colspan="5" style="background-color:silver; color:green; font-size:16px;">' + group + '</td></tr>'
                                    );

                            last = group;
                        }
                    });
                }
            });
        }
    });
}


function dt() {
    $('#history-checkout').dataTable();
}

function PrintInv() {
    var start = encodeURIComponent($('#start-date').val());
    var end = encodeURIComponent($('#end-date').val());
    var job = encodeURIComponent($('#job').val());
    var spv = encodeURIComponent($('#spv').val());
    var pembawa = encodeURIComponent($('#pembawa').val());
    var parameter = "?start=" + start + "&end=" + "&job=" + job + "&spv=" + spv + "&pembawa=" + pembawa;
//    window.open("../_includes/reports/historychekout/print_historychekout.php"+ parameter);
    alert("DALAM PERBAIKAN");
}