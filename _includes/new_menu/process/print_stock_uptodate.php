<?php
require_once '../../../_config/dbinfo.inc.php';
require_once '../../../_config/misc.func.php';
session_start();
// GENERATE THE APPLICATION PAGE
$conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB) or die;

// 1. SET THE CLIENT IDENTIFIER AFTER EVERY CALL
// 2. USING UNIQUE VALUE FOR BACK END USER
oci_set_client_identifier($conn, $_SESSION['userlogin']);
$username = htmlentities($_SESSION['userlogin'], ENT_QUOTES);
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" href="../../../_templates/bootstrap/css/bootstrap.min.css">
        <style>
            @media print,screen{
                #header{
                    font-size: 18px;
                    font-weight: bolder;
                    font-family: Times New Roman;
                }
                #inventory-adjust-table{
                    font-size: 12px;
                    font-family: Times New Roman;
                }
                .table > thead > tr > th, 
                .table > tbody > tr > th, 
                .table > tfoot > tr > th, 
                .table > thead > tr > td, 
                .table > tbody > tr > td, 
                .table > tfoot > tr > td{
                    padding: 4px;
                    border: 1px solid black;
                    line-height: 1.25;
                    vertical-align: middle;
                }
                #inventory-adjust-table{
                    border: 1px solid black;
                }
                .th-header{
                    border: 1px solid black;
                    border-left: 1px solid black;
                }
            }
        </style>
    </head>
    <body>  
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title text-center" id="header">LIST WAREHOUSE INVENTORY <br>PRINTED ON <?php echo date("d F Y h:m:i");?></h3>
                    </div><!-- /.box-header -->
                    <br>
                    <div class="box-body">
                        <table id="inventory-adjust-table" class="table" style="border: 1px solid black;">
                            <thead>
                                <tr>
                                    <th class="text-center th-header" style="border: 1px solid black;">NO</th>
                                    <th class="text-center th-header" style="border: 1px solid black;">INVENTORY NAME</th>
                                    <th class="text-center th-header" style="border: 1px solid black;">STOCK</th>
                                    <th class="text-center th-header" style="border: 1px solid black;">MIN</th>
                                    <th class="text-center th-header" style="border: 1px solid black;">MAX</th>
                                    <th class="text-center th-header" style="border: 1px solid black;">SAFE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT DISTINCT INV_TYPE FROM MART_STOCK_INFO ORDER BY INV_TYPE ASC";
                                $parse = oci_parse($conn, $sql);
                                oci_execute($parse);
                                $nomer = 1;
                                while ($row = oci_fetch_array($parse)) {
                                    ?>
                                    <tr>
                                        <th colspan="6" class="text-left">
                                            <?php echo $row['INV_TYPE']; ?>
                                        </th>
                                    </tr>
                                    <?php
                                    $sql1 = "SELECT INV_ID, "
                                            . "INV_DESC, "
                                            . "NVL(INV_STK_QTY,0)INV_STK_QTY, "
                                            . "NVL(INV_STK_MIN,0)INV_STK_MIN, "
                                            . "NVL(INV_STK_MAX,0)INV_STK_MAX, "
                                            . "NVL(INV_STK_SAFE,0)INV_STK_SAFE "
                                            . "FROM MART_STOCK_INFO "
                                            . "WHERE INV_TYPE = '$row[INV_TYPE]' "
                                            . "ORDER BY INV_DESC";
                                    $parse1 = oci_parse($conn, $sql1);
                                    oci_execute($parse1);
                                    while ($row1 = oci_fetch_array($parse1)) {
                                        ?>
                                        <tr>
                                            <td class="content-td text-center" style="border: 1px solid black;">
                                                <?php echo "$nomer"; ?>
                                            </td>
                                            <td class="content-td text-left" style="border: 1px solid black;">
                                                <?php echo $row1['INV_DESC']; ?>
                                            </td>
                                            <td class="content-td text-right" style="border: 1px solid black;">
                                                <?php echo $row1['INV_STK_QTY']; ?>
                                            </td>
                                            <td class="content-td text-right" style="border: 1px solid black;">
                                                <?php echo $row1['INV_STK_MIN']; ?>
                                            </td>
                                            <td class="content-td text-right" style="border: 1px solid black;">
                                                <?php echo $row1['INV_STK_MAX']; ?>
                                            </td>
                                            <td class="content-td text-right" style="border: 1px solid black;">
                                                <?php echo $row1['INV_STK_SAFE']; ?>
                                            </td>
                                        </tr>
                                        <?php
                                        $nomer++;
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
        </div>
    </body>
</html>
