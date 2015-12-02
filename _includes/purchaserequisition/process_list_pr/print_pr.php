<?php
require_once '../../../_config/dbinfo.inc.php';
require_once '../../../_config/misc.func.php';
// GENERATE THE APPLICATION PAGE
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
//$pr = $_GET['pr'];
//$sql = "SELECT DISTINCT MART_PR_NO, "
//        . "MART_PR_DATE, "
//        . "MART_PR_DELIV, "
//        . "TO_CHAR(MART_PR_REMARKS) MART_PR_REMARKS "
//        . "FROM MART_PR_INFO WHERE MART_PR_NO = '$pr'";
////echo "$sql";
//$parse = oci_parse($conn, $sql);
//oci_execute($parse);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
        <title>Editable Invoice</title>
        <link href="dist/css/print.css" rel="stylesheet" type="text/css"/>
        <link href="dist/css/style.css" rel="stylesheet" type="text/css"/>
    </head>

    <body>
        <div id="page-wrap">
            <textarea id="header">WELTES MART PURCHASE REQUISITION</textarea>
            <div id="identity"></div>	
            <div style="clear:both"></div>
            <div id="customer">
                <?php
//                while ($row = oci_fetch_array($parse)) {
                    ?>
                    <table id="meta">
                        <tr>
                            <td rowspan="3"><img style="height: 80px;" src="../../../_templates/img/mart_icon160x160-01-01.png" alt="logo"/></td>
                            <td style="width: 90px;" class="meta-head">JOB</td>
                            <td style="width: 110px;"><textarea id="date">STOCK</textarea></td>

                            <td class="meta-head" style="width: 233px; text-align: center; font: bold 15px Helvetica, Sans-Serif;">JOB TITLE</td>

                            <td style="width: 110px;" class="meta-head">ISSUE DATE</td>
                            <td style="width: 110px;"><textarea id="date"><?php echo $row['MART_PR_DATE'];?></textarea></td>
                        </tr>
                        <tr>
                            <td class="meta-head">SUBJOB</td>
                            <td><textarea id="date">STOCK</textarea></td>

                            <td rowspan="2"><textarea id="date"></textarea></td>

                            <td class="meta-head">PR #</td>
                            <td><textarea><?php echo $pr;?></textarea></td>
                        </tr>
                        <tr>
                            <td class="meta-head">CUSTOMER</td>
                            <td><textarea id="date"></textarea></td>
                            <td class="meta-head">SPV</td>
                            <td><div class="due">$875.00</div></td>
                        </tr>
                    </table>
                    <?php
//                }
                ?>
            </div>

            <table id="items">
                <tr>
                    <th style="text-align: center;">#</th>
                    <th style="width: 200px; text-align: center;">DESCRIPTION</th>
                    <th style="text-align: center; width: 54px;">QTY</th>
                    <th style="text-align: center;  width: 50px;">UNIT</th>
                    <th style="text-align: center; width: 110px;">NOTE</th>
                    <th style="text-align: center; width: 110px;">PO #</th>
                </tr>

                <tr class="item-row">
                    <td style="text-align: center;border-right: 1px solid;">1</td>
                    <td class="description" style="width: 400px;border-right: 1px solid;">item1</td>
                    <td style="text-align: center;border-right: 1px solid;">90</td>
                    <td style="border-right: 1px solid;"></td>
                    <td style="width: 100px;border-right: 1px solid;"></td>
                    <td style="width: 100px;"></td>
                </tr>

            </table>

            <table id="items">
                <tr class="item-row" style="border-bottom: 1px solid;">
                    <td style="border-right: 1px solid;text-align: center;"><small>DELIVERED TO</small></td>
                    <td style="border-right: 1px solid;text-align: center;width: 110px;"><small>SUBMIT</small></td>
                    <td style="border-right: 1px solid;text-align: center;width: 114px;"><small>APPROVAL</small></td>
                    <td style="border-right: 1px solid;text-align: center;width: 110px;"><small>CHECK</small></td>
                    <td style="text-align: center;width: 110px;"><small>REQUEST</small></td>
                </tr>
                <tr class="item-row" style="height: 50px;">
                    <td rowspan="2" style="border-right: 1px solid;"></td>
                    <td style="border-right: 1px solid;"></td>
                    <td style="border-right: 1px solid;"></td>
                    <td style="border-right: 1px solid;"></td>
                    <td></td>
                </tr>
                <tr class="item-row" style="height: 12px; border-top: 1px solid;">
                    <td style="border-right: 1px solid;"></td>
                    <td style="border-right: 1px solid;"></td>
                    <td style="border-right: 1px solid;"></td>
                    <td style="border-right: 1px solid;"></td>
                </tr>
            </table>
        </div>      
        </div> <!-- PAGE WRAP -->
    </body>
</html>