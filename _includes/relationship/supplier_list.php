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
    <h1><?=$globalName?> SUPPLIER RELATION MANAGEMENT<small> &nbsp; List all Weltes Suppliers</small></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Relationship</a></li>
        <li class="active">Suppliers</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            
            <div class="box box-default box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-truck"></i><b>&nbsp;&nbsp;SUPPLIERS DATA LIST</b> </h3>
                    <div class="box-tools pull-right">
                        <!-- In box-tools add this button if you intend to use the contacts pane -->
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                    <table id="supplier-list-table" class="table table-condensed table-responsive table-striped">
                        <thead>
                            <tr>
                                <th style="width: 7%">SUPP ID</th>
                                <th>SUPPLIER DESC</th>
                                <th>ADDRESS</th>
                                <th>LOCATION</th>
                                <th style="width: 7%">CONTACT</th>
                                <th>CONTACT PH</th>
                                <th>WORK PH 1</th>
                                <th>WORK PH 2</th>
                                <th>WORK FAX</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>SUPP ID</th>
                                <th>SUPPLIER DESC</th>
                                <th>ADDRESS</th>
                                <th>LOCATION</th>
                                <th>CONTACT</th>
                                <th>CONTACT PH</th>
                                <th>WORK PH 1</th>
                                <th>WORK PH 2</th>
                                <th>WORK FAX</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
</section>

<script>
    function listSuppJson(handleData) {
        return $.ajax({
            type: "POST",
            dataType: 'json',
            url: "../_includes/relationship/process/process_supplier.php",
            success: function (json) {
                handleData(json);
            }
        });
    }
    
    function feedToSupptable(){
        listSuppJson(function (response) {
             var table = $('#supplier-list-table').DataTable({
                processing: true,
                data: response,
                pageLength: 15,
                columns:
                        [
                            {"data": "SUPP_ID"},
                            {"data": "SUPP_NM"},
                            {"data": "SUPP_ADDR"},
                            {"data": "SUPP_LOC"},
                            {"data": "SUPP_CONT_NM"},
                            {"data": "SUPP_CONT_PH"},
                            {"data": "SUPP_PH1"},
                            {"data": "SUPP_PH2"},
                            {"data": "SUPP_FAX"}
                        ]
             });
        });
    }
    
    $(document).ready(function () {
        feedToSupptable();
    });
</script>
