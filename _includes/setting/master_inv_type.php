<?php
    require_once('../../_config/dbinfo.inc.php');
    require_once('../../_config/misc.func.php');
    $conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB) or die;
    $todaysDate = date("m/d/y");
    $globalName = SingleQryFld("SELECT WMS.SETTING_VALUE_STRING FROM MART_SETTINGS WMS WHERE WMS.SETTING_DESC = 'GLOBAL_NAME'", $conn);    
    $var1sql = "SELECT WMS.SETTING_VALUE FROM MART_SETTINGS WMS WHERE WMS.SETTING_DESC = 'SESSION_TIMEOUT'";
    $var1 = SingleQryFld($var1sql, $conn);
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1><?=$globalName?> MASTER INVENTORY SELECTION<small> &nbsp; Adjust master inventory for <?=$globalName?></small></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Settings</a></li>
        <li class="active">Master Inventory</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        
        <div class="col-lg-12 col-md-12 col-xs-12">
            
        </div>
        
    </div>
</section>