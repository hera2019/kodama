<?php
require_once 'class_data.php';
use NS_Kodama_DB\Class_Data;

$message = 'Param error! Do you choose a student first?';
$mod = GetParam('mod');
$studentID = GetParam('studentID');
$fileID = GetParam('fileID');
if (!empty($mod) && !empty($studentID) && !empty($fileID))
{
  if($mod == 'add')
  {
    $data = GetParam('data');
    if(!empty($data))
    {
      $classdata = new Class_Data($connection);
      $message = $classdata->AddData($studentID, $fileID, $data);

      if($message == '')
      {
        $message = "Save data successfully!";
        echo $message;
        return $message;
      }
    }
  }
  else if($mod == 'update')
  {
    $data = GetParam('data');
    if(!empty($data))
    {
      $dataobj = json_decode($data);
      if($dataobj && $dataobj->student && $dataobj->studentdata) {
        $classdata = new Class_Data($connection);
        $studentmsg = $classdata->UpdateStudent($studentID, $dataobj->student);
        $message = $classdata->UpdateData($studentID, $fileID, json_encode($dataobj->studentdata));

        if($message == '')
        {
          $message = "Update data successfully!";
          echo $message . $studentmsg;
          return $message . $studentmsg;
        }
      }
    }
  }
  else if($mod == 'get')
  {
    $data = '';
    $students = '';
    $classdata = new Class_Data($connection);
    $studentmsg = $classdata->GetStudent($studentID, $students);
    $message = $classdata->GetData($studentID, $fileID, $data);

    if($message == '')
    {
      $message = "Get data successfully!";
    }

    $postdata = [];
    $postdata["message"] = $studentmsg . $message;
    $postdata["student"] = $students;
    $postdata["studentdata"] = $data;
    $postdatastr = json_encode($postdata);
    echo $postdatastr;
    return $postdatastr;
  }
}

echo $message;
return $message;
?>