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
    foreach($sqlarray as $key => $value) {
      $title .= $key . ',';
      if($key == 'birthday' && empty($value)) {
        $context .= 'null,';
      } else {
        $context .= '"' . $value . '"' . ',';
      }
    }
    if(!empty($title) && !empty($context)) {
      $title = substr($title, 0, -1); //去掉最后的逗号
      $context = substr($context, 0, -1); //去掉最后的逗号
    } else {
      $message = '';//'Param error 2!';
      return $message;
    }
    
    $sql = 'INSERT INTO Checkin('. $title . ') VALUES('. $context . ')';
    //console_log($sql);
    $statement =  $this->connection->prepare($sql);
    if ($statement->execute()) {
      $message = '';
    }
    else {
      $message = 'Add record failed!';
      ShowErrorCode($statement);
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
    $sql = "SELECT ID from Checkin WHERE ID = :ID";
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
      if($key == 'birthday' && empty($value)) {
        $context .= $key . '=null,';
      } else if($key == 'nationalityregion' && $value == -1) {
        $context .= $key . '=null,';
      } else {
        $context .= $key . '="' . $value . '"' . ',';
      }
    }
    if(!empty($context)) {
      $context = substr($context, 0, -1); //去掉最后的逗号
    } else {
      $message = '';//'Param error 2!';
      return $message;
    }
    
    $sql = 'UPDATE Checkin SET ' . $context .' WHERE ID=:ID';
    $statement =  $this->connection->prepare($sql);
    if ($statement->execute([':ID' => $ID])) {
      $message = '';
    }
    else {
      $message = 'Update record failed!';
      ShowErrorCode($statement);
    }
		
		return $message;
	}
  
	//获取学生信息
	public function GetCheckin($checkinID, &$checkins)
	{
		if(!empty($checkinID))
		{
      $sql = 'SELECT * FROM checkin WHERE ID=:ID';
      $statement = $this->connection->prepare( $sql );
      $statement->execute( [ ':ID' => $checkinID ] );
      $recordcheckin = $statement->fetch( PDO::FETCH_OBJ );
      if ( $recordcheckin != NULL ) {
        $checkins = get_object_vars($recordcheckin);
        return '';
      }
      return 'checkin base info not found. ';
    }
		return 'checkin ID not found. ';
	}
  
	//查询学生信息
	public function QueryClasssituation($Param, &$data)
	{
		$message = 'Query record failed!';
    
    $sql = 'SELECT *, s.ID AS ID, s.classindex AS classindex, CONCAT(left (s.checkinnum * 100 / s.studentnum, 5),"%") AS checkinpercent, s.recordtime AS recordtime, s.manualmodified AS manualmodified, c.name AS classname, s.property AS property FROM situationclass AS s LEFT JOIN class AS c ON s.classID = c.ID';
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
	public function DeleteCheckin($checkinIDs)
	{
		$message = 'Delete record failed!';
		if(empty($checkinIDs))
		{
      $message = 'Param error!';
      return $message;
    }
    
    //批量删除DELETE FROM checkin WHERE ID IN (640,634,633)；
    $sql = 'DELETE FROM checkin WHERE ID IN ' . $checkinIDs;
    //console_log($sql);
    $statement =  $this->connection->prepare($sql);
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
}