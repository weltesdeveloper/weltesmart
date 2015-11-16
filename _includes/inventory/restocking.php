<?php
require_once('../../_config/dbinfo.inc.php');
require_once('../../_config/misc.func.php');
$conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB) or die;
$todaysDate = date("m/d/y");
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>INVENTORY RESTOCKING<small> &nbsp; Adjust the return stock from site or project</small></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Inventory</a></li>
        <li class="active">Restocking</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-xs-12">
            
        </div>
    </div>
</section>