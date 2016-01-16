<?php
    require_once('../../_config/Dbconfig.php');
    $dbconbfig = new Dbconfig();
?>

<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
    <title>Login Request</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <link rel="apple-touch-icon" sizes="57x57" href="../../_templates/img/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="../../_templates/img/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="../../_templates/img/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="../../_templates/img/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="../../_templates/img/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="../../_templates/img/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="../../_templates/img/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="../../_templates/img/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="../../_templates/img/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="../../_templates/img/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="../../_templates/img/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="../../_templates/img/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../../_templates/img/favicon-16x16.png">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="../../_templates/img/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-touch-fullscreen" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta content="" name="description" />
        <meta content="" name="author" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta content="" name="description" />
    <meta content="" name="author" />
    
    <link href="../../_templates/additional/assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" />
    <link href="../../_templates/additional/assets/plugins/boostrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../../_templates/additional/assets/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
    <link href="../../_templates/additional/assets/plugins/jquery-scrollbar/jquery.scrollbar.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="../../_templates/additional/assets/plugins/bootstrap-select2/select2.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="../../_templates/additional/assets/plugins/switchery/css/switchery.min.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="../../_templates/additional/pages/css/pages-icons.css" rel="stylesheet" type="text/css">
    <link href="../../_templates/plugins/silviomoreto-bootstrap-select-a8ed49e/dist/css/bootstrap-select.min.css" rel="stylesheet" type="text/css"/>
    <link class="main-stylesheet" href="../../_templates/additional/pages/css/pages.css" rel="stylesheet" type="text/css" />
    <!--[if lte IE 9]>
        <link href="pages/css/ie9.css" rel="stylesheet" type="text/css" />
    <![endif]-->
    <script type="text/javascript">
    window.onload = function()
    {
      // fix for windows 8
      if (navigator.appVersion.indexOf("Windows NT 6.2") != -1)
        document.head.innerHTML += '<link rel="stylesheet" type="text/css" href="pages/css/windows.chrome.fix.css" />'
    }
    </script>
  </head>
  <body class="fixed-header">
    <!-- START PAGE-CONTAINER -->
    <div id="cons-request">
      
    </div>
    <!-- END PAGE-CONTAINER -->

    
    <!-- END OVERLAY -->
    <!-- BEGIN VENDOR JS -->
    <script src="../../_templates/additional/assets/plugins/pace/pace.min.js" type="text/javascript"></script>
    <script src="../../_templates/additional/assets/plugins/jquery/jquery-1.11.1.min.js" type="text/javascript"></script>
    <script src="../../_templates/additional/assets/plugins/modernizr.custom.js" type="text/javascript"></script>
    <script src="../../_templates/additional/assets/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
    <script src="../../_templates/additional/assets/plugins/boostrapv3/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../../_templates/additional/assets/plugins/jquery/jquery-easy.js" type="text/javascript"></script>
    <script src="../../_templates/additional/assets/plugins/jquery-unveil/jquery.unveil.min.js" type="text/javascript"></script>
    <script src="../../_templates/additional/assets/plugins/jquery-bez/jquery.bez.min.js"></script>
    <script src="../../_templates/additional/assets/plugins/jquery-ios-list/jquery.ioslist.min.js" type="text/javascript"></script>
    <script src="../../_templates/additional/assets/plugins/jquery-actual/jquery.actual.min.js"></script>
    <script src="../../_templates/additional/assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <script type="text/javascript" src="../../_templates/additional/assets/plugins/bootstrap-select2/select2.min.js"></script>
    <script type="text/javascript" src="../../_templates/additional/assets/plugins/classie/classie.js"></script>
    <script src="../../_templates/additional/assets/plugins/switchery/js/switchery.min.js" type="text/javascript"></script>
    <script src="../../_templates/additional/assets/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
    <script src="../../_templates/plugins/silviomoreto-bootstrap-select-a8ed49e/dist/js/bootstrap-select.min.js" type="text/javascript"></script>
    <!-- END VENDOR JS -->
    <!-- BEGIN CORE TEMPLATE JS -->
    <script src="../../_templates/additional/pages/js/pages.min.js"></script>
    <!-- END CORE TEMPLATE JS -->
    <!-- BEGIN PAGE LEVEL JS -->
    <script src="../../_templates/additional/assets/js/scripts.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL JS -->
    <script>
        $(function() {
          $('#form-lock').validate();
        });
        
        $( document ).ready(function() {
            $.ajax({
                url: "divpages/login_element.php",
                data: {},
                beforeSend: function (xhr) {
                    $('#cons-request').html();
                },
                success: function (response, textStatus, jqXHR) {
                    $('#cons-request').html(response);
                }
            });
        });

    </script>
  </body>
</html>