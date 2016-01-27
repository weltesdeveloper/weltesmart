<?php
require_once('../../../_config/dbinfo.inc.php');
require_once('../../../_config/misc.func.php');

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
                    <h3 class="box-title"><b>MAIN INVENTORY</b> ~</h3>
                    <b><u><i><a href="#" style="color: red; font-size: 17px;">ADJUSTMENT INVENTORY ADALAH UNIT YANG TERKECIL (Pcs, Roll, Kg ...)</a></i></u></b>
                    <div class="box-tools pull-right">
                        <!--<button class="btn btn-xs btn-default"><i class="fa fa-key"></i>&nbsp;TOGGLE ADJUSTMENT</button>-->
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <!--<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>-->
                    </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                    <table id="inventory-adjust-table" class="table table-bordered table-responsive table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">INV ID</th>
                                <th class="text-center">DESCRIPTION</th>
                                <th class="text-center">ON HAND</th>
                                <th class="text-center">MIN</th>
                                <th class="text-center">STOCK STATUS</th>
                                <th class="text-center">DETAILS/ACTIONS</th>
                            </tr>
                        </thead>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
        <br/>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <button class="btn btn-success col-md-12" onclick="PrintStock();">PRINT STOCK</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--MODAL STOCK ADJUSTMENT-->
    <!-- Modal -->
    <div id="modal-adjust" class="modal fade modal-success" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title text-center">FORM ADJUST INVENTORY <span id="modal-header-invid" class="text-black"></span></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <label class="control-label col-sm-3">ID</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="modal-invid" readonly="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">DESCRIPTION</label>
                            <div class="col-sm-9"> 
                                <input type="text" class="form-control" id="modal-invdesc" readonly="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">DATE</label>
                            <div class="col-sm-9"> 
                                <input type="text" class="form-control" id="modal-date" readonly="" value="<?php echo date("m/d/Y"); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">QTY</label>
                            <div class="col-sm-9"> 
                                <input type="text" class="form-control" id="modal-qty">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">UNIT</label>
                            <div class="col-sm-9"> 
                                <select class="selectpicker" id="modal-unit" data-live-search="true" data-width="100%">
                                    <!--<option value="Box">Box</option>-->
                                    <!--<option value="Dos">Dos</option>-->
                                    <!--<option value="Dz">Dz</option>-->
                                    <option value="Pcs">Pcs</option>
                                    <option value="Kg">Kg</option>
                                    <option value="Roll">Roll</option>
                                    <!--<option value="Set">Set</option>-->
                                    <!--<option value="Tbg">Tabung</option>-->
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">REMARK</label>
                            <div class="col-sm-9"> 
                                <textarea id="modal-remark" class="form-control" placeholder="NB : Remark Tidak Boleh Kosong"></textarea>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="SubmitAdjust();">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
</section>

<script src="../_includes/new_menu/adjust/controller_adjustment.js"></script>