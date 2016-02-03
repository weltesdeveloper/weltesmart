<section class="content-header">
    <h1>MASTER INVENTORY SELECTION<small> &nbsp; Adjust master inventory</small></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Settings</a></li>
        <li class="active">Master Inventory</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-xs-12">

            <div class="box box-default box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-truck"></i><b>&nbsp;&nbsp;SELECT MASTER INVENTORY LIST</b> </h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-condensed table-responsive" id="master-inv-selection" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>CATEGORY</th>
                                <th>TYPE</th>
                                <th>ID</th>
                                <th>INVENTORY NAME</th>
                                <th>BRAND</th>
                                <th>UNIT</th>
                                <th>GRADE</th>
                                <th>REGISTER</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>CATEGORY</th>
                                <th>TYPE</th>
                                <th></th>
                                <th></th>
                                <th>BRAND</th>
                                <th></th>
                                <th>GRADE</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>
    <div id="modal-setting" class="modal fade modal-default" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title text-center">FORM SETTING UNIT FOR INVENTORY<span id="modal-inv-name"></span></h4>
                    <h5 class="modal-title text-center text-danger">*ANDA WAJIB MENGISI FORM UNIT KONVERSI MULTI SATUAN JIKA INGIN MENAMBAHKAN BARANG KE GUDANG*</h5>
                    <h5 class="modal-title text-center"><small class="modal-title text-center text-bold text-fuchsia">*TOLONG LIST SEMUA KEMUNGKINAN SATUAN YANG DI MR*</small></h5>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="control-label col-sm-2">UNIT TERKECIL</label>
                            <label class="control-label col-sm-1">:</label>
                            <div class="col-sm-9"> 
                                <select class="selectpicker" id="unit-terkecil" data-live-search="true" data-width="100%" onchange="RubahUnitTerkecil();">
                                    <option value="" disabled="" selected="">PILIH UNIT TERKECIL</option>
                                    <option value="Pcs">Pcs</option>
                                    <option value="Kg">Kg</option>
                                    <option value="Roll">Roll</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2"></label>
                            <label class="control-label col-sm-1"></label>
                            <div class="col-sm-9"> 
                                <button type="button" class="btn btn-warning col-sm-12" onclick="AddUnit();" id="add-items">ADD UNIT<fa fa-plus></fa></button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">KONVERSI</label>
                            <label class="control-label col-sm-1">:</label>
                            <div class="col-sm-9"> 
                                <table class="table table-striped table-bordered" id="table-konversi">
                                    <thead>
                                        <tr>
                                            <th class="text-center">UNIT 1</th>
                                            <th class="text-center">QTY</th>
                                            <th class="text-center">UNIT 2</th>
                                            <th class="text-center">REMOVE</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <input type="hidden" id="inv-id">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="SubmitKonversi();" id="submit-konversi">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
</section>
<script src="../_includes/new_menu/setting_inv/controller_setting_inv.js" type="text/javascript"></script>