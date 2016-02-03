<?php
require_once('../../_config/dbinfo.inc.php');
?>
<section class="content-header">
    <h1>INVENTORY CHECKOUT <i class="text-danger">by barcode</i><small> &nbsp; Consumable Checkout Based On Job & Subjob</small></h1>
    <ol class="breadcrumb">
        <!--<li class="active"><button class="btn btn-xs btn-primary" id="instant-checkout">INSTANT CHECKOUT</button></li>-->
        <!--<li class="active"><button class="btn btn-xs btn-warning" id="request-checkout">CHECKOUT BY CONSUMABLE REQUEST</button></li>-->
    </ol>
</section>
<section class="content" id="sect_checkout">
    <div class="row">
        <div class="col-md-5">
            <div class="box box-solid box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><b>INSTANT CHECKOUT</b> ~ Auto Generated Warehouse Receipt #</h3> : 
                    <b><span id="wh-receipt"></span></b>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">   
                                <label class="control-label">TANGGAL AMBIL</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" id="tgl_ambil" value="<?php echo date("m/d/Y"); ?>" readonly="">
                                </div><!-- /.input group -->
                            </div><!-- /.form group -->
                        </div>
                        <div class="col-md-6">
                            <label class="control-label">JOB</label>
                            <select class="selectpicker" data-width="100%" id="selectJob" data-live-search="true"></select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">   
                                <label class="control-label">SPV</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" id="spv" placeholder="Tolong Diisi Sesuai SPV">
                                </div><!-- /.input group -->
                            </div><!-- /.form group -->
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">   
                                <label class="control-label">MANAGER</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user-plus"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" id="manager" value="EDIYANTO">
                                </div><!-- /.input group -->
                            </div><!-- /.form group -->
                        </div>                        
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">   
                                <label class="control-label">PEMBAWA</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" id="pembawa" placeholder="Tolong Disi Sesuai Pembawa Bon">
                                </div><!-- /.input group -->
                            </div><!-- /.form group -->
                        </div>                        
                    </div>                       

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">   
                                <label class="control-label">KETERANGAN</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user-plus"></i>
                                    </div>
                                    <textarea class="form-control" id="remark" placeholder="..isi keterangan jika diperlukan"></textarea>
                                </div><!-- /.input group -->
                            </div><!-- /.form group -->
                        </div>
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>

        <div class="col-md-7">
            <div class="box box-solid box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><b>Check Out Item</b></h3> : 
                    <!--<b><a href="#" id="wh-receipt" data-type="text" data-pk="1" data-url="/post" data-title="Enter New Warehouse #">WEN-WH-JOB-SUBJOB-000001</a></b>-->
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="form-group">
                        <input class="form-control" type="text" id="txt_scanid" placeholder="SAAT SCAN, PASTIKAN CURSOR ADA DI SINI." />
                    </div>
                    <div class="form-group">
                        <table id="tble-checkout" class="table table-condensed table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center" style="" >INVENTORY NAME</th>
                                    <th class="text-center" style="width: 100px;">STOCK ON HAND</th>
                                    <th class="text-center" style="width: 75px">QTY</th>
                                    <th class="text-center" style="width: 75px">UNIT</th>
                                    <th class="text-center" style="width: 20px;">ACT</th>
                                </tr>
                            </thead>
                        </table>
                    </div>                        
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-xs-12 col-lg-12">
            <button class="btn btn-block btn-success" id="submit-checkout" onclick="SubmitBonGudang();">SUBMIT</button>
        </div>
    </div>

    <!--MODAL BOOTSTRAP-->
    <div class="modal modal-default fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">                
            </div>
        </div>
    </div>

</section>

<script src="../_includes/checkout_barcode/control_checkout.js" type="text/javascript"></script>