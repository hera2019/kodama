<?php
require_once 'class_data.php';
use NS_Kodama_DB\Class_Data;
require_once 'student_class.php';
use NS_Kodama_DB\Student_Class;

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
        $studentclass = new Student_Class($connection);
        $studentmsg = $studentclass->UpdateStudent($studentID, $dataobj->student);
        $studentclass2 = new Student_Class($connection);
        $studentmsg2 = $studentclass2->UpdateStudent2($studentID, $dataobj->student2);
        $classdata = new Class_Data($connection);
        $message = $classdata->UpdateData($studentID, $fileID, json_encode($dataobj->studentdata));

        if($message == '')
        {
          $message = "Update data successfully!";
          echo $message . $studentmsg . $studentmsg2;
          return $message . $studentmsg . $studentmsg2;
        }
      }
    }
  }
  else if($mod == 'get')
  {
    $data = '';
    $students = '';
    $students2 = '';
    $studentclass = new Student_Class($connection);
    $studentmsg = $studentclass->GetStudent($studentID, $students);
    $studentclass2 = new Student_Class($connection);
    $studentmsg2 = $studentclass2->GetStudent2($studentID, $students2);
    $classdata = new Class_Data($connection);
    $message = $classdata->GetData($studentID, $fileID, $data);

    if($message == '')
    {
      $message = "Get data successfully!";
    }

    $postdata = [];
    $postdata["message"] = $studentmsg . $message;
    $postdata["student"] = $students;
    $postdata["student2"] = $students2;
    $postdata["studentdata"] = $data;
    $postdatastr = json_encode($postdata);
    echo $postdatastr;
    return $postdatastr;
  }
}

echo $message;
return $message;
?>