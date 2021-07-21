<?php
require_once( 'frame.php' );
require_once( '../include/include_database.php' );
?>
<head>
<!-- code by zmq -->
<?php $INCLUDE_STUDENT_INFO = true; ?>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<link href="../style/css/tempusdominus-bootstrap-4.css" rel="stylesheet" />
<link href="../style/css/jquery-editable-select.css" rel="stylesheet" />
</head>

<section class="content">
  <div class="container-fluid">
    <div class="row m-t--60">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php
        if(isset($INCLUDE_STUDENT_INFO) && $INCLUDE_STUDENT_INFO) {
          require_once( '../frame/studentinfo.php' );
        }
        ?>
        <div class="card">
          <div class="kodama-header col-<?= $KODAMA_THEME_COLOR; ?>">
            <h2>作品集<small></small></h2>
            <ul class="header-button">
              <li><a href="javascript:void(0);" onclick="newRecord();">
                <div class="kodama-icon-circle bg-green"> <i class="material-icons">create_new_folder</i> </div>
                <div class="kodama-menu-info">
                  <h4>追加</h4>
                </div>
              </a></li>
              <li><a href="javascript:void(0);" onclick="refreshRecord();">
                <div class="kodama-icon-circle bg-orange"> <i class="material-icons">query_builder</i> </div>
                <div class="kodama-menu-info">
                  <h4>リロード</h4>
                </div>
              </a></li>
              <li><a href="javascript:void(0);" onclick="saveRecord();">
                <div class="kodama-icon-circle bg-light-blue"> <i class="material-icons">save</i> </div>
                <div class="kodama-menu-info">
                  <h4>保存</h4>
                </div>
              </a></li>
            </ul>
          </div>
          <div class="body">
            <table id="mainTable" class="kodama-formtable table table-bordered kodama-formtable-bordered text-center">
              <caption>
                <div  style="padding-left: 2rem;">
                  <div id="message" class="alert-warning align-left col-white" style="line-height: 23px; width: 100%;"><?= $message; ?></div>
                  <div id="xhr_progressgrd" class="progress" style="width: 0%;">
                    <div id="xhr_progress" class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%; display: inline;">
                    </div>
                  </div>
                </div>
              </caption>
              <thead>
                <tr>
                  <th class="col-xs-2 text-center">写真</th>
                  <td class="col-xs-2" colspan="3">
                    <div class="form-line" id="photo_drag" style="width: 100%;">
                      <form id="formphoto" name="formphoto" enctype="multipart/form-data" method="post" action="../plugin/upload/upload.php" />
                      <input hidden="true" type="text" name="photo" id="photo_photo" value="" />
                      <input hidden="true" type="file" size="32" id="photofile" name="photofile" value="" accept="image/*" />
                      <img class="photoimage" id="photoimage" autocomplete="off" alt="写真" height="" width="100%" src="<?= $PHOTO_PATH . 'default/upload.jpg'; ?>" />
                    </div>
                  </td>
                </tr>
                <tr>
                  <th class="col-xs-12" colspan="4" style="height: 10px; background-color: #e9e9e9;"></th>
                </tr>
              </thead>
              <?php
              for($i=1; $i<=24; $i++): ?>
              <tbody id=recordrow<?= $i; ?><?= $i == 1 ? '' : " hidden=\"hidden\""; ?>>
                <tr>
                  <th class="col-xs-2">タイトル</th>
                  <td class="col-xs-2 kodama-fill text-left" colspan="3" id="text_<?= $i; ?>_title"></td>
                </tr>
                <tr>
                  <th class="col-xs-2">日時</th>
                  <td class="col-xs-2 kodama-fillcontrol">
                    <div class="form-group kodama-datetimepicker" id="time_<?= $i; ?>_0011" data-target-input="nearest">
                      <input type="text" id="time_<?= $i; ?>_executiontime" class="form-control datetimepicker-input" data-target="#time_<?= $i; ?>_0011" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <th class="col-xs-2">クラス</th>
                  <th class="col-xs-2 text-left" id="text_<?= $i; ?>_class"><?= isset($StudentInfo) ? $StudentInfo->classname : ''; ?>
                </tr>
                <tr>
                  <th class="col-xs-2" rowspan="2">写真</th>
                  <th class="col-xs-2 text-left" colspan="3">
                    <button type="button" class="btn-imagefile btn btn-info waves-effect" id="btn_<?= $i; ?>_photofile">
                      <i class="material-icons">input</i>
                      <span>Image File</span>
                    </button>
                  <span id="photo_<?= $i; ?>_photofile">Upload an image first on top side. Click the button on left, input image file.</span></th>
                </tr>
                <tr>
                  <th class="col-xs-2" colspan="3" style="width: 100%;">
                    <img id="photo_<?= $i; ?>_image" autocomplete="off" alt="写真" height="100%" width="100%" src="<?= $PHOTO_PATH . 'default/blank.jpg'; ?>" />
                  </th>
                </tr>
                <tr>
                  <th class="col-xs-2">说明</th>
                  <td class="col-xs-2 kodama-fill text-left" colspan="3" id="text_<?= $i; ?>_description" style="vertical-align: top; height: 100px;"></td>
                </tr>
                <tr>
                  <th class="col-xs-2">その他</th>
                  <td class="col-xs-2 kodama-fill text-left" colspan="3" id="text_<?= $i; ?>_other" style="vertical-align: top; height: 100px;"></td>
                </tr>
                <tr>
                  <td class="kodama-fill" id="text_<?= $i; ?>_ID" hidden="hidden"></td>
                  <th class="col-xs-12" colspan="4" style="height: 10px; background-color: #e9e9e9;"></th>
                </tr>
              </tbody>
              <?php endfor; ?>
              <tfoot>
                <tr>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>      
  </div>
</section>

<!-- Editable Table Js -->
<script src="../style/js/mindmup-editabletable.js"></script>
<script src="../style/js/moment-with-locales.js"></script>
<script src="../style/js/tempusdominus-bootstrap-4.js"></script>
<script src="../style/js/kodama-datetimepicker.js"></script>
<script src="../style/js/jquery-editable-select.js"></script>
<script src="../style/js/kodama-table-student-datasave.js"></script>
<script src="../style/js/kodama-photoupload.js"></script>
<script type="text/javascript">
  var _studentrecord = { // 默认值需要与html中同样，重置时使用
    'text_title': '',
    'select_workstype': '',
    'text_class': '<?= isset($StudentInfo) ? $StudentInfo->classname : ''; ?>',
    'time_executiontime': '',
    'photo_photofile': '',
    'text_description': '',
    'text_other': '',
    'time_recordtime': '',
    'text_ID': '',
  };
  g_records.itemname = 'works';
  function datasaveGetCallback (data, studentrecord, recordindex) {
    let el = document.getElementById('photo_' + recordindex + '_image');
    if(el) {
      if(data && data['photofile']) {
        el.removeAttribute("height");
        el.src = kodamafunc.PHOTO_PATH + data['photofile'];
      } else {
        el.setAttribute("height", "100%");
        el.src = kodamafunc.PHOTO_PATH + 'default/blank.jpg';
      }
    }
  }

  $(function () {
    $(".btn-imagefile").click(function(event) {
      var btnid = this.id;
      if(btnid.search(/btn_/) == 0) {
        let n = btnid.search(/_photofile/);
        var index = btnid.substr(4, n - 4);
        var photofile = document.getElementById('photo_photo').value;
        if(photofile == '') {
          return;
        }
        document.getElementById('photo_' + index + '_photofile').innerHTML = photofile;
        document.getElementById('photo_' + index + '_image').removeAttribute("height");
        document.getElementById('photo_' + index + '_image').src = kodamafunc.PHOTO_PATH + photofile;
      }
    });
  });
</script>