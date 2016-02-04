<section class="content-header">
    <h1>HISTORY CHECKIN INVENTORY<small><i class="text-danger">Mencatat Check In Barang Per periode</i></small></h1>
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
                        <div class="col-md-6 col-xs-6 col-lg-6">
                            <div class="form-group">   
                                <label class="control-label">START DATE</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" id="start-date" value="<?php echo date("m/d/Y"); ?>" readonly="">
                                </div><!-- /.input group -->
                            </div><!-- /.form group -->
                        </div>
                        <div class="col-md-6 col-xs-6 col-lg-6">
                            <div class="form-group">   
                                <label class="control-label">END DATE</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" id="end-date" value="<?php echo date("m/d/Y"); ?>" readonly="">
                                </div><!-- /.input group -->
                            </div><!-- /.form group -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12 col-lg-12">
                            <div class="form-group">
                                <label class="control-label">JOB</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <select class="selectpicker" data-width="100%" id="job" onchange="ChangeJob();" data-live-search="true"></select>
                                </div><!-- /.input group -->
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12 col-lg-12">
                            <button class="btn btn-block btn-danger" id="submit-checkout" onclick="ShowHistory();">SHOW DATA</button>
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
                                <th class="text-center">No</th>
                                <th class="text-center">Job</th>
                                <th class="text-center">Tanggal Check In</th>
                                <th class="text-center">Nama Barang</th>
                                <th class="text-center">Qty Barang</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Job</th>
                                <th class="text-center">Tanggal Check In</th>
                                <th class="text-center">Nama Barang</th>
                                <th class="text-center">Qty Barang</th>
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
                    <button class="btn btn-block btn-warning" id="submit-checkout" onclick="PrintHistory();">PRINT HISTORY CHECK IN</button>
                </div>
            </div>
        </div>

    </div>

</section>

<script src="../_includes/new_menu/history_checkin/controller_hist_checkin.js" type="text/javascript"></script>