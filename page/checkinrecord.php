<head>
<!-- code by zmq -->
<?php
require_once( 'frame.php' );
require_once( '../include/include_database.php' );
$studentID = '';
if ( isset( $_GET[ 'ID' ] ) && !empty( $_GET[ 'ID' ] ) ) {
  $studentID = $_GET[ 'ID' ];
}
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
            <h2 class="col-<?= $KODAMA_THEME_COLOR; ?>">Checkin Record<small>Choose record first before operate.</small></h2>
            <ul class="header-button">
              <li><a href="#collapseExample" data-toggle="collapse" aria-expanded="false" aria-controls="collapseExample">
                <div class="kodama-icon-circle bg-orange"> <i class="material-icons">query_builder</i> </div>
                <div class="kodama-menu-info"><h4>Query</h4></div>
              </a></li>
              <li><a href="../attend/situation_build.php">
                <div class="kodama-icon-circle bg-cyan"> <i class="material-icons">person_add</i> </div>
                <div class="kodama-menu-info"><h4>Build</h4></div>
              </a></li>
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
              <li class="kodama-checkbox">
                  <input type="checkbox" id="checkbox_multiselect" class="filled-in chk-col-purple"/>
                  <label for="checkbox_multiselect">複選</label>
              </li>
            </ul>
            <div class="collapse m-t-10" id="collapseExample">
              <form id="queryform" method="GET">
                <div class="well p-t-30">
                  <div class="kodama-horli">
                    <li class="input-group">
                      <span class="input-group-addon"> <i class="material-icons col-orange">person</i> </span>
                      <div class="form-line">
                        <input value="" type="text" class="form-control" name="s.name" placeholder="Name" autofocus>
                      </div>
                    </li>
                    <li class="input-group">
                      <span class="input-group-addon"> <i class="material-icons col-orange">format_list_numbered</i> </span>
                      <div class="form-line">
                        <input value="" type="text" class="form-control" name="s.studentnumber" placeholder="Student Number">
                      </div>
                    </li>

                    <li class="input-group-select clearfix">
                      <span class="input-group-addon"> <i class="material-icons col-orange">class</i> </span>
                      <div class="form-line">
                        <select class="kodama-icon-select" name="s.classID" id="classID">
                          <option value="0">-- Please select class --</option>
                          <?php
                          $sql = 'SELECT ID, name FROM class';
                          $statement = $connection->prepare($sql);
                          $statement->execute();
                          $recordclasses = $statement->fetchAll( PDO::FETCH_OBJ );
                          foreach($recordclasses as $recordclass): ?>
                          <option value="<?= $recordclass->ID ?>"><?= $recordclass->name ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </li>
                  </div>
                  <div style="text-align: center;">
                    <button type="submit" class="btn bg-orange waves-effect" href="javascript:void(0);">Conditional Query</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
          
          <div style="padding: 0 20px;"><div class="alert-warning col-white" id="message" style="word-wrap: break-word; word-break: break-all;"></div></div>
          
          <!-- DataTable -->
          <div class="body">
            <div class="table-responsive-lg">
              <table id="mainTable" class="table table-bordered table-striped table-hover dataTable display">
                <thead class="col-<?= $KODAMA_THEME_COLOR; ?>">
                  <tr>
                    <th class="kodama-fillcontrol" style="width: 5%;">
                      <div class="custom-control custom-checkbox text-center" id="checkbox_hc001" style="margin-left: 20px;">
                        <input type="checkbox" id="checkbox_hc1" class="custom-control-input filled-in chk-col-orange"/>
                        <label class="custom-control-label" for="checkbox_hc1" id="checkbox_hl1" style="visibility: hidden;"></label>
                      </div>
                    </th>
                    <th style="width: 7%;">学籍番号</th>
                    <th style="width: 5%;">氏名</th>
                    <th style="width: 5%;">クラス名</th>
                    <th style="width: 7%;">時間11</th>
                    <th style="width: 7%;">時間12</th>
                    <th style="width: 7%;">時間21</th>
                    <th style="width: 7%;">時間22</th>
                    <th style="width: 7%;">時間31</th>
                    <th style="width: 7%;">時間32</th>
                    <th style="width: 7%;">時間41</th>
                    <th style="width: 7%;">時間42</th>
                    <th style="width: 5%;">屬性</th>
                    <th style="width: 7%;">記錄時間</th>
                    <th style="width: 5%;">設備</th>
                    <th style="width: 5%;">生成する</th>
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
                    <th>学籍番号</th>
                    <th>氏名</th>
                    <th>クラス名</th>
                    <th>時間11</th>
                    <th>時間12</th>
                    <th>時間21</th>
                    <th>時間22</th>
                    <th>時間31</th>
                    <th>時間32</th>
                    <th>時間41</th>
                    <th>時間42</th>
                    <th>屬性</th>
                    <th>記錄時間</th>
                    <th>設備</th>
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
<script type="text/javascript">
$(document).ready(function(){
  _kodama_students.currentstudentid = <?php echo $studentID; ?>;
});
</script>
<script src="../style/js/kodama-checkinrecord.js"></script>