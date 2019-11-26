<?php
namespace NS_Kodama_DB;
require_once '../include/include_database.php';
require_once '../include/include_function.php';

use PDO;

class Class_Data
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
	public function AddData($studentID, $fileID, $data)
	{
		$message = 'Add record failed!';
		if(empty($studentID) || empty($fileID) || empty($data))
		{
      $message .= ' Param error!';
      return $message;
    }
    
    //数据表 studentdata：ID, studentID, fileID, data, time
    //查询fileID是否存在，存在则返回错误
    $sql = "SELECT COUNT(*) from studentdata WHERE studentID = :studentID AND fileID = :fileID";
    $statement = $this->connection->prepare($sql);
    $statement->execute([':studentID' => $studentID, ':fileID' => $fileID]);
    $num = $statement->fetchColumn();
    if($num > 0)
    {
      $message = 'This record has been exist!';
      return $message;
    }
    
    $sql = 'INSERT INTO studentdata(studentID, fileID, data) VALUES(:studentID, :fileID, :data)';
    $statement =  $this->connection->prepare($sql);
    if ($statement->execute([':studentID' => $studentID, ':fileID' => $fileID, ':data' => $data])) {
      $message = '';
    }
    else {
      $message = 'Add record failed!';
      ShowErrorCode($statement);
    }
    
		return $message;
	}

	//更新一条记录
	public function UpdateData($studentID, $fileID, $data)
	{
		$message = 'Update record failed!';
		if(empty($studentID) || empty($fileID) || empty($data))
		{
      $message .= ' Param error!';
      return $message;
    }
    
    //数据表 studentdata：ID, studentID, fileID, data, time
    //查询fileID是否存在，不存在则返回错误
    $sql = "SELECT ID from studentdata WHERE studentID = :studentID AND fileID = :fileID";
    $statement = $this->connection->prepare($sql);
    $statement->execute([':studentID' => $studentID, ':fileID' => $fileID]);
    $record = $statement->fetch( PDO::FETCH_OBJ );
    if ( $record == NULL )
    {
      $message = $this->AddData($studentID, $fileID, $data);
      //$message = 'This record is not exist!';
      return $message;
    }
    
    $sql = 'UPDATE studentdata SET data=:data, time=now() WHERE ID=:ID';
    $statement =  $this->connection->prepare($sql);
    if ($statement->execute([':data' => $data, ':ID' => $record->ID])) {
      $message = '';
    }
    else {
      $message = 'Update record failed!';
      ShowErrorCode($statement);
    }
		
		return $message;
	}
  
	//更新学生信息
	public function UpdateStudent($studentID, $students)
	{
		$message = ' Update students base info failed!';
		if(empty($studentID) || empty($students))
		{
      $message .= ' Param error!';
      return $message;
    }
    
    $context = '';
    foreach($students as $key => $value) {
      if($key == 'birthday' && empty($value)) {
        $context .= $key . '=null,';
      } else {
        $context .= $key . '="' . $value . '"' . ',';
      }
    }
    if(!empty($context)) {
      $context = substr($context, 0, -1); //去掉最后的逗号
    } else {
      $message = '';
      return $message;
    }
    
    $sql = 'UPDATE student SET ' . $context .' WHERE ID=:ID';
    $statement =  $this->connection->prepare($sql);
    if ($statement->execute([':ID' => $studentID])) {
      $message = '';
    }
    else {
      $message .= ' Database operate failed!';
      ShowErrorCode($statement);
    }
		
		return $message;
	}
  
	//获取一条记录
	public function GetData($studentID, $fileID, &$data)
	{
		$message = 'Get record failed!';
		if(empty($studentID) || empty($fileID))
		{
      $message .= ' Param error!';
      return $message;
    }
    
    //数据表 studentdata：ID, studentID, fileID, data, time
    //查询FileID是否存在，存在则返回错误
    $sql = "SELECT data from studentdata WHERE studentID = :studentID AND fileID = :fileID";
    $statement = $this->connection->prepare($sql);
    $statement->execute([':studentID' => $studentID, ':fileID' => $fileID]);
    $record = $statement->fetch( PDO::FETCH_OBJ );
    if ( $record != NULL )
    {
      $message = '';
      $data = $record->data;
    }
    else
    {
      $message = 'No file exist! This is an empty new file.';
      return $message;
    }
    
		return $message;
	}
  
	//获取学生信息
	public function GetStudent($studentID, &$students)
	{
		if(!empty($studentID))
		{
      $sql = 'SELECT * FROM student WHERE ID=:ID';
      $statement = $this->connection->prepare( $sql );
      $statement->execute( [ ':ID' => $studentID ] );
      $recordstudent = $statement->fetch( PDO::FETCH_OBJ );
      if ( $recordstudent != NULL ) {
        $students = get_object_vars($recordstudent);
        return '';
      }
      return 'Student base info not found. ';
    }
		return 'Student ID not found. ';
	}
}