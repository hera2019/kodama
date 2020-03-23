<?php
require_once 'user_class.php';
use NS_Kodama_DB\UserManage_Class;

class RtInfo {
  public $result = 201; //result:(200:success, 201...:failed)
  public $message = '';
  public $data = '';
  public $info = '';
}

$message = 'Info operate failed: param error.';
$rtinfo = new RtInfo();
$rtinfo->result = 201;
$rtinfo->message = $message;
//echo json_encode($rtinfo);

$mod = GetParam('mod');
if (!empty($mod))
{
  if($mod == 'adduser')
  {
    $sqlarray = array();
    foreach($_POST as $key => $value) {
      if($key != 'mod') {
        $sqlarray[$key] = $value;
      }
    }

    if(!empty($sqlarray))
    {
      $classdata = new UserManage_Class($connection);
      $message = $classdata->AddUser($sqlarray);

      if($message == '')
      {
        $rtinfo->result = 200;
        $rtinfo->message = "Add class successfully!";
        echo json_encode($rtinfo);
        return $message;
      }
    }
  }
  else if($mod == 'queryuser')
  {
    $Param = GetParam('param');
    $data = '';
    $cls = new UserManage_Class($connection);
    $message = $cls->QueryUser($Param, $data);

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
    $message = '[{"ID":"","name":"' . $message . '","classteachername":"","description":""}]';
  }
  else if($mod == 'updateuser')
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
        $classdata = new UserManage_Class($connection);
        $message = $classdata->UpdateUser($ID, $sqlarray);
        if($message == '')
        {
          $rtinfo->result = 200;
          $rtinfo->message = "Update class info successfully!";
          echo json_encode($rtinfo);
          return $message;
        }
      }
    }
  }
  else if($mod == 'deleteuser')
  {
    $ID = GetParam('param');
    if(!empty($ID))
    {
      $cls = new UserManage_Class($connection);
      $message = $cls->DeleteUser($ID);
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

$rtinfo->message = $message;
echo json_encode($rtinfo);
return $message;
?>