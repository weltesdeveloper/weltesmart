<?php
require_once('../../_config/dbinfo.inc.php');
require_once('../../_config/misc.func.php');
$conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB) or die;
$todaysDate = date("m/d/y");
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>INVENTORY CHECKOUT<small> &nbsp; Consumable Checkout Based On Job & Subjob</small></h1>
    <ol class="breadcrumb">
        <li class="active"><button class="btn btn-xs btn-primary" id="instant-checkout">INSTANT CHECKOUT</button></li>
        <li class="active"><button class="btn btn-xs btn-warning" id="request-checkout">CHECKOUT BY CONSUMABLE REQUEST</button></li>
    </ol>
</section>

    <section class="content" id="checkout-page">
        <div class="row">
            <div class="col-md-4 col-xs-4 col-lg-4">
                <select class="selectpicker" data-width="100%"></select>
            </div>
            <div class="col-md-4 col-xs-4 col-lg-4">
                <select class="selectpicker" data-width="100%"></select>
            </div>
            <div class="col-md-4 col-xs-4 col-lg-4">
                <div class="form-group">   
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                      <input type="text" class="form-control pull-right" id="inventory-checkout-list">
                    </div><!-- /.input group -->
                </div><!-- /.form group -->
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-xs-12 col-lg-12">
                <div class="box box-primary direct-chat direct-chat-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><b>INSTANT CHECKOUT</b> ~ Auto Generated Warehouse Receipt #</h3> : 
                            <b><a href="#" id="wh-receipt" data-type="text" data-pk="1" data-url="/post" data-title="Enter New Warehouse #">WEN-WH-JOB-SUBJOB-000001</a></b>
                        <div class="box-tools pull-right">
                            <span data-toggle="tooltip" title="3 New Messages" class="badge bg-light-blue">3</span>
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button class="btn btn-box-tool" data-toggle="tooltip" title="Contacts" data-widget="chat-pane-toggle"><i class="fa fa-comments"></i></button>
                            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">

                    <div class="row">
                        <div class="col-md-12">
                            <table id="inv-checkout-table" class="table table-condensed" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 3%;" class="text-center">#</th>
                                        <th style="width: 50%;" >INVENTORY</th>
                                        <th>QUANTITY</th>
                                        <th>REMARKS</th>
                                        <th style="width: 6%;">DELETE</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                    </div><!-- /.box-body -->
                        <div class="box-footer">
                            <button class="btn btn-block btn-default" id="add-inv-checkout-item"><i class="fa fa-plus fa-lg"></i></button>
                        </div><!-- /.box-footer-->
                  </div><!--/.direct-chat -->
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-xs-12 col-lg-12">
                <button class="btn btn-block btn-success" id="submit-checkout">SUBMIT REQUEST</button>
            </div>
        </div>

    </section>

<a href="request_checkout.php"></a>
<script>
    $('#instant-checkout').on('click', function(){
       alert("instant"); 
    });
    
    $('#request-checkout').on('click', function(){
        $.ajax({
            url: "../_includes/checkout/request_checkout.php",
            data: {},
            beforeSend: function (xhr) {
                $('#checkout-page').html();
            },
            success: function (response, textStatus, jqXHR) {
                $('#checkout-page').html(response);
            }
        });
    });
    
    $('.selectpicker').selectpicker();
    $('#inventory-checkout-list').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true
    });
    $.fn.editable.defaults.mode = 'inline';
    
    var inv_checkout_table = $('#inv-checkout-table').DataTable(
    {
        "bFilter": false,
        "bInfo": false,
        "bLengthChange": false,
        "bPaginate": false
    });
    
    // ADDING ROW
    var counter = 1;
    $('#add-inv-checkout-item').on('click', function () {
        inv_checkout_table.row.add(
                [
                    counter,
                    "<select class='selectpicker' data-id='item-name' data-live-search='true' data-width='100%' title='Select Inventory...' id='inventory-detail-drop" + counter + "'></select>",
                    "<input type='number' class='form-control' style='width: -moz-available;'/>",
                    "<input type='text' class='form-control' style='width: -moz-available;'/>",
                    "&nbsp;<i class='fa fa-trash fa-fw fa-lg text-danger' style='cursor: pointer; width: 2.286em; vertical-align: -70%;'  ></i>"
                ]).draw(false);
        $('.selectpicker').selectpicker();

        // FILLING INVENTORY DROPDOWN SELECTPICKER
        $('#inventory-detail-drop' + counter).each(function (index, value)
        {
            var initSelectpicker = $(this).selectpicker();
            $.getJSON('../_includes/purchaserequisition/process/inventory_list.php', function (data)
            {
                initSelectpicker.html('');
                $.each(data, function (key, val)
                {
                    initSelectpicker.append('<option value=' + val["INV_ID"] + '>' + val["INV_DESC"] + '</option>');
                });
                initSelectpicker.selectpicker('refresh');
            });
        });

        counter++;
    });
    
    // INITIALIZATION
    $(document).ready(function () {
        $('#wh-receipt').editable();
    });
</script>