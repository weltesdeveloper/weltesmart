<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
        <div class="pull-left image">
            <img src="../_templates/img/mart_icon160x160-01-01.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
            <p><?= $username ?></p>
            <a href="#"><i class="fa fa-sitemap text-success"></i> <?php echo $user_role; ?></a>
        </div>
    </div>

    <!-- search form -->
    <!--    <div class="input-group">
            <select class="selectpicker" data-style="btn-primary" data-width="300px" title="Quick Report..">
                <option>Report 1</option>
                <option>Report 2</option>
                <option>Report 3</option>
            </select>
        </div>-->

    <ul class="sidebar-menu">
        <li class="header">MAIN MENU</li>
        <li class="">
            <a href="#">
                <i class="fa fa-briefcase"></i> <span> Inventory Control</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a onclick="inventory('ADJUST')" style="cursor: pointer;"><i class="fa fa-terminal"></i> Inventory Adjustment</a></li>
            </ul>
        </li>
        <li class="">
            <a href="#">
                <i class="fa fa-gear"></i> <span>Check OUT</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">                
                <li><a onclick="checkout('CHECKOUT')" style="cursor: pointer;"><i class="fa fa-sign-out text-warning"></i> <span>By Select</span></a></li>
                <li><a onclick="checkout('CHECKOUT_BARCODE')" style="cursor: pointer;"><i class="fa fa-sign-out text-success"></i> <span>By Barcode</span></a></li>
            </ul>
        </li>
        <li class="">
            <a href="#">
                <i class="fa fa-gear"></i> <span>Setting Inventory</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a onclick="setting('GLOBAL')" style="cursor: pointer;"><i class="fa fa-globe"></i> Global Setting</a></li>
                <li><a onclick="setting('MASTER_INV')" style="cursor: pointer;"><i class="fa fa-cubes"></i> Master Inventory</a></li>
                <li><a onclick="setting('ALBUM')" style="cursor: pointer;"><i class="fa fa-sitemap"></i> Inventory Library</a></li>
            </ul>
        </li>
        <li class="">
            <a href="#">
                <i class="fa fa-print"></i> <span> Reports</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a onclick="report('STOCK_ON_HAND')" style="cursor: pointer;"><i class="fa fa-truck"></i> Stock On Hand</a></li>
                <li><a onclick="report('CHECKOUT_SUMM')" style="cursor: pointer;"><i class="fa fa-sign-out"></i> Checkout Summary</a></li>
            </ul>
        </li>
        <!--TESTING ITEM-->
        <li class="">
            <a href="#">
                <i class="fa fa-print"></i> <span> TestingData</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <!--<li><a onclick="report('STOCK_ON_HAND')" style="cursor: pointer;"><i class="fa fa-truck"></i> Stock On Hand</a></li>-->
                <li><a onclick="new_menu('NEW_ADJUST')" style="cursor: pointer;">
                        <i class="fa fa-sign-in"></i> Adjustment Stock</a>
                </li>
                <li><a onclick="new_menu('NEW_CHECKOUT')" style="cursor: pointer;">
                        <i class="fa fa-sign-out"></i> Check Out Stock</a>
                </li>
            </ul>
        </li>
    </ul>
</section>
<!-- /.sidebar -->

<script type="text/javascript">
    $('.selectpicker').selectpicker();
    function inventory(param) {
        switch (param) {
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

        switch (param) {
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

        switch (param) {
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

    function relationship(param) {
        switch (param) {
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

        switch (param) {
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

    function pr(param) {
        switch (param) {
            case "LIST_PR":
                $.ajax({
                    url: "../_includes/purchaserequisition/list.php",
                    data: {},
                    beforeSend: function (xhr) {
                        $('#maincontent').html();
                    },
                    success: function (response, textStatus, jqXHR) {
                        $('#maincontent').html(response);
                    }
                });
                break; // END OF CASE

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

            case "REV_PR":
                $.ajax({
                    url: "../_includes/purchaserequisition/revise.php",
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

    function report(param) {
        switch (param) {
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

            case "CHECKOUT_SUMM" :
                $.ajax({
                    url: "../_includes/reports/checkoutsumm/checkout_summary.php",
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

    function setting(param) {
        switch (param) {
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

            case "MASTER_INV":
                $.ajax({
                    url: "../_includes/setting/master_inv_type.php",
                    data: {},
                    beforeSend: function (xhr) {
                        $('#maincontent').html();
                    },
                    success: function (response, textStatus, jqXHR) {
                        $('#maincontent').html(response);
                    }
                });
                break; // END OF CASE
            case "ALBUM":
                $.ajax({
                    url: "../_includes/setting/album_warehouse.php",
                    data: {},
                    beforeSend: function (xhr) {
                        $('#maincontent').html();
                    },
                    success: function (response, textStatus, jqXHR) {
                        $('#maincontent').html(response);
                    }
                });
                break;
        }
    }

    function checkout(param) {
        switch (param) {
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

            case "CHECKOUT_BARCODE":
                $.ajax({
                    url: "../_includes/checkout_barcode/view_checkout.php",
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

    function new_menu(param) {
        switch (param) {
            case "NEW_ADJUST":
                $.ajax({
                    url: "../_includes/new_menu/adjust/view_adjustment.php",
                    data: {},
                    beforeSend: function (xhr) {
                        $('#maincontent').html();
                    },
                    success: function (response, textStatus, jqXHR) {
                        $('#maincontent').html(response);
                    }
                });
                break;

            case "NEW_CHECKOUT":
                $.ajax({
                    url: "../_includes/new_menu/checkout/view_checkout.php",
                    data: {},
                    beforeSend: function (xhr) {
                        $('#maincontent').html();
                    },
                    success: function (response, textStatus, jqXHR) {
                        $('#maincontent').html(response);
                    }
                });
                break;
        }
    }
</script>