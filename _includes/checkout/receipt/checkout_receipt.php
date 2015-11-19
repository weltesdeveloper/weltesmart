<?php
require_once '../../../_config/dbinfo.inc.php';
require_once '../../../_config/FunctionAct.php';
session_start();
// GENERATE THE APPLICATION PAGE
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
$query = "SELECT DISTINCT MART_WR_ID, "
        . "TO_CHAR(MART_WR_DATE,'DD MONTH YYYYY')MART_WR_DATE "
        . "MART_WR_SIGN, "
        . "MART_WR_JOB, "
        . "MART_WR_SUBJOB, "
        . "MART_WR_CARRIER, "
        . "MART_WR_SPV_SIGN, "
        . "MART_WR_FM_SIGN,"
        . "MART_WR_REMARK "
        . "FROM MART_CHECKOUT_INFO ";
//echo $query;
$queryParse = oci_parse($conn, $query);
oci_execute($queryParse);
$wh_id = "";
$job = "";
$subjob = "";
$date = "";
$pembawa = "";
$sign = "";
$manager = "";
$spv = "";
$remark = "";
while ($row1 = oci_fetch_array($queryParse)) {
    $wh_id = "$row1[MART_WR_ID]";
    $job = "$row1[MART_WR_JOB]";
    $subjob = "$row1[MART_WR_SUBJOB]";
    $date = "$row1[MART_WR_DATE]";
    $pembawa = "$row1[MART_WR_CARRIER]";
    $sign = "$row1[MART_WR_SIGN]";
    $manager = "$row1[MART_WR_FM_SIGN]";
    $spv = "$row1[MART_WR_SPV_SIGN]";
    $remark = $row1['MART_WR_REMARK'];
}
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>WAREHOUSE RECEIPT</title>
        <link href="dist/css/print.css" rel="stylesheet" type="text/css"/>
        <link href="dist/css/style.css" rel="stylesheet" type="text/css"/>

        <!--[if lt IE 9]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>

    <body>
        <div id="page-wrap">
            <textarea id="header">PT. WELTES ENERGI NUSANTARA - WAREHOUSE RECEIPT</textarea>
            <div id="identity"></div>	
            <div style="clear:both"></div>
            <div id="customer">
                <table id="meta">
                    <tr>
                        <td rowspan="2"><img style="height: 55px;" src="../../../_templates/img/mart_icon160x160-01-01.png" alt="logo"/></td>
                        <td style="width: 70px; text-align: right; font-weight: bold;" class="meta-head">JOB &nbsp;</td>
                        <td style="width: 110px; text-align: left;">&nbsp; <?php echo $job;?></td>
                        <td id="wr-title" rowspan="2" style="width: 240px; text-align: center;">WAREHOUSE RECEIPT <br/> 
                            <div id="wr-id"><?php echo $wh_id;?></div>
                        </td>
                        <td style="width: 50px; text-align: right; font-weight: bold;" class="meta-head">DATE &nbsp;</td>
                        <td style="width: 110px; text-align: left;">&nbsp; <?php echo $date;?></td>
                        <td rowspan="2" id="wr-qr" style=" height: 58px; left: 0; margin: 0; position: relative; top: 0;width: 35px;"></td>
                    </tr>
                    <tr>
                        <td style="width: 70px; text-align: right; font-weight: bold;" class="meta-head">SUBJOB &nbsp;</td>
                        <td style="width: 110px; text-align: left;">&nbsp; <?php echo $subjob;?></td>
                        <td colspan="2" style="width: 40px; text-align: right; font-weight: bold;"><img id="UPC-barcode" /></td>
                    </tr>
                </table>
            </div>

            <table id="items">
                <tr>
                    <th style="text-align: center;width: 35px;">NO</th>
                    <th style="width: 200px; text-align: center;">ITEM DESCRIPTION</th>
                    <th style="text-align: center; width: 112px;">QTY</th>
                    <th style="text-align: center; width: 222px;">ITEM REMARKS</th>
                </tr>
                <?php
                $i = 1;
                $sql = "SELECT * FROM MART_CHECKOUT_INFO ORDER BY INV_DESC ASC";
                $parse = oci_parse($conn, $sql);
                oci_execute($parse);
                while ($row = oci_fetch_array($parse)) {
                    ?>
                    <tr class="item-row">
                        <td style="text-align: center;border-right: 1px solid;">
                            <?php echo $i++; ?>
                        </td>
                        <td class="description" style="width: 400px;border-right: 1px solid;">
                            <?php echo $row['INV_DESC']; ?>
                        </td>
                        <td style="border-right: 1px solid; text-align: center">
                            <?php echo $row['MART_WR_INV_QTY']; ?>
                        </td>
                        <td style="width: 100px;">
                            <?php echo $row['MART_WR_INV_REMARK']; ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>


            </table>

            <table id="items">
                <tr class="item-row" style="border-bottom: 1px solid;">
                    <td style="border-right: 1px solid;text-align: center;"><small>REMARKS / ADDITIONAL INFO</small></td>
                    <td style="border-right: 1px solid;text-align: center;width: 110px;"><small>CARRIER</small></td>
                    <td style="border-right: 1px solid;text-align: center;width: 114px;"><small>WAREHOUSE</small></td>
                    <td style="border-right: 1px solid;text-align: center;width: 110px;"><small>FACTORY MANAGER</small></td>
                    <td style="text-align: center;width: 110px;"><small>SUPERVISOR</small></td>
                </tr>
                <tr class="item-row" style="height: 50px;">
                    <td rowspan="2" style="border-right: 1px solid; text-align: center;"><?php echo $remark;?></td>
                    <td style="border-right: 1px solid;"></td>
                    <td style="border-right: 1px solid;"><img id="signature" src="../../../_templates/img/ACSignature.jpg" alt=""/></td>
                    <td style="border-right: 1px solid;"></td>
                    <td></td>
                </tr>
                <tr class="item-row" style="height: 12px; border-top: 1px solid;">
                    <td class="user-identifier" style="border-right: 1px solid;"><?= strtoupper($pembawa) ?></td>
                    <td class="user-identifier" style="border-right: 1px solid;"><?= strtoupper($sign) ?></td>
                    <td class="user-identifier" style="border-right: 1px solid;"><?= strtoupper($manager) ?></td>
                    <td class="user-identifier" style="border-right: 1px solid;"><?= strtoupper($spv) ?></td>
                </tr>
            </table>
        </div>      
    </div> <!-- PAGE WRAP -->
</body>
</html>

<script src="../../../_templates/plugins/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script>
<script src="../../../_templates/plugins/qrcode/jquery.qrcode-0.12.0.min.js" type="text/javascript"></script>
<script src="../../../_templates/plugins/barcodegenerator/CODE128.js" type="text/javascript"></script>
<script src="../../../_templates/plugins/barcodegenerator/JsBarcode.js" type="text/javascript"></script>

<script>
    var wrid = $('#wr-id').text();


    $('#wr-qr').qrcode({
        render: "div",
        size: 60,
        text: wrid
    });

    $("#UPC-barcode").JsBarcode(wrid, {width: 1, height: 20});

</script>