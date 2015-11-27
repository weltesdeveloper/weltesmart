<?php
require_once('../_config/dbinfo.inc.php');
require_once('../_config/misc.func.php');
session_start();

if (!isset($_SESSION['userlogin'])) {
    echo <<< EOD
   <h1>You are UNAUTHORIZED !</h1>
   <p>INVALID usernames/passwords<p>
   <p><a href="../index.php">LOGIN PAGE</a></p>

EOD;
    exit;
}

// GENERATE THE APPLICATION PAGE
$conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB) or die;

// 1. SET THE CLIENT IDENTIFIER AFTER EVERY CALL
// 2. USING UNIQUE VALUE FOR BACK END USER
oci_set_client_identifier($conn, $_SESSION['userlogin']);

$username = htmlentities($_SESSION['userlogin'], ENT_QUOTES);
$companyRole = htmlentities($_SESSION['rolelogin'], ENT_QUOTES);
//    $firstName = htmlentities($_SESSION['firstname'], ENT_QUOTES);
//    $lastName = htmlentities($_SESSION['lastname'], ENT_QUOTES);
$globalName = htmlentities($_SESSION['globalname'], ENT_QUOTES);

$todaysDate = date("m/d/y");
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?= $globalName ?> | Dashboard</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

        <link rel="apple-touch-icon" sizes="57x57" href="../_templates/img/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="../_templates/img/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="../_templates/img/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="../_templates/img/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="../_templates/img/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="../_templates/img/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="../_templates/img/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="../_templates/img/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="../_templates/img/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="../_templates/img/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="../_templates/img/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="../_templates/img/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../_templates/img/favicon-16x16.png">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="../_templates/img/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">

        <!-- Bootstrap 3.3.5 -->
        <link href="../_templates/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <!-- Font Awesome -->
        <link href="../_templates/plugins/font-awesome-4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
        <!-- Ionicons -->
        <link href="../_templates/plugins/ionicons-2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css"/>
        <!-- Theme style -->
        <link href="../_templates/css/AdminLTE.css" rel="stylesheet" type="text/css"/>
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link href="../_templates/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css"/>
        <!-- iCheck -->
        <link href="../_templates/plugins/iCheck/all.css" rel="stylesheet" type="text/css"/>
        <!-- Morris chart -->
        <link href="../_templates/plugins/morris/morris.css" rel="stylesheet" type="text/css"/>
        <!-- jvectormap -->
        <link href="../_templates/plugins/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css"/>
        <!-- Date Picker -->
        <link href="../_templates/plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
        <!-- Daterange picker -->
        <link href="../_templates/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css"/>
        <!-- bootstrap wysihtml5 - text editor -->
        <link href="../_templates/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css"/>
        <link href="../_templates/plugins/silviomoreto-bootstrap-select-a8ed49e/dist/css/bootstrap-select.min.css" rel="stylesheet" type="text/css"/>
        <!-- BOOTSTAP XEDITABLE -->
        <link href="../_templates/plugins/bootstrap3-editable-1.5.1/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet" type="text/css"/>\
        <!-- DATATABLES -->
        <!--<link href="../_templates/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>-->
        <link href="../_templates/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css"/>
        <link href="../_templates/plugins/jquery-ui-1.11.4/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
        <link href="../_templates/plugins/jquery-ui-1.11.4/jquery-ui.theme.min.css" rel="stylesheet" type="text/css"/>
        <!--sweet alert-->
        <link href="../_templates/plugins/sweetalert-master/dist/sweetalert.css" rel="stylesheet" type="text/css">
        <!-- SWITCHERY -->
        <link href="../_templates/plugins/switchery-master/dist/switchery.min.css" rel="stylesheet" type="text/css"/>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        
        <!-- jQuery 2.1.4 -->
        <script src="../_templates/plugins/jQuery/jQuery-2.1.4.min.js"></script>
        <!-- jQuery UI 1.11.4 -->
        <script src="../_templates/plugins/jQueryUI/jquery-ui.min.js" type="text/javascript"></script>
        <script src="../_templates/plugins/jquery-ui-1.11.4/jquery-ui.min.js" type="text/javascript"></script>
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->

        <!-- Bootstrap 3.3.5 -->
        <script src="../_templates/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <!-- Morris.js charts -->
        <script src="../_templates/plugins/raphael_2.1.4.js" type="text/javascript"></script>
        <script src="../_templates/plugins/silviomoreto-bootstrap-select-a8ed49e/dist/js/bootstrap-select.js"></script>
        <script src="../_templates/plugins/morris/morris.min.js" type="text/javascript"></script>
        <!-- Sparkline -->
        <script src="../_templates/plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
        <!-- jvectormap -->
        <script src="../_templates/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
        <script src="../_templates/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
        <!-- jQuery Knob Chart -->
        <script src="../_templates/plugins/knob/jquery.knob.js"></script>
        <!-- daterangepicker -->
        <script src="../_templates/plugins/daterangepicker/moment.min.js" type="text/javascript"></script>
        <script src="../_templates/plugins/daterangepicker/daterangepicker.js"></script>
        <!-- datepicker -->
        <script src="../_templates/plugins/datepicker/bootstrap-datepicker.js"></script>
        <!-- Bootstrap WYSIHTML5 -->
        <script src="../_templates/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
        <!-- Slimscroll -->
        <script src="../_templates/plugins/slimScroll/jquery.slimscroll.min.js"></script>
        <!-- FastClick -->
        <script src="../_templates/plugins/fastclick/fastclick.min.js"></script>
        <!-- AdminLTE App -->
        <script src="../_templates/js/app.min.js" type="text/javascript"></script>
        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
        <script src="../_templates/js/pages/dashboard.js" type="text/javascript"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="../_templates/js/demo.js" type="text/javascript"></script>
        <!-- XEDITABLE -->
        <script src="../_templates/plugins/bootstrap3-editable-1.5.1/bootstrap3-editable/js/bootstrap-editable.min.js" type="text/javascript"></script>
        <!-- DATATABLES -->
        <script src="../_templates/plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
        <script src="../_templates/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
        <!-- IDLE TIMEOUT -->
        <script src="../_templates/plugins/idletimeout/jquery.idletimeout.js" type="text/javascript"></script>
        <script src="../_templates/plugins/idletimeout/jquery.idletimer.js" type="text/javascript"></script>
        <!-- QRCODE GENERATOR -->
        <script src="../_templates/plugins/qrcode/jquery.qrcode-0.12.0.min.js" type="text/javascript"></script>
        <!-- IDLE TIMER JS -->
        <!--<script src="../_templates/js/idletimer.js" type="text/javascript"></script>-->
        <!--sweet alert-->
        <script src="../_templates/plugins/sweetalert-master/dist/sweetalert.min.js"></script>
        <script src="../_templates/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
        <!-- INPUT MASK -->
        <script src="../_templates/plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
        <!-- SWITCHERY -->
        <script src="../_templates/plugins/switchery-master/dist/switchery.js" type="text/javascript"></script>
        <script>
            $.widget.bridge('uibutton', $.ui.button);
        </script>
    </head>
    <body class="hold-transition skin-blue sidebar-mini fixed">
        <!-- dialog window markup -->
        <!--        <div id="dialog" title="Your session is about to expire!">
        <?php // $idleCount = SingleQryFld("SELECT WMS.SETTING_VALUE FROM MART_SETTINGS WMS WHERE WMS.SETTING_DESC = 'SESSION_TIMEOUT'", $conn) ?>
                    <input type="number" value="<?php // echo $idleCount;  ?>" hidden="" id="idle-value">
                    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 50px 0;"></span>
                        You will be logged off in <span id="dialog-countdown" style="font-weight:bold"></span> seconds.
                    </p>
                    <p>Do you want to continue your session?</p>
                </div>-->

        <div class="wrapper">
            <header class="main-header">
                <?php include 'elements/header.php'; ?>
            </header>

            <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar">
                <?php include 'elements/sidebar.php'; ?>
            </aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper" id="maincontent">
                <?php include 'elements/mainpage.php'; ?>
            </div><!-- /.content-wrapper -->

            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <?php include 'elements/control_sidebar.php'; ?>
            </aside><!-- /.control-sidebar -->
            <!-- Add the sidebar's background. This div must be placed
                 immediately after the control sidebar -->
            <div class="control-sidebar-bg"></div>

        </div><!-- ./wrapper -->

        
        
    </body>
</html>