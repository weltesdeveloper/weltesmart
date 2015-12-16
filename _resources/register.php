<?php
require_once '../_config/dbinfo.inc.php';
$conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>WELTESMART</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.5 -->
        <link href="../_templates/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <!-- Font Awesome -->
        <link href="../_templates/plugins/font-awesome-4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
        <!-- Ionicons -->
        <link href="../_templates/plugins/ionicons-2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css"/>
        <!-- Theme style -->
        <link href="../_templates/css/AdminLTE.min.css" rel="stylesheet" type="text/css"/>
        <!-- iCheck -->
        <link href="../_templates/plugins/iCheck/square/blue.css" rel="stylesheet" type="text/css"/>
        <link href="../_templates/plugins/silviomoreto-bootstrap-select-a8ed49e/dist/css/bootstrap-select.min.css" rel="stylesheet" type="text/css"/>
        <link href="../_templates/plugins/sweetalert-master/dist/sweetalert.css" rel="stylesheet" type="text/css"/>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="hold-transition register-page">
        <div class="register-box">
            <div class="register-logo">
                <a href="#"><b>WELTES</b> MART</a>
            </div>

            <div class="register-box-body">
                <p class="login-box-msg">Register a new membership</p>
                <form method="post">
                    <div class="form-group has-feedback">
                        <input type="text" class="form-control" placeholder="Full Name" id="fullname" required="">
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="email" class="form-control" placeholder="Email" id="email" required="">
                        <span class="glyphicon glyphicon-folder-open form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" class="form-control" placeholder="Password" id="password" required="">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="form-group">
                        <select class="selectpicker form-control" title="Select Role" id="reg-user-role" required="">
                            <?php
                            $roleParse = oci_parse($conn, "SELECT WMR.MART_ROLE_NAME ROLE, WMR.MART_ROLE_ID ID FROM MART_ROLE WMR ORDER BY WMR.MART_ROLE_NAME ASC");
                            $roleExcErr = oci_execute($roleParse);
                            if (!$roleExcErr) {
                                $e = oci_error($roleParse);
                                print htmlentities($e['message']);
                                print "\n<pre>\n";
                                print htmlentities($e['sqltext']);
                                printf("\n%" . ($e['offset'] + 1) . "s", "^");
                                print "\n</pre>\n";
                            }
                            while ($row = oci_fetch_array($roleParse)) {
                                echo "<option value=" . $row['ID'] . ">" . $row['ROLE'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <button type="submit" class="btn btn-primary btn-block btn-flat" id="submit-new-user">Register</button>
                        </div><!-- /.col -->
                    </div>
                </form>


            </div><!-- /.form-box -->
            <br/>
            <p class="login-box-msg"><a href="../index.php">Go To Main Screen</a></p>
        </div><!-- /.register-box -->

        <!-- jQuery 2.1.4 -->
        <script src="../_templates/plugins/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script>
        <!-- Bootstrap 3.3.5 -->
        <script src="../_templates/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <!-- iCheck -->
        <script src="../_templates/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
        <script src="../_templates/plugins/silviomoreto-bootstrap-select-a8ed49e/dist/js/bootstrap-select.min.js" type="text/javascript"></script>
        <script src="../_templates/plugins/sweetalert-master/dist/sweetalert.min.js" type="text/javascript"></script>
        <script src="../_templates/plugins/jqueryvalidation/dist/jquery.validate.min.js" type="text/javascript"></script>
        <script>
            $("#registerform").validate();
            $('.selectpicker').selectpicker();
            $(function () {
                $('input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%' // optional
                });
            });
        </script>
    </body>
</html>

<script>
    $('#submit-new-user').on('click', function () {
        var newUserArr = {};
        var timer = null;
        newUserArr["fullname"] = $('#fullname').val();
        newUserArr["email"] = $('#email').val();
        newUserArr["password"] = $('#password').val();
        newUserArr["role"] = $('#reg-user-role').val();

        $.ajax({
            type: 'POST',
            data: {
                fullname: newUserArr["fullname"],
                email: newUserArr["email"],
                password: newUserArr["password"],
                role: newUserArr["role"]
            },
            url: 'login_process/registerDB.php',
            success: function (response)
            {
                swal({
                    title: "Submit User ?",
                    text: "Contact admin for more information..",
                    type: "warning",
                    showCancelButton: false,
                    confirmButtonColor: "#DD6B55 !important",
                    confirmButtonText: "Yes, Submit",
                    closeOnConfirm: false
                },
                function (isConfirm) {
                    if (isConfirm) {
                        location.href = '../index.php';
                    }
                });
            }
        });
    });
</script>