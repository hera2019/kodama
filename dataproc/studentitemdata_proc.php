<?php
require_once 'studentitemdata_class.php';
use NS_Kodama_DB\StudentItem_Class;

class RtInfo {
  public $result = 201; //result:(200:success, 201...:failed)
  public $message = '';
  public $data = '';
}

$message = 'Student fee info operate failed.';
$rtinfo = new RtInfo();
$rtinfo->result = 201;
$rtinfo->message = $message;
//echo json_encode($rtinfo);

$mod = GetParam('mod');
if (!empty($mod))
{
  if($mod == 'updatefee')
  {
    $studentID = GetParam('studentID');
    $data = GetParam('data');
    if(!empty($studentID) && !empty($data))
    {
      $data1 = rawurldecode($data);//+号不能作为参数传递
      $dataobj = json_decode($data1);
      //print_r($dataobj);

      if(!empty($dataobj))
      {
        $classdata = new StudentItem_Class($connection);
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
  else if($mod == 'getfee')
  {
    $studentID = GetParam('studentID');
    if(!empty($studentID))
    {
      $data = '';
      $class = new StudentItem_Class($connection);
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
  else if($mod == 'updatescore')
  {
    $studentID = GetParam('studentID');
    $data = GetParam('data');
    if(!empty($studentID) && !empty($data))
    {
      $data1 = rawurldecode($data);//+号不能作为参数传递
      $dataobj = json_decode($data1);
      //print_r($dataobj);

      if(!empty($dataobj))
      {
        $classdata = new StudentItem_Class($connection);
        foreach($dataobj as $key => $value) {
          $message = $classdata->UpdateStudentscore($studentID, $value);
        }

        if($message == '')
        {
          $rtinfo->result = 200;
          $rtinfo->message = "Update student score successfully!";
          echo json_encode($rtinfo);
          return $message;
        }
      }
    }
  }
  else if($mod == 'getscore')
  {
    $studentID = GetParam('studentID');
    if(!empty($studentID))
    {
      $data = '';
      $class = new StudentItem_Class($connection);
      $message = $class->GetStudentscore($studentID, $data);
      if($message == '')
      {
        $rtinfo->result = 200;
        $rtinfo->message = "Get student score successfully!";
        $rtinfo->data = $data;
        echo json_encode($rtinfo);
        return $message;
      }
    }
  }
  else if($mod == 'updateinterview')
  {
    $studentID = GetParam('studentID');
    $data = GetParam('data');
    if(!empty($studentID) && !empty($data))
    {
      $data1 = rawurldecode($data);//+号不能作为参数传递
      $dataobj = json_decode($data1);
      //print_r($dataobj);

      if(!empty($dataobj))
      {
        $classdata = new StudentItem_Class($connection);
        foreach($dataobj as $key => $value) {
          $message = $classdata->UpdateStudentInterview($studentID, $value);
        }

        if($message == '')
        {
          $rtinfo->result = 200;
          $rtinfo->message = "Update student interview successfully!";
          echo json_encode($rtinfo);
          return $message;
        }
      }
    }
  }
  else if($mod == 'getinterview')
  {
    $studentID = GetParam('studentID');
    if(!empty($studentID))
    {
      $data = '';
      $class = new StudentItem_Class($connection);
      $message = $class->GetStudentInterview($studentID, $data);
      if($message == '')
      {
        $rtinfo->result = 200;
        $rtinfo->message = "Get student interview successfully!";
        $rtinfo->data = $data;
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