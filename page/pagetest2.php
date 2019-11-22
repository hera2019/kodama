<?php require_once( 'frame.php' ); ?>
<section class="content">
  <div class="container-fluid">
    <div class="block-header">
      <h2><font class="col-<?= $KODAMA_THEME_COLOR; ?>">Example</font></h2>
    </div>
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
          <div class="header">
            <h2>EXAMPLE<small>You can edit any columns except header/footer</small></h2>
            <ul class="header-dropdown m-r--5">
              <li class="dropdown"><a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                <ul class="dropdown-menu pull-right">
                  <li><a href="javascript:void(0);">Action</a></li>
                  <li><a href="javascript:void(0);">Another action</a></li>
                  <li><a href="javascript:void(0);">Something else here</a></li>
                </ul>
              </li>
            </ul>
          </div>
          <div class="body">
            <table id="mainTable" class="table table-striped">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Cost</th>
                  <th>Profit</th>
                  <th>Fun</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Car</td>
                  <td>100</td>
                  <td>200</td>
                  <td>0</td>
                </tr>
                <tr>
                  <td>Bike</td>
                  <td>330</td>
                  <td>240</td>
                  <td>1</td>
                </tr>
                <tr>
                  <td>Plane</td>
                  <td>430</td>
                  <td>540</td>
                  <td>3</td>
                </tr>
                <tr>
                  <td>Yacht</td>
                  <td>100</td>
                  <td>200</td>
                  <td>0</td>
                </tr>
                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
              </tbody>
              <tfoot>
                <tr>
                  <th><strong>TOTAL</strong></th>
                  <th>1290</th>
                  <th>1420</th>
                  <th>5</th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="container-fluid">
    <div>
      <p>My Page Test</p>
      <?php
      $url = "http://localhost/kodama/mail/mailtemplate.html";
      echo "<a href=" . $url . ">Please click here to reset your password.</a>";
      ?>
    </div>
  </div>
</section>
<script src="../style/js/mindmup-editabletable.js"></script>
<script src="../style/js/editable-table.js"></script>