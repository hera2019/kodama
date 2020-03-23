<?php
namespace NS_Kodama_DB;
require_once '../include/include_database.php';
require_once '../include/include_function.php';

use PDO;
class EmptyClass {
}
class Student_Class
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
	public function AddStudent($sqlarray)
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
    
    $sql = 'INSERT INTO student('. $title . ') VALUES('. $context . ')';
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
	public function UpdateStudent($ID, $sqlarray)
	{
		$message = 'update record failed!';
		if(empty($ID) || empty($sqlarray))
		{
      $message = 'Param error!';
      return $message;
    }
    
    //数据表 student
    //查询ID是否存在，不存在则返回错误
    $sql = "SELECT ID from student WHERE ID = :ID";
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
    
    $sql = 'UPDATE student SET ' . $context .' WHERE ID=:ID';
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
	public function GetStudent($studentID, &$data)
	{
		if(!empty($studentID))
		{
      $sql = 'SELECT * FROM student WHERE ID=:ID';
      $statement = $this->connection->prepare( $sql );
      $statement->execute( [ ':ID' => $studentID ] );
      $recordstudent = $statement->fetch( PDO::FETCH_OBJ );
      if ( $recordstudent != NULL ) {
        $data = get_object_vars($recordstudent);
        return '';
      }
      return 'Student base info not found. ';
    }
		return 'Student ID not found. ';
	}
  
	//获取学生备注信息
	public function GetStudentDescription($studentID, &$data)
	{
		if(!empty($studentID))
		{
      $sql = 'SELECT ID, description FROM student WHERE ID=:ID';
      $statement = $this->connection->prepare( $sql );
      $statement->execute( [ ':ID' => $studentID ] );
      $recordstudent = $statement->fetch( PDO::FETCH_OBJ );
      if ( $recordstudent != NULL ) {
        $data = get_object_vars($recordstudent);
        return '';
      }
      return 'Student base info not found. ';
    }
		return 'Student ID not found. ';
	}
  
	//查询学生信息
	public function QueryStudent($Param, &$data)
	{
		$message = 'Query record failed!';
    
    $sql = 'SELECT *, s.ID AS ID, s.studentnumber AS studentnumber, s.name AS name, s.password AS password, s.nickname AS nickname, s.lastname AS lastname, s.firstname AS firstname, s.birthday AS birthday, s.genderfemale AS genderfemale, s.phonenumber AS phonenumber, s.description AS description, c.name AS classname, u.name AS classteachername, i.typename AS statusname FROM student AS s LEFT JOIN class AS c ON s.classID = c.ID LEFT JOIN usermanage AS u ON s.classteacherID = u.ID LEFT JOIN idconfig AS i ON (type="status" AND s.status = i.typeID)';
    $sql .= $Param;
    $statement = $this->connection->prepare($sql);
    $statement->execute();
    $record = $statement->fetchAll( PDO::FETCH_OBJ );
    $time = time();
    if ( $record != NULL )
    {
      $message = '';
      $all = array();
      foreach($record as $record)
      {
        $ID = $record->ID;
        $classID = $record->classID;
        //至今日
        $attend = new EmptyClass();
        $sql2 = 'SELECT sum(attendlesson) AS al, sum(classlesson) AS cl FROM situationmonth WHERE studentID=:ID';
        $statement = $this->connection->prepare($sql2);
        $statement->execute([':ID' => $ID]);
        $record2 = $statement->fetch( PDO::FETCH_OBJ );
        $attend->attendancebeforeday = '';
        if(!empty($record2) && !empty($record2->cl)) {
          $maxclasslessons = $record2->cl;
          if($maxclasslessons > 0) {
            $attend->attendancebeforeday = round($record2->al * 100 / $maxclasslessons) . '%';
          }
        }
        //前月截止
        $thismonth = date( 'Y-m-01', $time );
        $sql3 = 'SELECT sum(attendlesson) AS al, sum(classlesson) AS cl FROM situationmonth WHERE studentID=:ID AND date<:thismonth';
        $statement = $this->connection->prepare($sql3);
        $statement->execute([':ID' => $ID, ':thismonth' => $thismonth]);
        $record3 = $statement->fetch( PDO::FETCH_OBJ );
        $attend->attendancebeforemonth = '';
        if(!empty($record3) && !empty($record3->cl)) {
          $maxclasslessons = $record3->cl;
          if($maxclasslessons > 0) {
            $attend->attendancebeforemonth = round($record3->al * 100 / $maxclasslessons) . '%';
          }
        }

        $obj_merged = (object) array_merge((array)$record, (array)$attend);
        
        $all[] = $obj_merged;
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
  
	//查询班级学生信息树
	public function QueryClassStudentTree($Param, &$data)
	{
		$message = 'Query record failed!';
    
    $sql = 'SELECT *, s.ID AS ID, s.studentnumber AS studentnumber, s.name AS name, s.password AS password, s.nickname AS nickname, s.lastname AS lastname, s.firstname AS firstname, s.birthday AS birthday, s.genderfemale AS genderfemale, s.phonenumber AS phonenumber, s.description AS description, c.name AS classname, u.name AS classteachername, i.typename AS statusname FROM student AS s LEFT JOIN class AS c ON s.classID = c.ID LEFT JOIN usermanage AS u ON s.classteacherID = u.ID LEFT JOIN idconfig AS i ON (type="status" AND s.status = i.typeID) ORDER BY s.classID desc,  s.ID desc';
    $sql .= $Param;
    $statement = $this->connection->prepare($sql);
    $statement->execute();
    $recordstudent = $statement->fetchAll( PDO::FETCH_OBJ );
    $time = time();
    if ( $recordstudent != NULL )
    {
      $message = '';
      $studentclassinfo = array();
      foreach($recordstudent as $record)
      {
        $ID = $record->ID;
        $classID = $record->classID;
        //至今日
        $attend = new EmptyClass();
        $sql2 = 'SELECT sum(attendlesson) AS al, sum(classlesson) AS cl FROM situationmonth WHERE studentID=:ID';
        $statement = $this->connection->prepare($sql2);
        $statement->execute([':ID' => $ID]);
        $record2 = $statement->fetch( PDO::FETCH_OBJ );
        $attend->attendancebeforeday = '';
        if(!empty($record2) && !empty($record2->cl)) {
          $maxclasslessons = $record2->cl;
          if($maxclasslessons > 0) {
            $attend->attendancebeforeday = round($record2->al * 100 / $maxclasslessons) . '%';
          }
        }
        //前月截止
        $thismonth = date( 'Y-m-01', $time );
        $sql3 = 'SELECT sum(attendlesson) AS al, sum(classlesson) AS cl FROM situationmonth WHERE studentID=:ID AND date<:thismonth';
        $statement = $this->connection->prepare($sql3);
        $statement->execute([':ID' => $ID, ':thismonth' => $thismonth]);
        $record3 = $statement->fetch( PDO::FETCH_OBJ );
        $attend->attendancebeforemonth = '';
        if(!empty($record3) && !empty($record3->cl)) {
          $maxclasslessons = $record3->cl;
          if($maxclasslessons > 0) {
            $attend->attendancebeforemonth = round($record3->al * 100 / $maxclasslessons) . '%';
          }
        }

        $obj_merged = (object) array_merge((array)$record, (array)$attend);
        
        $studentclassinfo[] = $obj_merged;
      }
      $data = json_encode($studentclassinfo);
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
	public function DeleteStudent($studentIDs)
	{
		$message = 'Delete record failed!';
		if(empty($studentIDs))
		{
      $message = 'Param error!';
      return $message;
    }
    
    //批量删除DELETE FROM student WHERE ID IN (640,634,633)；
    $sql = 'DELETE FROM student WHERE ID IN ' . $studentIDs;
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
  
	//添加一条记录
	public function AddStudent2($ID, $sqlarray)
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
      $message = '';//'Param error 2!';
      return $message;
    }
    
    $sql = 'INSERT INTO student2('. $title . ') VALUES('. $context . ')';
    //console_log($sql);
    $statement =  $this->connection->prepare($sql);
    if ($statement->execute()) {
      $message = '';
    }
    else {
      $message = 'Add record failed!';
      //$message .= ShowErrorCode($statement);
    }
    
		return $message;
	}

	//更新一条记录
	public function UpdateStudent2($ID, $sqlarray)
	{
		$message = 'update record failed!';
		if(empty($ID) || empty($sqlarray))
		{
      $message = 'Param error!';
      return $message;
    }
    
    //数据表 student
    //查询ID是否存在，不存在则返回错误
    $sql = "SELECT ID from student2 WHERE ID = :ID";
    $statement = $this->connection->prepare($sql);
    $statement->execute([':ID' => $ID]);
    $record = $statement->fetch( PDO::FETCH_OBJ );
    if ( $record == NULL )
    {
      $message = $this->AddStudent2($ID, $sqlarray);
      //$message = 'This record is not exist!';
      return $message;
    }
    
    $context = '';
    foreach($sqlarray as $key => $value) {
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
      $message = '';//'Param error 2!';
      return $message;
    }
    
    $sql = 'UPDATE student2 SET ' . $context .' WHERE ID=:ID';
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
	public function GetStudent2($studentID, &$students)
	{
		if(!empty($studentID))
		{
      $sql = 'SELECT * FROM student2 WHERE ID=:ID';
      $statement = $this->connection->prepare( $sql );
      $statement->execute( [ ':ID' => $studentID ] );
      $recordstudent = $statement->fetch( PDO::FETCH_OBJ );
      if ( $recordstudent != NULL ) {
        $students = get_object_vars($recordstudent);
        return '';
      } else {
        $ret = new EmptyClass();
        $ret->ID = $studentID;
        $students = get_object_vars($ret);
        return '';
      }
      return 'Student other info not found. ';
    }
		return 'Student ID not found. ';
	}
  
	//删除记录
	public function DeleteStudent2($studentIDs)
	{
		$message = 'Delete record failed!';
		if(empty($studentIDs))
		{
      $message = 'Param error!';
      return $message;
    }
    
    //批量删除DELETE FROM student WHERE ID IN (640,634,633)；
    $sql = 'DELETE FROM student2 WHERE ID IN ' . $studentIDs;
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