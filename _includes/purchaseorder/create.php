<?php
require_once('../../_config/dbinfo.inc.php');
require_once('../../_config/misc.func.php');
$conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB) or die;
$todaysDate = date("m/d/y");
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>CREATE PURCHASE ORDER<small> &nbsp; Intended for ordering stock from Weltes Suppliers</small></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Purchase Order</a></li>
        <li class="active">Create</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">

            <div class="box box-success box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-cubes"></i><b>&nbsp;&nbsp;ENTER DETAILS BELOW</b> </h3>
                    <div class="box-tools pull-right">
                        <!-- In box-tools add this button if you intend to use the contacts pane -->
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">

                    <div class="row">
                        <div class="col-md-1 col-lg-1 col-xs-1">
                            <select class="selectpicker" id="type-dropdown-po" data-width="100%" title='Type'>
                                <option value="VAT">VAT</option>
                                <option value="NONVAT">NON-VAT</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-lg-2 col-xs-2">
                            <select class="selectpicker" id="pr-dropdown-po" data-width="100%" title='Select PR..'></select>
                        </div>
                        <div class="col-md-5 col-lg-5 col-xs-5">
                            <select class="selectpicker" id="supplier-dropdown-po" data-live-search="true" data-width="60%" title='Select Supplier..'></select>
                            <span>&nbsp;&nbsp;&nbsp;Or<a id="new-supplier" style="cursor: pointer;">&nbsp;&nbsp;create a new supplier entry</a></span>
                        </div>
                        <div class="col-md-4 col-lg-4 col-xs-4">
                            <div class="pull-right">
                                <h4>Auto Invoice # : 
                                    <span style="font-weight: bold;">
                                        <a href="#" id="po-number" data-type="text" data-pk="1" data-url="/post" data-title="Enter New PO#">WEN-MR-00987</a></span>  
                                </h4>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>



</section>

<div class="modal fade" id="new-supplier-modal1" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div id="modal-isi"></div>
        </div>
        <!-- End Of Modal Content -->
    </div>
</div>
<script>
    $('.selectpicker').selectpicker();

    $('#new-supplier').on('click', function () {
        $.ajax({
            type: 'POST',
            url: '../_includes/purchaseorder/process/modal_newsupplier.php',
            success: function (response)
            {
                $('#modal-isi').html(response);
                $('#new-supplier-modal1').modal('show');
            }
        });
    });

    $.fn.editable.defaults.mode = 'inline';
    $(document).ready(function () {
        $('#po-number').editable();
        
        $('#supplier-dropdown-po').each(function(index, value)
        {
            var initSelectpicker = $(this).selectpicker();
            var count = 1;

            $.getJSON('../_includes/relationship/process/process_supplier.php', function(data)
            {

                initSelectpicker.html('');
                $.each(data, function(key, val)
                {
                    initSelectpicker.append('<option value="' + val["SUPP_ID"] + '">' + val["SUPP_NM"] + '</option>');
                    count++;
                });
                initSelectpicker.selectpicker('refresh');
            });
        });
    });
</script>