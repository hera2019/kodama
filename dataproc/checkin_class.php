<?php
namespace NS_Kodama_DB;
require_once '../include/include_database.php';
require_once '../include/include_function.php';

use PDO;

class Checkin_Class
{	
	protected $connection;
	
	public function __construct(PDO $db_connection)//, 
	{
		$this->connection = $db_connection;//& $GLOBALS['connection'];
	}
	
	public function __destruct()
	{
		// cleanup
	}
  	
	//添加一条记录
	public function AddCheckin($sqlarray)
	{
		$message = 'Add record failed!';
		if(empty($sqlarray))
		{
      $message = 'Param error!';
      return $message;
    }
    
    $title = '';
    $context = '';
    $studentIDs = [];
    foreach($sqlarray as $key => $value) {
      if(strstr($key, 'time') && empty($value)) {
        $title .= $key . ',';
        $context .= 'null,';
      } elseif($key == 'ID') { //studentID
        if(!empty($value)) {
          $studentIDs = json_decode($value);
        }
      } else {
        $title .= $key . ',';
        $context .= '"' . $value . '"' . ',';
      }
    }
    $title1 = '';
    $context1 = '';
    if(!empty($title) && !empty($context)) {
      $title .= 'manualmodified';
      $context .= '"1"';
      //$title = substr($title, 0, -1); //去掉最后的逗号
      //$context = substr($context, 0, -1); //去掉最后的逗号
      foreach($studentIDs as $key1 => $value1) {
        if($value1 == true) {
          $title1 = $title . ',studentID';
          $context2 = $context . ',"' . $key1 . '"';

          if(empty($context1)) {
            $context1 .= $context2;
          } else {
            $context1 .= '),(' . $context2;
          }
        }
      }
    } else {
      $message = '';//'Param error 2!';
      return $message;
    }
    
    $sql = 'INSERT INTO attendance('. $title1 . ') VALUES('. $context1 . ')';
    //console_log($sql);
    $message .= $sql;
    $statement =  $this->connection->prepare($sql);
    if ($statement->execute()) {
      $message = '';
    }
    else {
      $message = 'Add record failed!';
      $message .= ShowErrorCode($statement);
    }
    
		return $message;
	}

	//更新一条记录
	public function UpdateCheckin($ID, $sqlarray)
	{
		$message = 'update record failed!';
		if(empty($ID) || empty($sqlarray))
		{
      $message = 'Param error!';
      return $message;
    }
    
    //数据表 Checkin
    //查询ID是否存在，不存在则返回错误
    $sql = "SELECT ID from attendance WHERE ID = :ID";
    $statement = $this->connection->prepare($sql);
    $statement->execute([':ID' => $ID]);
    $record = $statement->fetch( PDO::FETCH_OBJ );
    if ( $record == NULL )
    {
      $message = 'This record is not exist!';
      return $message;
    }
    
    $context = '';
    foreach($sqlarray as $key => $value) {
      if(strstr($key, 'time') && empty($value)) {
        $context .= $key . '=null,';
      } else {
        $context .= $key . '="' . $value . '"' . ',';
      }
    }
    if(!empty($context)) {
      $context .= 'manualmodified="1"';
      //$context = substr($context, 0, -1); //去掉最后的逗号
    } else {
      $message = '';//'Param error 2!';
      return $message;
    }
    
    $sql = 'UPDATE attendance SET ' . $context .' WHERE ID=:ID';
    $statement =  $this->connection->prepare($sql);
    if ($statement->execute([':ID' => $ID])) {
      $message = '';
    }
    else {
      $message = 'Update record failed!';
      $message .= ShowErrorCode($statement);
    }
		
		return $message;
	}
  
	//获取学生信息
	public function GetCheckin($checkinID, &$checkins)
	{
		if(!empty($checkinID))
		{
      $sql = 'SELECT * FROM attendance WHERE ID=:ID';
      $statement = $this->connection->prepare( $sql );
      $statement->execute( [ ':ID' => $checkinID ] );
      $recordcheckin = $statement->fetch( PDO::FETCH_OBJ );
      if ( $recordcheckin != NULL ) {
        $checkins = get_object_vars($recordcheckin);
        return '';
      }
      return 'checkin record not found. ';
    }
		return 'checkin ID not found. ';
	}
  
	//查询学生签到信息
	public function QueryCheckin($Param, &$data)
	{
		$message = 'Query record failed!';
    
    $sql = 'SELECT *, a.ID AS ID, s.studentnumber AS studentnumber, s.name AS name, c.name AS classname FROM attendance AS a LEFT JOIN student AS s ON a.studentID=s.ID LEFT JOIN class AS c ON s.classID=c.ID';
    $sql .= $Param;
    $statement = $this->connection->prepare($sql);
    $statement->execute();
    $record = $statement->fetchAll( PDO::FETCH_OBJ );
    if ( $record != NULL )
    {
      $message = '';
      $all = array();
      foreach($record as $record)
      {
        $all[] = $record;
      }
      $data = json_encode($all);
		  return $message;
    }
    else
    {
      //ShowErrorCode($statement);
      $message = 'Record not found!';
      return $message;
    }
    
		return $message;
	}
  
	//删除记录
	public function DeleteCheckin($recordIDs)
	{
		$message = 'Delete record failed!';
		if(empty($recordIDs))
		{
      $message = 'Param error!';
      return $message;
    }
    
    //批量删除DELETE FROM student WHERE ID IN (640,634,633)；
    $sql = 'DELETE FROM attendance WHERE ID IN ' . $recordIDs;
    //console_log($sql);
    $statement = $this->connection->prepare($sql);
    $statement->execute();
    $count = $statement->rowCount();
    if($count > 0) {
      $message = '';
    }
    else {
      $message = 'No record has been deleted!';
      ShowErrorCode($statement);
    }
    
		return $message;
	}
  
	//查询上课时间信息
	public function QueryClasssituation($Param, &$data)
	{
		$message = 'Query record failed!';
    
    $sql = 'SELECT *, s.ID AS ID, s.classindex AS classindex, LEFT(s.checkinnum * 100 / s.studentnum, 5) AS checkinpercent, s.recordtime AS recordtime, s.manualmodified AS manualmodified, c.name AS classname, s.property AS property FROM situationclass AS s LEFT JOIN class AS c ON s.classID = c.ID'; //CONCAT(left (s.checkinnum * 100 / s.studentnum, 5),"%") AS checkinpercent, 
    $sql .= $Param;
    $statement = $this->connection->prepare($sql);
    $statement->execute();
    $record = $statement->fetchAll( PDO::FETCH_OBJ );
    if ( $record != NULL )
    {
      $message = '';
      $all = array();
      foreach($record as $record)
      {
        $all[] = $record;
      }
      $data = json_encode($all);
		  return $message;
    }
    else
    {
      //ShowErrorCode($statement);
      $message = 'Record not found!';
      return $message;
    }
    
		return $message;
	}
  
	//修改上课时间记录
	public function editClasssituation($checkinIDs, $property)
	{
		$message = 'Update record failed!';
		if(empty($checkinIDs))
		{
      $message = 'Param error!';
      return $message;
    }
    
    //批量删除DELETE FROM checkin WHERE ID IN (640,634,633);
    $sql = 'UPDATE situationclass SET property="' . $property . '", manualmodified="1" WHERE ID IN ' . $checkinIDs;
    //console_log($sql);
    $statement = $this->connection->prepare($sql);
    if($statement->execute()) {
      $message = '';
    }
    else {
      $message = 'No record has been updated!';
      ShowErrorCode($statement);
    }
		
		return $message;
	}  
}