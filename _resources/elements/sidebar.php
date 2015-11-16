<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
        <div class="pull-left image">
          <img src="../_templates/img/mart_icon160x160-01-01.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?=$username?></p>
          <a href="#"><i class="fa fa-sitemap text-success"></i> <?php echo $companyRole; ?></a>
        </div>
    </div>
    
    <!-- search form -->
        <div class="input-group">
<!--            <input type="text" name="q" class="form-control" placeholder="Lookup Data..">
            <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
            </span>-->
            <select class="selectpicker" data-style="btn-primary" data-width="300px" title="Quick Report..">
                <option>Report 1</option>
                <option>Report 2</option>
                <option>Report 3</option>
            </select>
        </div>
    
    <!-- /.search form -->
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu">
        <li class="header"><?=$globalName?> MAIN MENU</li>
        <li><a href="#"><i class="fa fa-th-large"></i> <span>Dashboard Monitoring</span></a></li>
        
        <li class="">
            <a href="#">
              <i class="fa fa-briefcase"></i> <span> Inventory Control</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a onclick="inventory('ADJUST')" style="cursor: pointer;"><i class="fa fa-terminal"></i> Inventory Adjustment</a></li>
                <li><a onclick="inventory('LIST')" style="cursor: pointer;"><i class="fa fa-navicon"></i> Inventory List</a></li>
                <li><a onclick="inventory('RESERVE_LIST')" style="cursor: pointer;"><i class="fa fa-hand-grab-o"></i> Reservation List</a></li>
                <li><a onclick="inventory('RESTOCKING')" style="cursor: pointer;"><i class="fa fa-download"></i> Restocking</a></li>
            </ul>
        </li>
        
        <li class="">
            <a href="#">
              <i class="fa fa-phone"></i> <span> Order Management</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a onclick="ordermanagement('CREATE_PR')" style="cursor: pointer;"><i class="fa fa-phone-square"></i> Create Purchase Requisition</a></li>
                <li><a onclick="ordermanagement('LIST_PR')" style="cursor: pointer;"><i class="fa fa-server"></i> List Purchase Requisition</a></li>
                <li><a onclick="ordermanagement('CREATE_PO')" style="cursor: pointer;"><i class="fa fa-ship"></i> Create Purchase Order</a></li>
                <li><a onclick="ordermanagement('LIST_PO')" style="cursor: pointer;"><i class="fa fa-server"></i> List Purchase Order</a></li>
                <li><a onclick="ordermanagement('CREATE_SO')" style="cursor: pointer;"><i class="fa fa-cart-arrow-down"></i> Create Sales Order</a></li>
                <li><a onclick="ordermanagement('LIST_SO')" style="cursor: pointer;"><i class="fa fa-server"></i> List Sales Order</a></li>
            </ul>
        </li>
        
        
        <li class="">
            <a href="#">
              <i class="fa fa-print"></i> <span> Reports</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a onclick="report('STOCK_ON_HAND')" style="cursor: pointer;"><i class="fa fa-truck"></i> Stock On Hand</a></li>
                <li><a href="#"><i class="fa fa-bars"></i> Inventory Details</a></li>
                <li><a href="#"><i class="fa fa-code-fork"></i> Tracker</a></li>
                <li><a href="#"><i class="fa fa-hourglass-2"></i> Historical Summary</a></li>
                <li><a href="#"><i class="fa fa-clock-o"></i> Sales Over-Time</a></li>
            </ul>
        </li>
        
        <li class="">
            <a href="#">
              <i class="fa fa-television"></i> <span> Intelligence</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a href="#"><i class="fa fa-desktop"></i> Monitoring</a></li>
                <li><a href="#"><i class="fa fa-bar-chart"></i> Forecasting</a></li>
            </ul>
        </li>
        
        <li class="">
            <a href="#">
              <i class="fa fa-user"></i> <span> Relationship</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a onclick="relationship('SUPPLIER_LIST')" style="cursor: pointer;"><i class="fa fa-ship"></i> Suppliers</a></li>
                <li><a onclick="relationship('CUSTOMER_LIST')" style="cursor: pointer;"><i class="fa fa-slideshare"></i> Customers</a></li>
            </ul>
        </li>
        
        <li class="">
            <a href="#">
              <i class="fa fa-gear"></i> <span> My <?=$globalName?></span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a href="#"><i class="fa fa-warning"></i> Minimum Stock Adjust</a></li>
                <li><a onclick="setting('GLOBAL')" style="cursor: pointer;"><i class="fa fa-globe"></i> Global Setting</a></li>
            </ul>
        </li>
        
        <li><a onclick="checkout('CHECKOUT')" style="cursor: pointer;"><i class="fa fa-sign-out text-success"></i> <span>Consumable Checkout</span></a></li>

        <li class="header">INVENTORY INFORMATION</li>
        <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>Important</span></a></li>
        <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> <span>Warning</span></a></li>
        <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>Information</span></a></li>


	
    </ul>
</section>
<!-- /.sidebar -->

<script>
$('.selectpicker').selectpicker();
function inventory(param){
        switch(param){
            case "ADJUST":
                $.ajax({
                    url: "../_includes/inventory/inv_adjust.php",
                    data: {},
                    beforeSend: function (xhr) {
                        $('#maincontent').html();
                    },
                    success: function (response, textStatus, jqXHR) {
                        $('#maincontent').html(response);
                    }
                });
            break; // END OF CASE
        }
        
        switch(param){
            case "RESTOCKING":
                $.ajax({
                    url: "../_includes/inventory/restocking.php",
                    data: {},
                    beforeSend: function (xhr) {
                        $('#maincontent').html();
                    },
                    success: function (response, textStatus, jqXHR) {
                        $('#maincontent').html(response);
                    }
                });
            break; // END OF CASE
        }
        
        switch(param){
            case "LIST":
                $.ajax({
                    url: "../_includes/inventory/inv_list.php",
                    data: {},
                    beforeSend: function (xhr) {
                        $('#maincontent').html();
                    },
                    success: function (response, textStatus, jqXHR) {
                        $('#maincontent').html(response);
                    }
                });
            break; // END OF CASE
        }
    }

function relationship(param){
    switch(param){
        case "SUPPLIER_LIST":
            $.ajax({
                url: "../_includes/relationship/supplier_list.php",
                data: {},
                beforeSend: function (xhr) {
                    $('#maincontent').html();
                },
                success: function (response, textStatus, jqXHR) {
                    $('#maincontent').html(response);
                }
            });
        break; // END OF CASE
    }
    
    switch(param){
        case "CUSTOMER_LIST":
            $.ajax({
                url: "../_includes/relationship/customer_list.php",
                data: {},
                beforeSend: function (xhr) {
                    $('#maincontent').html();
                },
                success: function (response, textStatus, jqXHR) {
                    $('#maincontent').html(response);
                }
            });
        break; // END OF CASE
    }
}

function ordermanagement(param){
    switch(param){
        case "CREATE_PO":
            $.ajax({
                url: "../_includes/purchaseorder/create.php",
                data: {},
                beforeSend: function (xhr) {
                    $('#maincontent').html();
                },
                success: function (response, textStatus, jqXHR) {
                    $('#maincontent').html(response);
                }
            });
        break; // END OF CASE
    }
    
    switch(param){
        case "CREATE_PR":
            $.ajax({
                url: "../_includes/purchaserequisition/create.php",
                data: {},
                beforeSend: function (xhr) {
                    $('#maincontent').html();
                },
                success: function (response, textStatus, jqXHR) {
                    $('#maincontent').html(response);
                }
            });
        break; // END OF CASE
    }
}

function report(param){
    switch(param){
    case "STOCK_ON_HAND" :
        $.ajax({
                url: "../_includes/reports/stockonhand/stockonhand.php",
                data: {},
                beforeSend: function (xhr) {
                    $('#maincontent').html();
                },
                success: function (response, textStatus, jqXHR) {
                    $('#maincontent').html(response);
                }
            });
        break; // END OF CASE
    }
}
    
function setting(param){
    switch(param){
        case "GLOBAL":
            $.ajax({
                url: "../_includes/setting/global.php",
                data: {},
                beforeSend: function (xhr) {
                    $('#maincontent').html();
                },
                success: function (response, textStatus, jqXHR) {
                    $('#maincontent').html(response);
                }
            });
        break; // END OF CASE
    }
}

function checkout(param){
    switch(param){
        case "CHECKOUT":
            $.ajax({
                url: "../_includes/checkout/checkout.php",
                data: {},
                beforeSend: function (xhr) {
                    $('#maincontent').html();
                },
                success: function (response, textStatus, jqXHR) {
                    $('#maincontent').html(response);
                }
            });
        break; // END OF CASE
    }
}
</script>