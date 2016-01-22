<?php
    require_once('../../_config/dbinfo.inc.php');
    require_once('../../_config/misc.func.php');

    $todaysDate = date("m/d/y");
    $globalName = SingleQryFld("SELECT WMS.SETTING_VALUE_STRING FROM MART_SETTINGS WMS WHERE WMS.SETTING_DESC = 'GLOBAL_NAME'", $conn);    
    $var1sql = "SELECT WMS.SETTING_VALUE FROM MART_SETTINGS WMS WHERE WMS.SETTING_DESC = 'SESSION_TIMEOUT'";
    $var1 = SingleQryFld($var1sql, $conn);
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1><?=$globalName?> GLOBAL SETTINGS<small> &nbsp; Adjust various settings on your workflow</small></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Settings</a></li>
        <li class="active">Global</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            
            <div class="box box-default box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-globe"></i><b>&nbsp;&nbsp;GLOBAL SETTINGS</b> </h3>
                    <div class="box-tools pull-right">
                        <!-- In box-tools add this button if you intend to use the contacts pane -->
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="form-group">
                        <label>Global Store Name</label>
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-industry"></i></div>
                                <input type="text" class="form-control" value="<?=$globalName?>" id="global-name">
                            </div><!-- /.input group -->
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                        <label>Session Timeout Duration</label>
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
                                <input type="number" class="form-control" min="1" max="240" value="<?=$var1?>" id="timeout-duration">
                            </div><!-- /.input group -->
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                        <label>Forecasting Method</label>
                            <div class="input-group">
                                <select class="selectpicker">
                                    <option value="1">Specified Percent Over Last Weeks</option>
                                    <option value="2">Calculated Percent Over Last Weeks</option>
                                    <option value="3">Last Week to This Week</option>
                                    <option value="4">Moving Average</option>
                                    <option value="5">Weighted Moving Average</option>
                                    <option value="6">Linear Approximation</option>
                                    <option value="7">Least Square Regression</option>
                                    <option value="8">Second Degree Approximation</option>
                                    <option value="9">Linear Smoothing</option>
                                    <option value="10">Exponential Smoothing With Trend & Seasonality</option>
                                </select>
                            </div><!-- /.input group -->
                    </div><!-- /.form group -->
                </div>
            </div>
            
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <button class="btn btn-block btn-success">SUBMIT SETTINGS</button>
        </div>
    </div>
</section>

<script>
    $('.selectpicker').selectpicker();
</script>

    