<?php require_once( 'frame.php' ); ?>
<head>
<style>
.info-box {
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
  height: 160px;
  display: flex;
  cursor: default;
  background-color: #fff;
  position: relative;
  overflow: hidden;
  margin-bottom: 30px;
  cursor: pointer;
  }
  .info-box .icon {
    background-color: #fff;
    display: inline-block;
    text-align: center;
    width: 160px;
  }
    .info-box .icon i {
      color: rgba(0, 0, 0, 0.15);
      font-size: 80px;
      line-height: 160px;
  }
  .info-box .content {
    display: inline-block;
    padding: 7px 20px;
  }
    .info-box .content .text {
      text-align: center;
      font-size: 26px;
      margin-top: 50px;
      color: #555;
  }
    .info-box .content .number {
      font-weight: normal;
      font-size: 26px;
      margin-top: -4px;
      color: #555;
  }
</style>
</head>



<section class="content">
  <div class="container-fluid">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <div class="card">
        <div class="header bg-<?= $KODAMA_THEME_COLOR; ?>">
          <h2>Welcome<small><?php if(!empty($username)) echo $username; ?> <?php if(!empty($useremail)) echo $useremail; ?></small></h2>
        </div>
        <div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12" style="width: 480px; margin: 50px 0 0  50px;">
                <a href="studenttable.php" style="text-decoration: none">
                  <div class="info-box hover-zoom-effect">
                        <div class="icon">
                            <i class="material-icons col-blue">person</i>
                        </div>
                        <div class="content">
                          <div class="text">学生情報検索</div>
                        </div>
                  </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12" style="width: 480px; margin: 50px 0 0  50px;">
                <a href="studentattend.php" style="text-decoration: none">
                  <div class="info-box hover-zoom-effect">
                        <div class="icon">
                            <i class="material-icons col-rose-red">beenhere</i>
                        </div>
                        <div class="content">
                          <div class="text">出席情報</div>
                        </div>
                  </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12" style="width: 480px; margin: 50px 0 0  50px;">
                <a href="studentscore.php" style="text-decoration: none">
                  <div class="info-box hover-zoom-effect">
                        <div class="icon">
                            <i class="material-icons col-green">content_paste</i>
                        </div>
                        <div class="content">
                          <div class="text">成績情報</div>
                        </div>
                  </div>
                </a>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="width: 480px; margin: 50px 0 0  50px;">
                <a href="IB-Admission.php" style="text-decoration: none">
                  <div class="info-box hover-zoom-effect">
                        <div class="icon">
                            <i class="material-icons col-orange">person_outline</i>
                        </div>
                        <div class="content">
                          <div class="text">入学願書</div>
                        </div>
                  </div>
                </a>
            </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script src="../style/js/jquery.sparkline.js"></script>
<script src="../style/js/infobox-2.js"></script>