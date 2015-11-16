<?php
    require_once('../../_config/dbinfo.inc.php');
    require_once('../../_config/misc.func.php');
    $conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB) or die;
    $todaysDate = date("m/d/y");
    $globalName = SingleQryFld("SELECT MS.SETTING_VALUE_STRING FROM MART_SETTINGS MS WHERE MS.SETTING_DESC = 'GLOBAL_NAME'", $conn);    
    $var1sql = "SELECT MS.SETTING_VALUE FROM MART_SETTINGS MS WHERE MS.SETTING_DESC = 'SESSION_TIMEOUT'";
    $var1 = SingleQryFld($var1sql, $conn);
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1><?=$globalName?> CUSTOMER RELATION MANAGEMENT<small> &nbsp; List all Weltes Customers</small></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Relationship</a></li>
        <li class="active">Customers</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            
            <div class="box box-default box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-user-plus"></i><b>&nbsp;&nbsp;CUSTOMER DATA LIST</b> </h3>
                    <div class="box-tools pull-right">
                        <!-- In box-tools add this button if you intend to use the contacts pane -->
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                    <table id="customer-list-table" class="table table-condensed table-responsive table-striped">
                        <thead>
                            <tr>
                                <th>JOB</th>
                                <th>CLIENT ID</th>
                                <th>CLIENT NAME</th>
                                <th>CLIENT INITIAL</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>JOB</th>
                                <th>CLIENT ID</th>
                                <th>CLIENT NAME</th>
                                <th>CLIENT INITIAL</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
</section>

<script>
    function listCustJson(handleData) {
        return $.ajax({
            type: "POST",
            dataType: 'json',
            url: "../_includes/relationship/process/process_customer.php",
            success: function (json) {
                handleData(json);
            }
        });
    }
    
    function feedToCusttable(){
        listCustJson(function (response) {
             var table = $('#customer-list-table').DataTable({
                processing: true,
                data: response,
                pageLength: 15,
                columns:
                    [
                        {"data": "PROJECT_NO"},
                        {"data": "CLIENT_ID"},
                        {"data": "CLIENT_NAME"},
                        {"data": "CLIENT_INIT"}
                    ]
             });
        });
    }
    
    $(document).ready(function () {
        feedToCusttable();
    });
</script>