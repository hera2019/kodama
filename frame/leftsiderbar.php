<!-- Left Sidebar -->
<aside id="leftsidebar" class="sidebar"> 
  <ul class="nav nav-tabs tab-nav-right" role="tablist">
    <li role="presentation" class="active"><a href="#menus" data-toggle="tab">メニュー</a></li>
    <li role="presentation"><a href="#names" data-toggle="tab">生徒名簿</a></li>
  </ul>
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane fade in active" id="menus">
      <!-- Menu -->
      <div class="menu">
          <ul class="list">
            <!-- <li class="header"></li> -->
            <li> <a href="welcome.php"> <i class="material-icons">home</i> <span>ようこそ</span> </a> </li>
            <li> <a href="javascript:void(0);" class="menu-toggle"> <i class="material-icons">person</i> <span>学生管理</span> </a>
              <ul class="ml-menu">
                <li> <a href="studenttable.php"> <span>学生情報検索</span> </a> </li>
                <li> <a href="studentedit.php"> <span>学生基本情報</span> </a> </li>
                <li> <a href="studentotherinfo.php"> <span>学籍情報</span> </a> </li>
                <li> <a href="studentscore.php"> <span>成績情報</span> </a> </li>
                <li> <a href="studentattend.php"> <span>出席情報</span> </a> </li>
                <li> <a href="studentfee.php"> <span>入金情報</span> </a> </li>
                <li> <a href="studentinterview.php"> <span>面談履歷</span> </a> </li>
                <li> <a href="studentrewards.php"> <span>賞罰</span> </a> </li>
                <li> <a href="studentadvancement.php"> <span>進學·就職</span> </a> </li>
                <li> <a href="studentworks.php"> <span>作品集</span> </a> </li>
                <li> <a href="studentdescription.php"> <span>メモ</span> </a> </li>
                <!-- <li> <a href="javascript:void(0);"> <span>アルバイト情報</span> </a> </li> -->
              </ul>
            </li>
            <li> <a href="javascript:void(0);" class="menu-toggle"> <i class="material-icons">people</i> <span>申請者管理</span> </a>
              <ul class="ml-menu">
                <li> <a href="IB-Admission.php"> <span>入学願書</span> </a> </li>
                <li> <a href="IB-AdmissionAppend.php"> <span>入学追加調書</span> </a> </li>
                <li> <a href="IB-AdmissionResumeAppend.php"> <span>履歴書（追加）</span> </a> </li>
                <li> <a href="IB-AdmissionOtherinfo.php"> <span>入学前その他の情報</span> </a> </li>
              </ul>
            </li>
            <li> <a href="javascript:void(0);" class="menu-toggle"> <i class="material-icons">beenhere</i> <span>出席管理</span> </a>
              <ul class="ml-menu">
                <li> <a href="classsituation.php"> <span>クラスのスケジュール修正</span> </a> </li>
                <li> <a href="checkinrecord.php"> <span>チェックイン記録</span> </a> </li>
                <li> <a href="classtime.php"> <span>課程時間修正</span> </a> </li>
              </ul>
            </li><!--
            <li> <a href="javascript:void(0);" class="menu-toggle"> <i class="material-icons">print</i> <span>集計印刷</span> </a>
              <ul class="ml-menu">
                <li> <a href="welcome4.php"> <span>Welcome4</span> </a> </li>
              </ul>
            </li>
            <li> <a href="javascript:void(0);" class="menu-toggle"> <i class="material-icons">local_printshop</i> <span>名簿印刷</span> </a>
              <ul class="ml-menu">
                <li> <a href="Welcome7.php"> <span>Welcome7</span> </a> </li>
              </ul>
            </li>-->
            <li> <a href="javascript:void(0);" class="menu-toggle"> <i class="material-icons">file_download</i> <span>提出書類</span> </a>
              <ul class="ml-menu">
                <li> <a href="PDFWriteGradeAttend.php"> <span>学業成績及び出席状況証明書</span> </a> </li>
              </ul>
            </li>
            <li> <a href="javascript:void(0);" class="menu-toggle"> <i class="material-icons">account_box</i> <span>マスタ管理</span> </a>
              <ul class="ml-menu">
                <li> <a href="usermanage.php"> <span>ユーザー情報一覧</span> </a> </li>
                <li> <a href="classmanage.php"> <span>クラス情報一覧</span> </a> </li>
              </ul>
            </li>
          </ul>
        </div>
    </div>
    <!-- #Menu -->
    <div role="tabpanel" class="tab-pane fade" id="names">
      <div class="menu">
        <ul class="list">
          <div style="padding-left: 5px; padding-right: 5px;">
            <input type="checkbox" id="chk-select-multi" value="false" class="custom-control-input filled-in chk-col-light-blue"/>
            <label class="custom-control-label" for="chk-select-multi">Multi Select</label>
            <button type="button" class="btn btn-info btn-circle waves-effect waves-circle waves-float" id="tree-refresh" style="float: right;">
              <i class="material-icons">refresh</i>
            </button>
          </div>
          <div style="padding: 5px;">
            <input type="input" id="input-select-node" placeholder="Search..." value="">
          </div>
          <div class="treeview-selectable">
            <div id="treeview-selectable" class=""></div>
          </div>
        </ul>
      </div>
    </div>
  </div>
  <!-- Footer -->
  <div class="legal">
    <div class="copyright"> &copy; 2019 - 2020 <a href="javascript:void(0);">KODAMA Design</a>. </div>
    <div class="version"> <b>Version: </b> 1.0.0 </div>
  </div>
  <!-- #Footer -->
</aside>
<!-- #END# Left Sidebar -->
<script src="../style/js/bootstrap-treeview.js"></script>
<script src="../style/js/kodama-studentclassinfo.js"></script>