<?php
require_once '../../../_config/dbinfo.inc.php';
require_once '../../../_config/misc.func.php';

$judul = urldecode($_GET['judul']);
$inv_id = urldecode($_GET['inv_id']);

$concat_inv_id = str_replace("*", ',', $inv_id);
$concat_inv_id = substr($concat_inv_id, 0, (strlen($concat_inv_id) - 1));
//echo $concat_inv_id;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
        <title>Consumable Album</title>
        <link href="../../../_templates/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <style type="text/css">
            .tbl{
                border: solid 1px black;
            }
            .tbl tr.tr_img{
                height: 210px;
            }
            .tbl tr.tr_nm th{
                height: 60px;
            }
            .tbl tr.tr_img td img{
                height: 210px;
                width:  210px;
            }
            .tbl tbody tr td{
                padding:0px !important;						
            }

            .text-center{
                text-align: center;
            }
            .text-right{
                text-align: right;
            }
            .info{
                background-color: graytext;
                font-weight: bold;
                position: fixed;
                top: 5 ;
                width: 100%;
                opacity: 0.7;
                filter: alpha(opacity=70); /* For IE8 and earlier */
                color: white;
                //margin: 15px 0 15px 0;
            }
            @media print{
                .info{
                    visibility: hidden;
                }
            }
        </style>
        <script src="../../../_templates/plugins/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script>
        <script src="../../../_templates/plugins/qrcode/jquery.qrcode-0.12.0.min.js" type="text/javascript"></script>
    </head>

    <body>
        <div class="info text-right">
            setting margin mozilla : left=10mm, right=5mm, top=5mm, bottom=5mm
            <br/>
            POTRAIT FOLIO PAPER
            <br/>
            <button class="btn btn-lg btn-success" style="opacity: 1;" onclick="window.print();
                    window.close();">PRINT</button>
        </div>        
        <table align="center" style="width:100%;" class="table-condensed" >
            <thead>
                <tr>
                    <th colspan="2"><img src="../../../_templates/img/logo.jpg" style="float: left;"></img><h2 style="float: right;"><?= $judul ?></h2></th>
                </tr>
                <tr>                           
                    <th class="text-center" style="font-style: italic;">Inventori Name</th>
                    <th class="text-center" style="font-style: italic;">Inventori Name</th>
                </tr>
            </thead>  
            <tbody>
                <?php
                $i = 0;
                $j = 0;                
                    $sql = "SELECT "
                            . "INV_ID, "
                            . "INV_DESC_CONCAT, "
                            . "INV_TYPE, "
                            . "NVL(INV_WH_LOC, '-') INV_WH_LOC,"
                            . "INV_IMG "
                            . "FROM VW_INV_IMG "
                            . "WHERE INV_WM_SELECT = '1' AND INV_ID in ($concat_inv_id)"
                            . "ORDER BY INV_DESC_CONCAT ASC";
                
                //echo $sql;
                $sqlPck = oci_parse($conn_wenloginv, $sql);
                oci_execute($sqlPck);
                while ($rowPck = oci_fetch_array($sqlPck)) {

                    if ($i % 2 == 0) {
                        $j ++;
                        ?>                        
                        <tr>
                            <td style="width: 50%;">
                                <table width="98%" class="tbl">
                                    <tbody>
                                        <tr class="tr_nm">
                                            <th colspan="2"><?= $rowPck['INV_DESC_CONCAT']; ?></th>
                                        </tr>
                                        <tr class="tr_img">
                                            <td colspan="2" class="text-center">
                                                <?php
                                                if ($rowPck['INV_IMG']) {
                                                    echo '<img src="data:image/jpeg;base64,' . base64_encode($rowPck['INV_IMG']->load()) . '" />';
                                                } else {
                                                    echo '<i>empty photo</i>';
                                                }
                                                ?>
                                            </td>                                            
                                        </tr>
                                        <tr>
                                            <td style="vertical-align: bottom;font-size: 13px;height: 10px;">
                                                &nbsp;                                                
                                            </td>     
                                            <td style="width: 80px;" class="text-center" rowspan="2">
                                                <span id="<?= "img_qr_$i" . "_$j" ?>" data-id="<?= $rowPck['INV_ID']; ?>"></span>                                                
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align: bottom;font-size: 13px;">
                                                INV ID : <i><small><?= $rowPck['INV_ID']; ?></small></i></br>
                                                <?= "Location : <br/>" . $rowPck['INV_WH_LOC']; ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        <?php } else { ?>
                            <td style="width: 50%;">
                                <table width="98%" class="tbl">
                                    <tbody>
                                        <tr class="tr_nm">
                                            <th colspan="2" ><?= $rowPck['INV_DESC_CONCAT']; ?></th>
                                        </tr>
                                        <tr class="tr_img">
                                            <td colspan="2" class="text-center">
                                                <?php
                                                if ($rowPck['INV_IMG']) {
                                                    echo '<img src="data:image/jpeg;base64,' . base64_encode($rowPck['INV_IMG']->load()) . '" />';
                                                } else {
                                                    echo '<i>empty photo</i>';
                                                }
                                                ?>
                                            </td>                                            
                                        </tr>
                                        <tr>
                                            <td style="vertical-align: bottom;font-size: 13px;height: 10px;">
                                                &nbsp;
                                            </td>     
                                            <td style="width: 80px;" class="text-center" rowspan="2">
                                                <span id="<?= "img_qr_$i" . "_$j" ?>" data-id="<?= $rowPck['INV_ID']; ?>"></span>                                                
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align: top;font-size: 13px;">
                                                INV ID : <i><small><?= $rowPck['INV_ID']; ?></small></i></br>
                                                <?= "Location : <br/>" . $rowPck['INV_WH_LOC']; ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>                        
                            <?php if ($j == 1) { ?>
                            </tr>                            
                            <?php
                            $j = 0;
                        } else {
                            $j ++;
                        }
                    }
                    ?>

                    <?php
                    $i++;
                }
                ?>
            </tbody>
        </table>

        <script type="text/javascript">
            $(document).ready(function () {
                $('span[id ^= img_qr_]').each(function () {
                    $(this).qrcode({
                        "render": "image",
                        "size": 75,
                        "text": $(this).attr('data-id')
                    });
                });
            });
        </script>
    </body>
</html>