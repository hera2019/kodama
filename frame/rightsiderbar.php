<?php
  $KodamaThemeColors = [
    'rose-red' => array('Rose Red', ''),
    'red' => array('Red', ''),
    'pink' => array('Pink', ''),
    'purple' => array('Purple', ''),
    'deep-purple' => array('Deep Purple', ''),
    'indigo' => array('Indigo', ''),
    'blue' => array('Blue', ''),
    'light-blue' => array('Light Blue', ''),
    'cyan' => array('Cyan', ''),
    'teal' => array('Teal', ''),
    'green' => array('Green', ''),
    'light-green' => array('Light Green', ''),
    'lime' => array('Lime', ''),
    'yellow' => array('Yellow', ''),
    'amber' => array('Amber', ''),
    'orange' => array('Orange', ''),
    'deep-orange' => array('Deep Orange', ''),
    'brown' => array('Brown', ''),
    'grey' => array('Grey', ''),
    'blue-grey' => array('Blue Grey', ''),
    'black' => array('Black', ''),
  ];
  $KodamaThemeColors[$KODAMA_THEME_COLOR][1] = "active";
?>
<!-- Right Sidebar -->
<aside id="rightsidebar" class="right-sidebar">
  <ul class="nav nav-tabs tab-nav-right" role="tablist">
    <li role="presentation" class="active"><a href="#skins" data-toggle="tab">SKINS</a></li>
    <li role="presentation"><a href="#settings" data-toggle="tab">SETTINGS</a></li>
  </ul>
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane fade in active in active" id="skins">
      <ul class="kodama-choose-skin">
        <?php foreach($KodamaThemeColors as $key=>$value): ?>
        <li data-theme="<?= $key; ?>" class="<?= $value[1]; ?>">
          <div class="<?= $key; ?>"></div>
          <span><?= $value[0]; ?></span> </li>
        <?php endforeach; ?>
      </ul>
    </div>
    <div role="tabpanel" class="tab-pane fade" id="settings">
      <div class="kodama-settings">
        <p>GENERAL SETTINGS</p>
        <ul class="setting-list">
          <li> <span>Report Panel Usage</span>
            <div class="switch">
              <label>
                <input type="checkbox" checked>
                <span class="lever"></span></label>
            </div>
          </li>
          <li> <span>Email Redirect</span>
            <div class="switch">
              <label>
                <input type="checkbox">
                <span class="lever"></span></label>
            </div>
          </li>
        </ul>
        <p>SYSTEM SETTINGS</p>
        <ul class="setting-list">
          <li> <span>Notifications</span>
            <div class="switch">
              <label>
                <input type="checkbox" checked>
                <span class="lever"></span></label>
            </div>
          </li>
          <li> <span>Auto Updates</span>
            <div class="switch">
              <label>
                <input type="checkbox" checked>
                <span class="lever"></span></label>
            </div>
          </li>
        </ul>
        <p>ACCOUNT SETTINGS</p>
        <ul class="setting-list">
          <li> <span>Offline</span>
            <div class="switch">
              <label>
                <input type="checkbox">
                <span class="lever"></span></label>
            </div>
          </li>
          <li> <span>Location Permission</span>
            <div class="switch">
              <label>
                <input type="checkbox" checked>
                <span class="lever"></span></label>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</aside>
<!-- #END# Right Sidebar -->