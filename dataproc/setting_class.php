<?php
namespace NS_Kodama_DB;
require_once '../include/include_database.php';
require_once '../include/include_function.php';

use PDO;

class Setting_Class
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

	//更新Classtime
	public function UpdateClasstime($ID, $sqlarray)
	{
		$message = 'Update classtime record failed!';
		if(empty($ID) || empty($sqlarray))
		{
      $message .= ' Param error!';
      return $message;
    }
    
    //数据表 student
    //查询ID是否存在，不存在则返回错误
    $sql = "SELECT ID from classtime WHERE ID = :ID";
    $statement = $this->connection->prepare($sql);
    $statement->execute([':ID' => $ID]);
    $record = $statement->fetch( PDO::FETCH_OBJ );
    if ( $record == NULL )
    {
      $message = $this->AddClasstime($ID, $sqlarray);
      //$message = 'This record is not exist!';
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
    //计算每个上课时间段
    for($i=1; $i<=$sqlarray['num']; $i++) {
      $time1 = $sqlarray['time' . $i . '1'];
      $time2 = $sqlarray['time' . $i . '2'];
      if(!empty($time1) && !empty($time2)) {
        $lessons = floor((strtotime($time2) - strtotime($time1)) / 60 / 50);//假设40-50分钟一节课，休息10分钟
        $context .= 'lessons' . $i . '="' . $lessons . '"' . ',';
      }
    }
    if(!empty($context)) {
      $context = substr($context, 0, -1); //去掉最后的逗号
    } else {
      $message = '';//'Param error 2!';
      return $message;
    }    
    
    $sql = 'UPDATE classtime SET ' . $context .' WHERE ID=:ID';
    $statement =  $this->connection->prepare($sql);
    if ($statement->execute([':ID' => $ID])) {
      $message = '';
    }
    else {
      $message = 'Update classtime record failed!';
      ShowErrorCode($statement);
    }
		
		return $message;
	}
  
	//添加Classtime
	public function AddClasstime($ID, $sqlarray)
	{
		$message = 'Add classtime record failed!';
		if(empty($sqlarray))
		{
      $message .= ' Param error!';
      return $message;
    }
    
    $title = '';
    $context = '';
    foreach($sqlarray as $key => $value) {
      $title .= $key . ',';
      if(strstr($key, 'time') && empty($value)) {
        $context .= 'null,';
      } else {
        $context .= '"' . $value . '"' . ',';
      }
    }
    //计算每个上课时间段
    for($i=1; $i<=$sqlarray['num']; $i++) {
      $time1 = $sqlarray['time' . $i . '1'];
      $time2 = $sqlarray['time' . $i . '2'];
      if(!empty($time1) && !empty($time2)) {
        $minutes = (strtotime($time2) - strtotime($time1)) / 60;
        $title .= 'minutes' . $i . ',';
        $context .= '"' . $minutes . '"' . ',';
      }
    }
    if(!empty($title) && !empty($context)) {
      $title = substr($title, 0, -1); //去掉最后的逗号
      $context = substr($context, 0, -1); //去掉最后的逗号
    } else {
      $message = '';//'Param error 2!';
      return $message;
    }
    
    $sql = 'INSERT INTO classtime('. $title . ') VALUES('. $context . ')';
    //console_log($sql);
    $statement =  $this->connection->prepare($sql);
    if ($statement->execute()) {
      $message = '';
    }
    else {
      $message = 'Add classtime record failed!';
      //$message .= ShowErrorCode($statement);
    }
    
		return $message;
	}

	//获取学生信息
	public function GetSetting($studentID, &$students)
	{
		if(!empty($studentID))
		{
      $sql = 'SELECT * FROM Setting WHERE ID=:ID';
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
  
	//查询学生信息
	public function QuerySetting($Param, &$data)
	{
		$message = 'Query record failed!';
    
    $sql = 'SELECT *, s.ID AS ID, s.studentnumber AS studentnumber, s.name AS name, s.password AS password, s.nickname AS nickname, s.lastname AS lastname, s.firstname AS firstname, s.birthday AS birthday, s.genderfemale AS genderfemale, s.phonenumber AS phonenumber, s.description AS description, c.name AS classname, t.name AS classteachername, i.typename AS statusname FROM student AS s LEFT JOIN class AS c ON s.classID = c.ID LEFT JOIN teacher AS t ON s.classteacherID = t.ID LEFT JOIN idconfig AS i ON (type="status" AND s.status = i.typeID)';
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
	public function DeleteSetting($studentIDs)
	{
		$message = 'Delete record failed!';
		if(empty($studentIDs))
		{
      $message = 'Param error!';
      return $message;
    }
    
    //批量删除DELETE FROM student WHERE ID IN (640,634,633)；
    $sql = 'DELETE FROM Setting WHERE ID IN ' . $studentIDs;
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