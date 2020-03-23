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
            <h2 class="col-<?= $KODAMA_THEME_COLOR; ?>">Class Manage<small>Choose record first before operate.</small></h2>
            <ul class="header-button">
              <li><a href="javascript:void(0);" onclick="addRecord();">
                <div class="kodama-icon-circle bg-green"> <i class="material-icons">person_add</i> </div>
                <div class="kodama-menu-info"><h4>Add</h4></div>
              </a></li>
              <li><a href="javascript:void(0);" onclick="editRecord();">
                <div class="kodama-icon-circle bg-light-blue"> <i class="material-icons">person</i> </div>
                <div class="kodama-menu-info"><h4>Edit</h4></div>
              </a></li>
              <li><a href="javascript:void(0);" onclick="deleteRecord();">
                <div class="kodama-icon-circle bg-red"> <i class="material-icons">delete</i> </div>
                <div class="kodama-menu-info"><h4>Delete</h4></div>
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
                    <th hidden="hidden">ID</th>
                    <th class="col-xs-1">User名</th>
                    <th class="col-xs-2">名前</th>
                    <th class="col-xs-1">性別</th>
                    <th class="col-xs-2">電話番号</th>
                    <th class="col-xs-1">是否教師</th>
                    <th class="col-xs-1">教師番号</th>
                    <th class="col-xs-2">Email</th>
                    <th class="col-xs-2">説明</th>
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
<script src="../style/js/kodama-usermanagetable.js"></script>