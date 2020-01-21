<?php
require_once 'studentitemdata_class.php';
use NS_Kodama_DB\ StudentItem_Class;

class RtInfo {
  public $result = 201; //result:(200:success, 201...:failed)
  public $message = '';
  public $data = '';
}

$message = 'Student item info operate failed.';
$rtinfo = new RtInfo();
$rtinfo->result = 201;
$rtinfo->message = $message;
//echo json_encode($rtinfo);

$mod = GetParam( 'mod' );
if ( !empty( $mod ) ) {
  if ( $mod == 'update' ) {
    $item = GetParam( 'item' );
    $studentID = GetParam( 'studentID' );
    $data = GetParam( 'data' );
    if ( !empty( $studentID ) && !empty( $data ) && !empty( $item ) ) {
      $data1 = rawurldecode( $data ); //+号不能作为参数传递
      $dataobj = json_decode( $data1 );
      //print_r($dataobj);

      if ( !empty( $dataobj ) ) {
        $classdata = new StudentItem_Class( $connection, $item );
        foreach ( $dataobj as $key => $value ) {
          $message = $classdata->UpdateStudentItem( $studentID, $value );
        }

        if ( $message == '' ) {
          $rtinfo->result = 200;
          $rtinfo->message = "Update student item successfully!";
          echo json_encode( $rtinfo );
          return $message;
        }
      }
    }
  } elseif ( $mod == 'get' ) {
    $item = GetParam( 'item' );
    $studentID = GetParam( 'studentID' );
    if ( !empty( $studentID ) && !empty( $item ) ) {
      $data = '';
      $class = new StudentItem_Class( $connection, $item );
      $message = $class->GetStudentItem( $studentID, $data );
      if ( $message == '' ) {
        $rtinfo->result = 200;
        $rtinfo->message = "Get student item successfully!";
        $rtinfo->data = $data;
        echo json_encode( $rtinfo );
        return $message;
      }
    }
  }
}

$rtinfo->message = $message;
echo json_encode( $rtinfo );
return $message;
?>