<section class="content-header">
    <h1>LIST KONVERSI INVENTORY<small><i class="text-danger">Konversi Inventory Gudang</i></small></h1>
    <ol class="breadcrumb">
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><b>PILIH PERIODE TANGGAL DAN JOB</b></h3><small class="text-danger">&nbsp;&nbsp;&nbsp;Tanggal yang akan di search adalah tanggal penerimaan barang sesuai dengan tanggal peneriamaan di logistic</small> 
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12 col-xs-12 col-lg-12">
                            <div class="form-group">
                                <label class="control-label">INVENTORY</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <select class="selectpicker" data-width="100%" id="inventory" onchange="ChangeJob();" data-live-search="true"></select>
                                </div><!-- /.input group -->
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12 col-lg-12">
                            <button class="btn btn-block btn-danger" id="submit-checkout" onclick="ShowInv();">SHOW DATA</button>
                        </div>
                    </div>
                </div><!-- /.box-body -->

            </div><!-- /.box -->
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <table id="history-checkout" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center">INV ID</th>
                                <th class="text-center">NAMA BARANG</th>
                                <th class="text-center">UNIT 1</th>
                                <th class="text-center">=</th>
                                <th class="text-center">UNIT 2</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th class="text-center">INV ID</th>
                                <th class="text-center">NAMA BARANG</th>
                                <th class="text-center">UNIT 1</th>
                                <th class="text-center">=</th>
                                <th class="text-center">UNIT 2</th>
                            </tr>
                        </tfoot>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <button class="btn btn-block btn-warning" id="submit-checkout" onclick="PrintInv();">PRINT INVENTORY KONVERSI</button>
                </div>
            </div>
        </div>

    </div>

</section>

<script src="../_includes/new_menu/konversi/controller_konversi.js" type="text/javascript"></script>