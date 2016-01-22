<section class="content-header">
    <h1>INVENTORY CHECKOUT <i class="text-danger">by barcode</i><small> &nbsp; Consumable Checkout Based On Job & Subjob</small></h1>
    <ol class="breadcrumb">
        <li class="active"><button class="btn btn-xs btn-primary" id="instant-checkout">INSTANT CHECKOUT</button></li>
        <li class="active"><button class="btn btn-xs btn-warning" id="request-checkout">CHECKOUT BY CONSUMABLE REQUEST</button></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
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
                        <div class="col-md-4 col-xs-4 col-lg-4">
                            <div class="form-group">   
                                <label class="control-label">TANGGAL</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" id="tanggal" value="<?php echo date("m/d/Y"); ?>" readonly="">
                                </div><!-- /.input group -->
                            </div><!-- /.form group -->
                        </div>
                        <div class="col-md-4 col-xs-4 col-lg-4">
                            <label class="control-label">JOB</label>
                            <select class="selectpicker" data-width="100%" id="job" onchange="ChangeJob();" data-live-search="true"></select>
                        </div>
                        <div class="col-md-4 col-xs-4 col-lg-4">
                            <label class="control-label">SUBJOB</label>
                            <select class="selectpicker" data-width="100%" id="subjob" data-live-search="true"></select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-xs-3 col-lg-3">
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
                        <div class="col-md-3 col-xs-3 col-lg-3">
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
                        <div class="col-md-3 col-xs-3 col-lg-3">
                            <div class="form-group">   
                                <label class="control-label">REMARK</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user-plus"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" id="remark" placeholder="Remark jika diperlukan">
                                </div><!-- /.input group -->
                            </div><!-- /.form group -->
                        </div>
                        <div class="col-md-3 col-xs-3 col-lg-3">
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
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><b>Check Out Item</b></h3> : 
                    <!--<b><a href="#" id="wh-receipt" data-type="text" data-pk="1" data-url="/post" data-title="Enter New Warehouse #">WEN-WH-JOB-SUBJOB-000001</a></b>-->
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table id="inv-checkout-table" class="table table-condensed" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 50%;" >INVENTORY</th>
                                        <th class="text-center" style="width: 10%;">MAX STOCK QTY</th>
                                        <th class="text-center">CHECKOUT QTY</th>
                                        <th class="text-center">CHECKOUT UNIT</th>
                                        <th class="text-center">REMARKS</th>
                                        <th class="text-center" style="width: 6%;">DELETE</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="box-footer">
                            <button class="btn btn-block btn-default" id="add-inv-checkout-item" onclick="AddItem();"><i class="fa fa-plus fa-lg"></i></button>
                        </div><!-- /.box-footer-->
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

</section>

<script src="../_includes/new_menu/checkout/controller_checkout.js" type="text/javascript"></script>