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
            <h2 class="col-<?= $KODAMA_THEME_COLOR; ?>">クラス情報一覧<small>クラス情報を編集する前に、選択を行ってください。</small></h2>
            <ul class="header-button">
              <li><a href="javascript:void(0);" onclick="addRecord();">
                <div class="kodama-icon-circle bg-green"> <i class="material-icons">person_add</i> </div>
                <div class="kodama-menu-info"><h4> 追加</h4></div>
              </a></li>
              <li><a href="javascript:void(0);" onclick="editRecord();">
                <div class="kodama-icon-circle bg-light-blue"> <i class="material-icons">person</i> </div>
                <div class="kodama-menu-info"><h4>編集</h4></div>
              </a></li>
              <li><a href="javascript:void(0);" onclick="deleteRecord();">
                <div class="kodama-icon-circle bg-red"> <i class="material-icons">delete</i> </div>
                <div class="kodama-menu-info"><h4>削除</h4></div>
              </a></li>
            </ul>
          </div>
          
          <div style="padding: 0 20px;"><div class="alert-warning col-white" id="message" style="word-wrap: break-word; word-break: break-all;"></div></div>
          
          <!-- DataTable -->
          <div class="body">
            <div class="table-responsive-lg">
              <table id="mainTable" class="table table-bordered table-striped table-hover dataTable display">
                <thead class="col-<?= $KODAMA_THEME_COLOR; ?>">
                  <tr>
                    <th hidden="hidden">クラスID</th>
                    <th class="col-xs-4">クラス名</th>
                    <th class="col-xs-4">担任教師</th>
                    <th class="col-xs-4">注記</th>
                  </tr>
                </thead>
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
<script src="../style/js/kodama-classmanagetable.js"></script>