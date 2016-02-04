$(document).ready(function () {
    $('.selectpicker').selectpicker();
    $('#start-date, #end-date').datepicker();
    /*PROSES JAVASCRIPT DAN JQUERY*/
    $.ajax({
        type: 'POST',
        url: "../_includes/new_menu/history_checkout/model_hist_checkout.php",
        data: {"action": "loading_first"},
        dataType: 'JSON',
        beforeSend: function (xhr) {
            $('#job').selectpicker().html('');
            $('#spv').selectpicker().html('');
            $('#pembawa').selectpicker().html('');
        },
        success: function (response, textStatus, jqXHR) {
            var job = "<option value='%' selected=''>ALL JOB</value>";
            var spv = "<option value='%' selected=''>ALL SPV</value>";
            var pembawa = "<option value='%' selected=''>ALL PEMBAWA</value>";

            $.each(response.job, function (key, value) {
                job += "<option value=" + value.PROJECT_NO + ">" + value.PROJECT_NO + "</option>";
            });
            $('#job').selectpicker().append(job);

            $.each(response.spv, function (key, value) {
                spv += "<option value=" + value.MART_WR_SPV_SIGN + ">" + value.MART_WR_SPV_SIGN + "</option>";
            });
            $('#spv').selectpicker().append(spv);

            $.each(response.pembawa, function (key, value) {
                pembawa += "<option value=" + value.MART_WR_CARRIER + ">" + value.MART_WR_CARRIER + "</option>";
            });
            $('#pembawa').selectpicker().append(pembawa);

        },
        complete: function (jqXHR, textStatus) {
            $('#job').selectpicker().selectpicker('refresh');
            $('#spv').selectpicker().selectpicker('refresh');
            $('#pembawa').selectpicker().selectpicker('refresh');
        }
    });

    dt();
    ShowHistory();
});


function ChangeJob() {
    var job = document.getElementById('job').value;
    $.ajax({
        type: 'POST',
        url: "../_includes/new_menu/history_checkout/model_hist_checkout.php",
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

function ShowHistory() {
    var start = $('#start-date').val();
    var end = $('#end-date').val();
    var job = $('#job').val();
    var spv = $('#spv').val();
    var pembawa = $('#pembawa').val();

    var sentReq = {
        "action": "show_history",
        start: start,
        end: end,
        job: job,
        spv: spv,
        pembawa: pembawa
    };

    console.log(sentReq);

    $.ajax({
        type: 'POST',
        url: "../_includes/new_menu/history_checkout/model_hist_checkout.php",
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
                            {"data": "MART_WR_DATE", "className": "text-center"},
                            {"data": "MART_WR_JOB", "className": "text-center"},
                            {"data": "MART_WR_SPV_SIGN", "className": "text-center"},
                            {"data": "MART_WR_CARRIER", "className": "text-center"},
                            {"data": "INV_DESC", "className": "text-center"},
                            {"data": "MART_WR_INV_QTY", "className": "text-center"}
                        ],
                "columnDefs":
                        [
                            {
                                "orderable": true,
                                "visible": true,
                                "targets": [0],
                                "render": function (data, type, row, meta) {
                                    return nomer++;
                                }
                            },
                            {
                                "visible": false,
                                "targets": 2
                            },
                            {
                                "orderable": true,
                                "visible": true,
                                "targets": [6],
                                "render": function (data, type, row, meta) {
                                    return row.MART_WR_INV_QTY + " " + row.UNIT_LVL2;
                                }
                            }
                        ],
                "drawCallback": function (settings) {
                    var api = this.api();
                    var rows = api.rows({page: 'current'}).nodes();
                    var last = null;

                    api.column(2, {page: 'current'}).data().each(function (group, i) {
                        if (last !== group) {
                            $(rows).eq(i).before(
                                    '<tr class="group"><td colspan="6" style="background-color:silver; color:green; font-size:16px;">' + group + '</td></tr>'
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