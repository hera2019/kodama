<?php require_once( 'frame.php' ); ?>
<?php $INCLUDE_STUDENT_INFO = true; ?>
<section class="content">
  <div class="container-fluid">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <?php
      if(isset($INCLUDE_STUDENT_INFO) && $INCLUDE_STUDENT_INFO) {
        require_once( '../frame/studentinfo.php' );
      }
      ?>
      <div class="card">
        <div class="body">
          <div class="kodama-header col-<?= $KODAMA_THEME_COLOR; ?>">
            <h2>本当に申し訳ありません！ページが建設中ですので、バージョンのアップデートをお待ちください。</h2>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php
  return;
?>