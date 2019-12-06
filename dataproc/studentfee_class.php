<?php
namespace NS_Kodama_DB;
require_once '../include/include_database.php';
require_once '../include/include_function.php';

use PDO;

class Studentfee_Class
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
	public function AddStudentfee($studentID, $array)
	{
		$message = 'Add record failed!';
		if(empty($array))
		{
      $message = 'Param error!';
      return $message;
    }
    
    $title = 'studentID,';
    $context = $studentID . ',';
    foreach($array as $key => $value) {
      $title .= $key . ',';
      if(($key == 'passportexpiration'
         || strstr($key, 'date'))
        && empty($value)) {
        $context .= 'null,';
      } else {
        $context .= '"' . $value . '"' . ',';
      }
    }
    if(!empty($title) && !empty($context)) {
      $title = substr($title, 0, -1); //去掉最后的逗号
      $context = substr($context, 0, -1); //去掉最后的逗号
    } else {
      $message = 'Param error 2!';
      return $message;
    }
    
    $sql = 'INSERT INTO Studentfee('. $title . ') VALUES('. $context . ')';
    //echo $sql;
    //console_log($sql);
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
	public function UpdateStudentfee($studentID, $sqlarray)
	{
		$message = 'update record failed!';
		if(empty($studentID) || empty($sqlarray))
		{
      $message = 'Param error!';
      return $message;
    }
    if($sqlarray->feetype < 0) {
      return "";
    }
    //数据表 studentfee
    $ID = $sqlarray->ID;
    $array = array();
    $array["feetype"] = $sqlarray->feetype;
    $array["paymentdate"] = $sqlarray->paymentdate == '' ? null : $sqlarray->paymentdate;
    $array["period"] = $sqlarray->period;
    $array["moneyamount"] = $sqlarray->moneyamount;
    $array["expirationdate"] = $sqlarray->expirationdate == '' ? null : $sqlarray->expirationdate;
    $array["teacherID"] = $sqlarray->teacherID;
    $array["description"] = $sqlarray->description;
    //print_r($array);
    if(!empty($iD)) {
      //查询ID是否存在，不存在则返回错误
      $sql = "SELECT ID from Studentfee WHERE ID = :ID";
      $statement = $this->connection->prepare($sql);
      $statement->execute([':ID' => $ID]);
      $record = $statement->fetch( PDO::FETCH_OBJ );
      if ( $record == NULL )
      {
        $ID = "";
      }
    }
    if(empty($ID)) {
      $message = $this->AddStudentfee($studentID, $array);
      //$message = 'This record is not exist!';
      return $message;
    }
    
    $context = 'studentID=' . $studentID . ',';
    foreach($array as $key => $value) {
      if(($key == 'passportexpiration'
         || strstr($key, 'date'))
         && empty($value)) {
        $context .= $key . '=null,';
      } else {
        $context .= $key . '="' . $value . '"' . ',';
      }
    }
    if(!empty($context)) {
      $context = substr($context, 0, -1); //去掉最后的逗号
    } else {
      $message = 'Param error 2!';
      return $message;
    }
    
    $sql = 'UPDATE Studentfee SET ' . $context .' WHERE ID=:ID';
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
	public function GetStudentfee($studentID, &$data)
	{
		if(!empty($studentID))
		{
      $sql = 'SELECT * FROM Studentfee WHERE studentID=:studentID ORDER BY ID DESC';
      $statement = $this->connection->prepare( $sql );
      $statement->execute( [ ':studentID' => $studentID ] );
      $recordstudent = $statement->fetchAll( PDO::FETCH_OBJ );
      $strrecord = '';
      $arrayrecord = array();
      for($i=0; $i<count($recordstudent); $i++ ) {
        $strrecord = get_object_vars($recordstudent[$i]);
        $arrayrecord['record' . ($i + 1)] = $strrecord;
      }
      $data = json_encode($arrayrecord);
      return '';
    }
		return 'Student ID not found. ';
	}
  
	//查询学生信息
	public function QueryStudentfee($Param, &$data)
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
	public function DeleteStudentfee($studentIDs)
	{
		$message = 'Delete record failed!';
		if(empty($studentIDs))
		{
      $message = 'Param error!';
      return $message;
    }
    
    //批量删除DELETE FROM student WHERE ID IN (640,634,633)；
    $sql = 'DELETE FROM Studentfee WHERE ID IN ' . $studentIDs;
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