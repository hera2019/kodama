<?php
require_once 'student_class.php';
use NS_Kodama_DB\Student_Class;

class RtInfo {
  public $result = 201; //result:(200:success, 201...:failed)
  public $message = '';
}

$message = 'Student info operate failed: param error.';
$rtinfo = new RtInfo();
$rtinfo->result = 201;
$rtinfo->message = $message;
//echo json_encode($rtinfo);

$mod = GetParam('mod');
if (!empty($mod))
{
  if($mod == 'add')
  {
    $sqlarray = array();
    foreach($_POST as $key => $value) {
      if($key != 'mod' && $key != 'ID') {
        $sqlarray[$key] = $value;
      }
    }    

    if(!empty($sqlarray))
    {
      $classdata = new Student_Class($connection);
      $message = $classdata->AddStudent($sqlarray);

      if($message == '')
      {
        $rtinfo->result = 200;
        $rtinfo->message = "Add student successfully!";
        echo json_encode($rtinfo);
        return $message;
      }
    }
  }
  else if($mod == 'update')
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
        $classdata = new Student_Class($connection);
        $message = $classdata->UpdateStudent($ID, $sqlarray);

        if($message == '')
        {
          $rtinfo->result = 200;
          $rtinfo->message = "Update student info successfully!";
          echo json_encode($rtinfo);
          return $message;
        }
      }
    }
  }
  else if($mod == 'updatedescription')
  {
    $ID = GetParam('ID');
    if(!empty($ID))
    {
      $description = GetParam('description');
      $sqlarray = array();
      $sqlarray['description'] = $description;
      if(!empty($sqlarray))
      {
        $classdata = new Student_Class($connection);
        $message = $classdata->UpdateStudent($ID, $sqlarray);

        if($message == '')
        {
          $rtinfo->result = 200;
          $rtinfo->message = "Update student description successfully!";
          echo json_encode($rtinfo);
          return $message;
        }
      }
    }
  }
  else if($mod == 'query')
  {
    $Param = GetParam('param');
    $data = '';
    $cls = new Student_Class($connection);
    $message = $cls->QueryStudent($Param, $data);
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
  else if($mod == 'querytree')
  {
    $Param = GetParam('param');
    $data = '';
    $cls = new Student_Class($connection);
    $message = $cls->QueryClassStudentTree($Param, $data);
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
  else if($mod == 'delete')
  {
    $Param = GetParam('param');
    if(!empty($Param))
    {
      $obj = json_decode($Param);
      //(640,634,633)
      $studentIDs = '';
      $studentIDArray = [];
      foreach($obj as $key => $value) {
        if($value) {
          $studentIDArray[] = $key;
        }
      }
      if(!empty($studentIDArray)) {
        $studentIDs = '(';
        for($i=0; $i<count($studentIDArray); $i++) {
          if($studentIDArray[$i]) {
            $studentIDs .= $studentIDArray[$i] . ',';
          }
        }
        $studentIDs = rtrim($studentIDs, ",");
        $studentIDs .= ')';
        
        $cls = new Student_Class($connection);
        $message = $cls->DeleteStudent($studentIDs);

        if($message == '')
        {
          $rtinfo->result = 200;
          $rtinfo->message = "Delete student successfully!";
          echo json_encode($rtinfo);
          return $message;
        }
      }
    }
  }
  else if($mod == 'update2')
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
        $classdata = new Student_Class($connection);
        $message = $classdata->UpdateStudent2($ID, $sqlarray);

        if($message == '')
        {
          $rtinfo->result = 200;
          $rtinfo->message = "Update student info successfully!";
          echo json_encode($rtinfo);
          return $message;
        }
      }
    }
  }
}

echo json_encode($rtinfo);
return $message;
?>