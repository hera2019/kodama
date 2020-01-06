<?php
require_once 'checkin_class.php';
use NS_Kodama_DB\Checkin_Class;

class RtInfo {
  public $result = 201; //result:(200:success, 201...:failed)
  public $message = '';
}

$message = 'Checkin info operate failed: param error.';
$rtinfo = new RtInfo();
$rtinfo->result = 201;
$rtinfo->message = $message;
//echo json_encode($rtinfo);

$mod = GetParam('mod');
if (!empty($mod))
{
  if($mod == 'addcheckin')
  {
    $sqlarray = array();
    foreach($_POST as $key => $value) {
      if($key != 'mod' && $key != 'ID') {
        $sqlarray[$key] = $value;
      }
    }    

    if(!empty($sqlarray))
    {
      $classdata = new Checkin_Class($connection);
      $message = $classdata->AddCheckin($sqlarray);

      if($message == '')
      {
        $rtinfo->result = 200;
        $rtinfo->message = "Add Checkin successfully!";
        echo json_encode($rtinfo);
        return $message;
      }
    }
  }
  else if($mod == 'updatecheckin')
  {
    $ID = GetParam('ID');
    if(!empty($ID))
    {
      $sqlarray = array();
      foreach($_POST as $key => $value) {
        if($key != 'mod') {
          $sqlarray[$key] = $value;
        }
      }    

      if(!empty($sqlarray))
      {
        $classdata = new Checkin_Class($connection);
        $message = $classdata->UpdateCheckin($ID, $sqlarray);
        if($message == '')
        {
          $rtinfo->result = 200;
          $rtinfo->message = "Update Checkin info successfully!";
          echo json_encode($rtinfo);
          return $message;
        }
      }
    }
  }
  else if($mod == 'querycheckin')
  {
    $Param = GetParam('param');
    $data = '';
    $cls = new Checkin_Class($connection);
    $message = $cls->QueryCheckin($Param, $data);

    if($message == '')
    {
      $res = json_encode($data, JSON_HEX_QUOT); //引号用\u0022代替
      $res = str_replace('\u0022', '"', $res); //\u0022用引号代替回来
      $res = str_replace('\\\\u', '\\u', $res); //去掉多余的\，汉字显示\\u，用参数JSON_UNESCAPED_UNICODE无效
      $res = str_replace('\\\/', '/', $res); //路径符号：\/替换为/
      $res = str_replace('\/', '/', $res); //路径符号：\/替换为/
      $res = substr($res, 1, -1); //去掉前后的引号
      echo $res;
      return $res;
    }
    $message = '[{"ID":"","studentnumber":"","name":"' . $message . '","classname":""}]';
  }
  else if($mod == 'deletecheckin')
  {
    $Param = GetParam('param');
    if(!empty($Param))
    {
      $obj = json_decode($Param);
      //(640,634,633)
      $recordIDs = '';
      $recordIDArray = [];
      foreach($obj as $key => $value) {
        if($value) {
          $recordIDArray[] = $key;
        }
      }
      if(!empty($recordIDArray)) {
        $recordIDs = '(';
        for($i=0; $i<count($recordIDArray); $i++) {
          if($recordIDArray[$i]) {
            $recordIDs .= $recordIDArray[$i] . ',';
          }
        }
        $recordIDs = rtrim($recordIDs, ",");
        $recordIDs .= ')';
        
        $cls = new Checkin_Class($connection);
        $message = $cls->DeleteCheckin($recordIDs);

        if($message == '')
        {
          $rtinfo->result = 200;
          $rtinfo->message = "Delete record successfully!";
          echo json_encode($rtinfo);
          return $message;
        }
      }
    }
  }
  else if($mod == 'queryclasssituation')
  {
    $Param = GetParam('param');
    $data = '';
    $cls = new Checkin_Class($connection);
    $message = $cls->QueryClasssituation($Param, $data);

    if($message == '')
    {
      $res = json_encode($data, JSON_HEX_QUOT); //引号用\u0022代替
      $res = str_replace('\u0022', '"', $res); //\u0022用引号代替回来
      $res = str_replace('\\\\u', '\\u', $res); //去掉多余的\，汉字显示\\u，用参数JSON_UNESCAPED_UNICODE无效
      $res = str_replace('\\\/', '/', $res); //路径符号：\/替换为/
      $res = str_replace('\/', '/', $res); //路径符号：\/替换为/
      $res = substr($res, 1, -1); //去掉前后的引号
      echo $res;
      return $res;
    }
    $message = '[{"ID":"","classname":"' . $message . '","classnameindex":"","studentnum":""}]';
  }
  else if($mod == 'editclasssituation')
  {
    $IDs = GetParam('IDs');
    $property = GetParam('property');
    $message .= $IDs . " 3<br>";
    if(!empty($IDs))
    {
      $obj = json_decode($IDs);
      //(640,634,633)
      $checkinIDs = '';
      $checkinIDArray = [];
      foreach($obj as $key => $value) {
        if($value) {
          $checkinIDArray[] = $key;
        }
      }
      $message .= "1<br>";
      if(!empty($checkinIDArray)) {
        $checkinIDs = '(';
        for($i=0; $i<count($checkinIDArray); $i++) {
          if($checkinIDArray[$i]) {
            $checkinIDs .= $checkinIDArray[$i] . ',';
          }
        }
        $message .= "2<br>";
        $checkinIDs = rtrim($checkinIDs, ",");
        $checkinIDs .= ')';
        
        $cls = new Checkin_Class($connection);
        $message = $cls->editClasssituation($checkinIDs, $property);

        if($message == '')
        {
          $rtinfo->result = 200;
          $rtinfo->message = "Modified class situation successfully!";
          echo json_encode($rtinfo);
          return $message;
        }
      }
    }
  }
}

$rtinfo->message = $message;
echo json_encode($rtinfo);
return $message;
?>