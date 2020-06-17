<head>
<!-- code by zmq -->
<?php $INCLUDE_STUDENT_INFO = true; ?>
<?php
require_once( 'frame.php' );
require_once( '../include/include_database.php' );
?>
<!-- JQuery DataTable Css -->
<link href="../style/css/dataTables.bootstrap.css" rel="stylesheet">
<link href="../style/css/kodama.css" rel="stylesheet">
<style>
  td.details-control {
      background: url('../style/images/details_open.png') no-repeat center center;
      cursor: pointer;
  }
  tr.shown td.details-control {
      background: url('../style/images/details_close.png') no-repeat center center;
  }
  table.row-expand {
    background-color: #fffede;
    border: none;
    width: 100%;
  }
  table.row-expand, table.row-expand tr, table.row-expand tr td {
    border: none;
  }
</style>
</head>

<section class="content">
  <div class="container-fluid">
    <div class="row m-t--60">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php
        $strinfo = '学生情報：このページで生徒を選択する、編集ボタンを押して、学生の基本情報を編集します。';
        if(isset($INCLUDE_STUDENT_INFO) && $INCLUDE_STUDENT_INFO) {
          require_once( '../frame/studentinfo.php' );
        }
        ?>
        <div class="card">
          <div class="kodama-header">
            <h2 class="col-<?= $KODAMA_THEME_COLOR; ?>">学生一覧表<small>学生情報を編集する前に学生を選択してください。</small></h2>
            <ul class="header-button">
              <li><a href="#collapseExample" data-toggle="collapse" aria-expanded="false" aria-controls="collapseExample">
                <div class="kodama-icon-circle bg-orange"> <i class="material-icons">query_builder</i> </div>
                <div class="kodama-menu-info"><h4>フィルタ</h4></div>
              </a></li>
              <li><a href="javascript:void(0);" onclick="addStudent();">
                <div class="kodama-icon-circle bg-green"> <i class="material-icons">person_add</i> </div>
                <div class="kodama-menu-info"><h4>追加</h4></div>
              </a></li>
              <li><a href="javascript:void(0);" onclick="editStudent();">
                <div class="kodama-icon-circle bg-light-blue"> <i class="material-icons">person</i> </div>
                <div class="kodama-menu-info"><h4>編集</h4></div>
              </a></li>
              <li><a href="javascript:void(0);" onclick="deleteStudent();">
                <div class="kodama-icon-circle bg-red"> <i class="material-icons">delete</i> </div>
                <div class="kodama-menu-info"><h4>削除</h4></div>
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
                        <input value="" type="text" class="form-control" name="s.name" placeholder="名前" autofocus>
                      </div>
                    </li>
                    <li class="input-group">
                      <span class="input-group-addon"> <i class="material-icons col-orange">format_list_numbered</i> </span>
                      <div class="form-line">
                        <input value="" type="text" class="form-control" name="s.studentnumber" placeholder="学籍番号">
                      </div>
                    </li>
                    <li class="input-group">
                      <span class="input-group-addon"> <i class="material-icons col-orange">pregnant_woman</i> </span>
                      <div class="form-line">
                        <input name="s.genderfemale" type="radio" id="radio_000" class="with-gap radio-col-grey" value="-1" checked="checked" />
                        <label for="radio_000">ALL</label>
                        <input name="s.genderfemale" type="radio" id="radio_001" class="with-gap radio-col-blue" value="0" />
                        <label for="radio_001">男 Male</label>
                        <input name="s.genderfemale" type="radio" id="radio_002" class="with-gap radio-col-pink" value="1" />
                        <label for="radio_002">女 Female</label>
                      </div>
                    </li>
                    <li class="input-group-select clearfix">
                      <span class="input-group-addon"> <i class="material-icons col-orange">account_balance</i> </span>
                      <div class="form-line">
                        <select class="kodama-icon-select" name="s.nationalityregion">
                          <option value="-1">-- 国籍・地域でフィルタ --</option>
                          <?php
                          $sql = 'SELECT typeID, typename FROM idconfig WHERE type="nationalityregion" ORDER BY typeID ASC';
                          $statement = $connection->prepare($sql);
                          $statement->execute();
                          $recordnationalityregion = $statement->fetchAll( PDO::FETCH_OBJ );
                          foreach($recordnationalityregion as $recordnationalityregion): ?>
                          <option value="<?= $recordnationalityregion->typename ?>"><?= $recordnationalityregion->typename ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </li>
                    <li class="input-group-select clearfix">
                      <span class="input-group-addon"> <i class="material-icons col-orange">class</i> </span>
                      <div class="form-line">
                        <select class="kodama-icon-select" name="s.classID" id="classID">
                          <option value="0">-- クラスでフィルタ --</option>
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
                    <li class="input-group-select clearfix">
                      <span class="input-group-addon"> <i class="material-icons col-orange">beenhere</i> </span>
                      <div class="form-line">
                        <select class="kodama-icon-select" name="s.status">
                          <option value="-1">-- 在籍狀態でフィルタ --</option>
                          <?php
                          $sql = 'SELECT typeID, typename FROM idconfig WHERE type="status" ORDER BY typeID ASC';
                          $statement = $connection->prepare($sql);
                          $statement->execute();
                          $recordstatus = $statement->fetchAll( PDO::FETCH_OBJ );
                          foreach($recordstatus as $recordstatus): ?>
                          <option value="<?= $recordstatus->typeID ?>"><?= $recordstatus->typename ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </li>
                  </div>
                  <div style="text-align: center;">
                    <button type="submit" class="btn bg-orange waves-effect" href="javascript:void(0);">フィルタ</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
          
          <div style="padding: 0 20px;"><div class="alert-warning col-white" id="message" style="word-wrap: break-word; word-break: break-all;"></div></div>
          <!-- DataTable -->
          <div class="body">
            <div class="table-responsive-lg">
              <table id="mainTable" class="table table-bordered table-striped table-hover dataTable display" cellspacing="0">
                <thead class="col-<?= $KODAMA_THEME_COLOR; ?>">
                  <tr>
                    <th class="col-xs-1 kodama-fillcontrol" style="width: 20px;">
                      <div class="custom-control custom-checkbox text-center" id="checkbox_hc001" style="margin-left: 20px;">
                        <input type="checkbox" id="checkbox_hc1" class="custom-control-input filled-in chk-col-orange"/>
                        <label class="custom-control-label" for="checkbox_hc1" id="checkbox_hl1" style="visibility: hidden;"></label>
                      </div>
                    </th>
                    <td class="details-control details-thead"></td>
                    <th class="col-xs-2">学籍番号</th>
                    <th class="col-xs-2">氏名</th>
                    <th class="col-xs-2">クラス名</th>
                    <th class="col-xs-2">在籍狀態</th>
                    <th class="col-xs-1">性别</th>
                    <th class="col-xs-2">国籍・地域</th>
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
                    <td class="details-control details-tfoot"></td>
                    <th>学籍番号</th>
                    <th>氏名</th>
                    <th>クラス名</th>
                    <th>在籍狀態</th>
                    <th>性别</th>
                    <th>国籍・地域</th>
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
<script src="../style/js/kodama-studenttable.js"></script>