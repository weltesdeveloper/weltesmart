<?php
require_once('../../_config/dbinfo.inc.php');
require_once('../../_config/misc.func.php');
$conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB) or die;
$todaysDate = date("m/d/y");
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>INVENTORY ADJUSTMENT<small> &nbsp; Adjust stock quantity based on opname</small></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Inventory</a></li>
        <li class="active">Adjust</li>
    </ol>
</section>

<section class="content">
    <br/>
    <div class="row">
        <div class="col-md-12">Most used items &nbsp;
            <button class="btn btn-xs btn-default">BATU GRINDA</button>
            <button class="btn btn-xs btn-default">CUTTING TIP</button>
            <button class="btn btn-xs btn-default">GLOVES</button>
        </div>
    </div><br/>
    <div class="row">
        <div class="col-md-12">
            <div class="input-group input-group-sm">
                <select class="selectpicker form-control" data-live-search='true' id='inv-type-select'>
                    <option value='%' selected>ALL</option>
                    <?php
                    $invCatParse = oci_parse($conn, "SELECT DISTINCT MSI.INV_TYPE FROM MART_STOCK_INFO MSI ORDER BY MSI.INV_TYPE ASC");
                    $invExcErr = oci_execute($invCatParse);
                    if (!$invExcErr) {
                        $e = oci_error($invCatParse);
                        print htmlentities($e['message']);
                        print "\n<pre>\n";
                        print htmlentities($e['sqltext']);
                        printf("\n%" . ($e['offset'] + 1) . "s", "^");
                        print "\n</pre>\n";
                    }

                    while ($row = oci_fetch_array($invCatParse)) {
                        ?>
                        <option value='<?php echo $row['INV_TYPE']; ?>'>
                            <?php echo $row['INV_TYPE']; ?>
                        </option>
                        <?php
                    }
                    ?>
                </select>
                <span class="input-group-btn">
                    <button class="btn btn-primary" type="button" id='showInvButton'>Show Inventory</button>
                </span>
            </div><!-- /input-group -->
        </div>
    </div>

    <br/>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><b>MAIN INVENTORY</b> ~ <span id="specific-inv-info">ALL</span> <small>You can set stock adjustment or even make some adjustment to the minimum stock warning</small></h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-xs btn-default"><i class="fa fa-key"></i>&nbsp;TOGGLE ADJUSTMENT</button>
                        <button class="btn btn-xs btn-default"><i class="fa fa-battery-quarter"></i>&nbsp;SET MIN STOCK</button>
                        <!-- In box-tools add this button if you intend to use the contacts pane -->
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-toggle="tooltip" title="Contacts" data-widget="chat-pane-toggle"><i class="fa fa-comments"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                    <table id="inventory-adjust-table" class="table table-bordered table-responsive table-striped">
                        <thead>
                            <tr>
                                <th style="width: 7%">ID</th>
                                <th style="width: 7%">QR</th>
                                <th>DESCRIPTION</th>
                                <th class="text-center" style="width: 6%">UNIT</th> 
                                <th class="text-center" style="width: 10%">ON HAND</th>
                                <th class="text-center" style="width: 10%">MIN</th>
                                <th class="text-center" style="width: 10%">MAX</th>
                                <th class="text-center" style="width: 10%">SAFE</th>
                                <th class="text-center" style="width: 10%">STOCK STATUS</th>
                                <th class="text-center" style="width: 16%">DETAILS/ACTIONS</th>
                            </tr>
                        </thead>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>

        <!--MODAL BOOTSTRAP-->
        <div class="modal modal-default fade" id="myModal" role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title text-center">Adjustment History for 
                            <span id="inv-id"></span>
                        </h4>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped table-bordered" id="modal-table">
                            <thead>
                                <tr>
                                    <th class="text-center">DATE</th>
                                    <th class="text-center">INV DESC</th>
                                    <th class="text-center">IN</th>
                                    <th class="text-center">OUT</th>
                                    <th class="text-center">TYPE</th>
                                    <th class="text-center">PROPERTIES</th>
                                    <th class="text-center">SIGN</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th class="text-center" colspan="2">SUMMARY</th>
                                    <th class="text-center" id="in"></th>
                                    <th class="text-center" id="out"></th>
                                    <th class="text-center" id="jumlah" colspan="3" style="background-color: activecaption"></th>
                                </tr>
                            </tfoot>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
        <!-- END MODAL BOOTSTRAP -->    

        <!-- ITEM DETAILS MODAL -->
        <div id="modalDetails">tes</div>
        
        <!-- ITEM DETAILS MODAL QR CODE-->
        <div id="modalDetailsQrCode">tes</div>
      
        <!-- END ITEM DETAILS MODAL -->

    </div>


</section>

<script src="../_includes/inventory/pages/js/controller.js" type="text/javascript"></script>