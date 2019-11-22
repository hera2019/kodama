<?php require_once( 'frame.php' ); ?><head>
<!-- Bootstrap Material Datetime Picker Css -->
<link href="../style/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />
<link href="../style/css/bootstrap-datetimepicker.css" rel="stylesheet" />
</head>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
          <div class="kodama-header col-<?= $KODAMA_THEME_COLOR; ?>">
            <h2>Data<small>You can edit any columns except header/footer</small></h2>
            <ul class="header-dropdown m-r--5">
              <li>
                <button id="export-btn" class="btn btn-primary" onclick="saveData001();">Export Data</button>
              </li>
              <li class="dropdown"><a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                <ul class="dropdown-menu pull-right">
                  <ul class="menu">
                    <li> <a href="javascript:void(0);" onclick="saveData001();">
                        <div class="kodama-icon-circle bg-green"> <i class="material-icons">save</i> </div>
                        <div class="kodama-menu-info">
                          <h4>Save data</h4>
                        </div>
                      </a> </li>
                  </ul>
                </ul>
              </li>
            </ul>
          </div>
          <div class="body">
            <!--<table id="mainTable" class="table table-bordered">
                <h2>
                        <p class="text-center">標題:模擬課表</p>
                </h2>
                <tr>
                        <th>節次/星期</th>
                        <th>星期一</th>
                        <th>星期二</th>
                        <th>星期三</th>
                        <th>星期四</th>
                        <th>星期五</th>
                </tr>
                <tr>
                        <td>第一節</td>
                        <td rowspan="2">通識教育</td>
                        <td></td>
                        <td></td>
                        <td rowspan="3">微積分</td>
                        <td rowspan="3"></td>
                </tr>
                <tr>
                        <td>第二節</td>
                        <td rowspan="2">離散數學</td>
                        <td rowspan="2">英文(一)</td>
                </tr>
                <tr>
                        <td>第三節</td>
                        <td></td>
                </tr>
                <tr>
                        <td>第四節</td>
                        <td></td>
                        <td rowspan="2">體育(一)</td>
                        <td rowspan="3">計算機概論</td>
                        <td rowspan="3">數位邏輯設計</td>
                        <td></td>
                </tr>
                <tr>
                        <td>第五節</td>
                        <td></td>
                        <td></td>
                </tr>
                <tr>
                        <td>第六節</td>
                        <td></td>
                        <td></td>
                        <td rowspan="2">韻律教學法</td>
                </tr>
                <tr>
                        <td>第七節</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                </tr>
            </table>-->
            <table id="mainTable" class="table table-bordered text-center">
            <caption id="export"></caption>
              <thead>
                <tr>
                  <th class="col-xs-3">Name</th>
                  <th class="col-xs-3">Cost</th>
                  <th class="col-xs-3">Profit</th>
                  <th class="col-xs-3">Fun</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Car</td>
                  <td rowspan="2">100</td>
                  <td>200</td>
                  <td>0</td>
                </tr>
                <tr>
                  <td class="kodama-fill" id="plane01">Bike</td>
                  <td>330</td>
                  <td>240</td>
                </tr>
                <tr>
                  <td>Plane</td>
                  <td>430</td>
                  <td class="kodama-fill" id="plane02">540</td>
                  <td>2</td>
                </tr>
                <tr>
                  <td>Yacht erwe ewefef df</td>
                  <td>
                  </td>
                  <td class="kodama-fillcontrol" id="date02">
                    <div class="form-group">
                      <input type="text" class="kodama-datepicker form-control" placeholder="Please choose a date...">
                    </div>
                  </td>
                  <td>
                  </td>
                </tr>
                <tr>
                  <td>Brain</td>
                  <td class="kodama-fill"></td>
                  <td class="kodama-fillcontrol" id="date04">
                    <div class="form-group">
                      <input type="text" class="kodama-datepicker1 form-control" placeholder="Please choose a date...">
                    </div>
                  </td>
                  <td>3</td>
                </tr>
                <tr>
                  <td>Train</td>
                  <td class="kodama-fill" id="plane03" colspan="2">2</td>
                  <td class="kodama-fill" id="plane04">1</td>
                </tr>
                <tr style="height: 40px;"><!-- style="height: 40px;" -->
                  <td class="kodama-fill" id="plane05"> </td>
                  <td class="kodama-fill" id="plane06"></td>
                  <td class="kodama-fill" id="plane07"></td>
                  <td class="kodama-fillcontrol" id="plane08">
                    <div class="form-group">
                      <input type='text' class="kodama-datepicker2 form-control" placeholder="Please choose a date..."/>
                    </div>
                  </td>
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
</section>

<!-- Editable Table Js -->
<script src="../style/js/mindmup-editabletable.js"></script>
<script src="../style/js/editable-table.js"></script>
<!-- Bootstrap Material Datetime Picker Plugin Js -->
<script src="../style/js/autosize.js"></script>
<script src="../style/js/moment-with-locales.js"></script>
<script src="../style/js/bootstrap-material-datetimepicker.js"></script>
<script src="../style/js/bootstrap-datetimepicker.js"></script>
<script src="../style/js/basic-form-elements.js"></script>