<?php
    require_once('../../../_config/dbinfo.inc.php');
    require_once('../../../_config/misc.func.php');

    $todaysDate = date("m/d/y");
    $globalName = SingleQryFld("SELECT WMS.SETTING_VALUE_STRING FROM MART_SETTINGS WMS WHERE WMS.SETTING_DESC = 'GLOBAL_NAME'", $conn);
    $var1sql = "SELECT WMS.SETTING_VALUE FROM MART_SETTINGS WMS WHERE WMS.SETTING_DESC = 'SESSION_TIMEOUT'";
    $var1 = SingleQryFld($var1sql, $conn);
?>
<div class="lock-container full-height">
<!-- START PAGE CONTENT WRAPPER -->
<div class="container-sm-height full-height sm-p-t-50">
    <div class="row row-sm-height">
        
        <div class="col-sm-6 col-sm-height col-middle">
          <!-- START Lock Screen User Info -->
            <div class="inline">
              <div class="thumbnail-wrapper circular d48 m-r-10 ">
                <img width="43" height="43" data-src-retina="../../_templates/img/mart_icon160x160-01.png" data-src="../../_templates/img/mart_icon160x160-01.png" alt="" src="../../_templates/img/mart_icon160x160-01.png">
              </div>
            </div>
            <div class="inline">
                <h5 class="logged hint-text no-margin">Weltes Store Checkout</h5>
                <h2 class="name no-margin">Consumables</h2>
            </div>
          <!-- END Lock Screen User Info -->
        </div>
        
    <div class="col-sm-6 col-sm-height col-middle">
      <!-- START Lock Screen Form -->
      <form id="form-lock" role="form" action="index.html">
        <div class="row">
          <div class="col-sm-12">
            <!-- START Form Control -->
            <div class="form-group sm-m-t-30">
              <label> &nbsp;CHOOSE SUPERVISOR</label>
              <div class="controls">
                  <select class="selectpicker" id="spv-login" data-style="btn-success" data-live-search="true" title="TOUCH HERE...">
                      <?php
                          $invCatParse = oci_parse($conn, "SELECT MS.SPV_NM FROM MST_SPV@WELTESMART_WENLOGINV_LINK MS ORDER BY MS.SPV_NM ASC");
                          $invExcErr = oci_execute($invCatParse);
                          if (!$invExcErr) {
                              $e = oci_error($invCatParse);
                              print htmlentities($e['message']);
                              print "\n<pre>\n";
                              print htmlentities($e['sqltext']);
                              printf("\n%" . ($e['offset'] + 1) . "s", "^");
                              print "\n</pre>\n";
                          }

                          while ($row = oci_fetch_array($invCatParse)) {
                              ?>
                              <option value='<?php echo $row['SPV_NM']; ?>'>
                                  <?php echo $row['SPV_NM']; ?>
                              </option>
                              <?php
                          }
                      ?>
                  </select>
              </div>
            </div>
            <!-- END Form Control -->
          </div>
        </div>
        <!-- START Lock Screen Notification Icons-->
        <div class="row">
          <div class="col-sm-12">
            <a href="#" class="inline text-black fs-14 hint-text"> &nbsp;<i class="fa fa-phone"></i> <span class="hint-text">Contact Administrator</span></a>
          </div>
        </div>
        <!-- END Lock Screen Notification Icons-->
      </form>
      <!-- END Lock Screen Form -->
    </div>
        
    </div>
</div>
<!-- END PAGE CONTENT WRAPPER -->
</div>
<script>
    $('#spv-login').on('change',function(){
        $.ajax({
            url: "divpages/inventory_request.php",
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