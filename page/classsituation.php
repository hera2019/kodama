<head>
<!-- code by zmq -->
<?php
require_once( 'frame.php' );
?>
<!-- JQuery DataTable Css -->
<link href="../style/css/dataTables.bootstrap.css" rel="stylesheet">
<link href="../style/css/kodama.css" rel="stylesheet">
</head>

<section class="content">
  <div class="container-fluid">
    <div class="row m-t--60">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
          <div class="kodama-header">
            <h2 class="col-<?= $KODAMA_THEME_COLOR; ?>">Class Situation<small>Choose record first before operate.</small></h2>
            <ul class="header-button">
              <li><a href="../attend/situation_build.php">
                <div class="kodama-icon-circle bg-cyan"> <i class="material-icons">build</i> </div>
                <div class="kodama-menu-info"><h4>Build</h4></div>
              </a></li>
              <li><a href="../attend/situation_rebuildall.php">
                <div class="kodama-icon-circle bg-indigo"> <i class="material-icons">build</i> </div>
                <div class="kodama-menu-info"><h4>Rebuild All</h4></div>
              </a></li>
              <li><a href="javascript:void(0);" onclick="setClassProperty(1);">
                <div class="kodama-icon-circle bg-green"> <i class="material-icons">person</i> </div>
                <div class="kodama-menu-info"><h4>置為:授業</h4></div>
              </a></li>
              <li><a href="javascript:void(0);" onclick="setClassProperty(7);">
                <div class="kodama-icon-circle bg-red"> <i class="material-icons">person</i> </div>
                <div class="kodama-menu-info"><h4>置為:なし</h4></div>
              </a></li>
              <li class="kodama-checkbox">
                  <input type="checkbox" id="checkbox_multiselect" class="filled-in chk-col-purple"/>
                  <label for="checkbox_multiselect">複選</label>
              </li>
            </ul>
          </div>
          
          <div style="padding: 0 20px;"><div class="alert-warning col-white" id="message" style="word-wrap: break-word; word-break: break-all;"></div></div>
          
          <!-- DataTable -->
          <div class="body">
            <div class="table-responsive-lg">
              <table id="mainTable" class="table table-bordered table-striped table-hover dataTable display">
                <thead class="col-<?= $KODAMA_THEME_COLOR; ?>">
                  <tr>
                    <th class="col-xs-1 kodama-fillcontrol" style="width: 20px;">
                      <div class="custom-control custom-checkbox text-center" id="checkbox_hc001" style="margin-left: 20px;">
                        <input type="checkbox" id="checkbox_hc1" class="custom-control-input filled-in chk-col-orange"/>
                        <label class="custom-control-label" for="checkbox_hc1" id="checkbox_hl1" style="visibility: hidden;"></label>
                      </div>
                    </th>
                    <th class="col-xs-2">クラス名</th>
                    <th class="col-xs-1">クラス索引</th>
                    <th class="col-xs-2">チェックイン率</th>
                    <th class="col-xs-2">クラス属性</th>
                    <th class="col-xs-2">記録日時</th>
                    <th class="col-xs-2">生成する</th>
                  </tr>
                </thead>
                <tfoot class="col-<?= $KODAMA_THEME_COLOR; ?>">
                  <tr>
                    <th class="kodama-fillcontrol" id="checkbox_h2">
                      <div class="custom-control custom-checkbox text-center">
                        <input type="checkbox" id="checkbox_hc2" class="custom-control-input filled-in chk-col-orange"/>
                        <label class="custom-control-label" for="checkbox_hc2" id="checkbox_hl2" style="visibility: hidden;"></label>
                      </div>
                    </th>
                    <th>クラス名</th>
                    <th>クラス索引</th>
                    <th>チェックイン率</th>
                    <th>クラス属性</th>
                    <th>記録時間</th>
                    <th>生成する</th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Jquery DataTable Plugin Js --> 
<script src="../style/js/jquery.dataTables.js"></script>
<script src="../style/js/dataTables.bootstrap.js"></script>
<script src="../style/js/kodama-classsituationtable.js"></script>