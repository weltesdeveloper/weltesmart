$(document).ready(function () {
    $('.selectpicker').selectpicker();
    $('#start-date, #end-date').datepicker();
    /*PROSES JAVASCRIPT DAN JQUERY*/
    dt();
    ShowHistory();
    $.ajax({
        type: 'POST',
        url: "../_includes/new_menu/history_checkin/model_hist_checkin.php",
        data: {"action": "loading_first"},
        dataType: 'JSON',
        beforeSend: function (xhr) {
            $('#job').selectpicker().html('');
        },
        success: function (response, textStatus, jqXHR) {
            var job = "<option value='%' selected=''>ALL JOB</value>";

            $.each(response.job, function (key, value) {
                job += "<option value=" + value.PROJECT_NO + ">" + value.PROJECT_NO + "</option>";
            });
            $('#job').selectpicker().append(job);
        },
        complete: function (jqXHR, textStatus) {
            $('#job').selectpicker().selectpicker('refresh');
        }
    });
});

function ShowHistory() {
    var start = $('#start-date').val();
    var end = $('#end-date').val();
    var job = $('#job').val();

    var sentReq = {
        "action": "show_history",
        start: start,
        end: end,
        job: job
    };

    console.log(sentReq);

    $.ajax({
        type: 'POST',
        url: "../_includes/new_menu/history_checkin/model_hist_checkin.php",
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
                            {"data": null, "className": "text-center"},
                            {"data": "PROJECT_NO", "className": "text-center"},
                            {"data": "MART_CHECKIN_DATE", "className": "text-center"},
                            {"data": "INV_DESC", "className": "text-center"},
                            {"data": null, "className": "text-center"}
                        ],
                "columnDefs":
                        [
                            {"visible": false, "targets": 1},
                            {
                                "orderable": true,
                                "visible": true,
                                "targets": [0],
                                "render": function (data, type, row, meta) {
                                    return nomer++;
                                }
                            },
                            {
                                "orderable": true,
                                "visible": true,
                                "targets": [4],
                                "render": function (data, type, row, meta) {
                                    var stock_asli = row.UNIT_ASLI;
                                    var stock_konversi = "<sub>(" + row.UNIT_TERKECIL + ")</sub>";
                                    return stock_asli + " " + stock_konversi;
                                }
                            }
                        ],
                "drawCallback": function (settings) {
                    var api = this.api();
                    var rows = api.rows({page: 'current'}).nodes();
                    var last = null;

                    api.column(1, {page: 'current'}).data().each(function (group, i) {
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

function PrintHistory() {
    var start = encodeURIComponent($('#start-date').val());
    var end = encodeURIComponent($('#end-date').val());
    var job = encodeURIComponent($('#job').val());
    var spv = encodeURIComponent($('#spv').val());
    var pembawa = encodeURIComponent($('#pembawa').val());
    var parameter = "?start=" + start + "&end=" + "&job=" + job + "&spv=" + spv + "&pembawa=" + pembawa;
//    window.open("../_includes/reports/historychekout/print_historychekout.php"+ parameter);
    alert("DALAM PERBAIKAN");
}