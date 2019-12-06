<?php
require_once 'studentfee_class.php';
use NS_Kodama_DB\Studentfee_Class;

class RtInfo {
  public $result = 201; //result:(200:success, 201...:failed)
  public $message = '';
  public $data = '';
}

$message = 'Student other info operate failed: param error.';
$rtinfo = new RtInfo();
$rtinfo->result = 201;
$rtinfo->message = $message;
//echo json_encode($rtinfo);

$mod = GetParam('mod');
if (!empty($mod))
{
  if($mod == 'add')
  {
    $ID = GetParam('ID');
    if(!empty($ID))
    {
      $sqlarray = array();
      foreach($_POST as $key => $value) {
        if($key != 'mod') { //Studentfee ID must set, //&& $key != 'ID'
          $sqlarray[$key] = $value;
        }
      }    

      if(!empty($sqlarray))
      {
        $classdata = new Studentfee_Class($connection);
        $message = $classdata->AddStudentfee($ID, $sqlarray);

        if($message == '')
        {
          $rtinfo->result = 200;
          $rtinfo->message = "Add student successfully!";
          echo json_encode($rtinfo);
          return $message;
        }
      }
    }
  }
  else if($mod == 'update')
  {
    $studentID = GetParam('studentID');
    $data = GetParam('data');
    if(!empty($studentID) && !empty($data))
    {
      $dataobj = json_decode($data);
      //print_r($dataobj);

      if(!empty($dataobj))
      {
        $classdata = new Studentfee_Class($connection);
        foreach($dataobj as $key => $value) {
          $message = $classdata->UpdateStudentfee($studentID, $value);
        }

        if($message == '')
        {
          $rtinfo->result = 200;
          $rtinfo->message = "Update student fee successfully!";
          echo json_encode($rtinfo);
          return $message;
        }
      }
    }
  }
  else if($mod == 'get')
  {
    $studentID = GetParam('studentID');
    if(!empty($studentID))
    {
      $data = '';
      $class = new Studentfee_Class($connection);
      $message = $class->GetStudentfee($studentID, $data);
      if($message == '')
      {
        $rtinfo->result = 200;
        $rtinfo->message = "Get student fee successfully!";
        $rtinfo->data = $data;
        echo json_encode($rtinfo);
        return $message;
      }
    }
  }
  else if($mod == 'query')
  {
    $Param = GetParam('param');
    $data = '';
    $cls = new Studentfee_Class($connection);
    $message = $cls->QueryStudentfee($Param, $data);

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
        
        $cls = new Studentfee_Class($connection);
        $message = $cls->DeleteStudentfee($studentIDs);

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
}

$rtinfo->message = $message;
echo json_encode($rtinfo);
return $message;
?>