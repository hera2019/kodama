<?php
namespace NS_Kodama_DB;
require_once '../include/include_database.php';
require_once '../include/include_function.php';

use PDO;

class UserManage_Class
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
  
	//添加一条班级记录
	public function AddUser($sqlarray)
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
      if($key == 'ID' || $key == 'confirm') {
        continue;
      } elseif($key == 'birthday' && empty($value)) {
        $title .= $key . ',';
        $context .= 'null,';
      } elseif($key == 'password' && !empty($value)) {
        $title .= $key . ',';
        $context .= 'SHA("' . $value . '")' . ',';
      } else {
        $title .= $key . ',';
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
    
    $sql = 'INSERT INTO usermanage('. $title . ') VALUES('. $context . ')';
    //echo $sql;
    //console_log($sql);
    $statement =  $this->connection->prepare($sql);
    if ($statement->execute()) {
      $message = '';
    }
    else {
      $message = 'Insert record failed!';
      $ret =  ShowErrorCode($statement);
      if(strstr($ret, "key 'email'")) {
        $message = 'Sorry, your email has been registered!';
      } elseif(strstr($ret, "key 'username'")) {
        $message = 'Sorry, your username has been registered!';
      } else {
        $message .= $ret;
      }
    }
    
		return $message;
	}

	//查询班级信息
	public function QueryUser($Param, &$data)
	{
		$message = 'Query record failed!';
    
    $sql = 'SELECT *, usermanage.ID AS ID, usermanage.name AS name, usermanage.description AS description, userrights.name AS userrightsname FROM usermanage LEFT JOIN userrights ON usermanage.userrights = userrights.ID'; //CONCAT(left (s.checkinnum * 100 / s.studentnum, 5),"%") AS checkinpercent, 
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
  
	//修改班级信息
	public function UpdateUser($ID, $sqlarray)
	{
		$message = 'Update record failed!';
		if(empty($ID) || empty($sqlarray))
		{
      $message = 'Param error!';
      return $message;
    }
    
    //数据表 Checkin
    //查询ID是否存在，不存在则返回错误
    $sql = "SELECT ID from usermanage WHERE ID = :ID";
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
      if($key == 'ID' || $key == 'confirm') {
        continue;
      } elseif($key == 'birthday' && empty($value)) {
        $context .= $key . '=null,';
      } elseif($key == 'password') {
        if(empty($value)) {
          continue;
        } else {          
          $context .= $key . '=SHA("' . $value . '")' . ',';
        }
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
    
    $sql = 'UPDATE usermanage SET ' . $context .' WHERE ID=:ID';
    $message .=  $sql;
    $statement =  $this->connection->prepare($sql);
    if ($statement->execute([':ID' => $ID])) {
      $message = '';
    }
    else {
      $message = 'Update record failed!';
      $ret =  ShowErrorCode($statement);
      if(strstr($ret, "key 'email'")) {
        $message = 'Sorry, your email has been registered!';
      } elseif(strstr($ret, "key 'username'")) {
        $message = 'Sorry, your username has been registered!';
      } else {
        $message .= $ret;
      }
    }
		
		return $message;
	}
  
	//删除记录
	public function DeleteUser($ID)
	{
		$message = 'Delete record failed!';
		if(empty($ID))
		{
      $message = 'Param error!';
      return $message;
    }
    
    //批量删除DELETE FROM student WHERE ID IN (640,634,633)；
    $sql = 'DELETE FROM usermanage WHERE ID=:ID';
    //console_log($sql);
    $statement = $this->connection->prepare($sql);
    $statement->execute([':ID' => $ID]);
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
// end of class  
}