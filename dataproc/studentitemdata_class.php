<?php
namespace NS_Kodama_DB;
require_once '../include/include_database.php';
require_once '../include/include_function.php';

use PDO;

class StudentItem_Class
{	
	protected $connection;
	protected $item;
	
	public function __construct(PDO $db_connection, string $item = null)//, 
	{
		$this->connection = $db_connection;//& $GLOBALS['connection'];
    $this->item = $item;
	}
	
	public function __destruct()
	{
		// cleanup
	}
  
	//添加一条记录
	public function AddStudentItem($studentID, $array)
	{
    if(empty($this->item)) {
      $message .= 'No student item name!';
      return $message;
    }
		$message = 'Add record failed!';
		if(empty($array))
		{
      $message .= 'Param array empty!';
      return $message;
    }
    
    $title = 'studentID,';
    $context = $studentID . ',';
    foreach($array as $key => $value) {
      if($key == 'recordtime') {
        continue;
      }
      $title .= $key . ',';
      if((strstr($key, 'date') || strstr($key, 'time')) && empty($value)) {
        $context .= 'null,';
      } else {
        $context .= '"' . $value . '"' . ',';
      }
    }
    if(!empty($title) && !empty($context)) {
      $title = substr($title, 0, -1); //去掉最后的逗号
      $context = substr($context, 0, -1); //去掉最后的逗号
    } else {
      $message .= 'Param title or context empty!';
      return $message;
    }
    
    $sql = 'INSERT INTO student' . $this->item . '('. $title . ') VALUES('. $context . ')';
    //echo $sql;
    $statement =  $this->connection->prepare($sql);
    if ($statement->execute()) {
      $message = '';
    }
    else {
      $message = 'Add record database insert failed!';
      $message .= ShowErrorCode($statement);
    }
    
		return $message;
	}

	//更新一条记录
	public function UpdateStudentItem($studentID, $sqlarray)
	{
    if(empty($this->item)) {
      $message .= 'No student item name!';
      return $message;
    }
		$message = 'Update record failed!';
		if(empty($studentID) || empty($sqlarray))
		{
      $message .= 'Param studentID or array empty!';
      return $message;
    }
    
    $ID = $sqlarray->ID;
    //数据表 studentinterview
    $array = get_object_vars($sqlarray);
    unset($array['ID']);
    //print_r($array);
    $first_value = reset($array);
    //echo $first_value . "..." . $ID . "!!!<br>";
    if($first_value == '' || $first_value == null) {
      if(!empty($ID)) {
        $message = $this->DeleteStudentItem($ID);
        return $message;
      }
      return '';
    }
    if(!empty($ID)) {
      //查询ID是否存在，不存在则返回错误
      $sql = 'SELECT ID from student' . $this->item . ' WHERE ID = :ID';
      $statement = $this->connection->prepare($sql);
      $statement->execute([':ID' => $ID]);
      $record = $statement->fetch( PDO::FETCH_OBJ );
      if ( $record == NULL )
      {
        $ID = "";
      }
    }
    if(empty($ID)) {
      $message = $this->AddStudentItem($studentID, $array);
      //$message = 'This record is not exist!';
      return $message;
    }
    
    $context = 'studentID=' . $studentID . ',';
    foreach($array as $key => $value) {
      if((strstr($key, 'date') || strstr($key, 'time')) && empty($value)) {
        $context .= $key . '=null,';
      } else {
        $context .= $key . '="' . $value . '"' . ',';
      }
    }
    if(!empty($context)) {
      $context = substr($context, 0, -1); //去掉最后的逗号
    } else {
      $message = 'Param context empty!';
      return $message;
    }
    
    $sql = 'UPDATE student' . $this->item . ' SET ' . $context .' WHERE ID=:ID';
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
	public function GetStudentItem($studentID, &$data)
	{
    if(empty($this->item)) {
      $message .= 'No student item name!';
      return $message;
    }
		if(!empty($studentID))
		{
      $sql = 'SELECT * FROM student' . $this->item . ' WHERE studentID=:studentID ORDER BY ID ASC';
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
  
	//删除记录
	public function DeleteStudentItem($ID)
	{
    if(empty($this->item)) {
      $message .= 'No student item name!';
      return $message;
    }
		$message = 'Delete record failed!';
		if(empty($ID))
		{
      $message .= 'Param ID empty!';
      return $message;
    }
    
    $sql = 'DELETE FROM student' . $this->item . ' WHERE ID =' . $ID;
    //console_log($sql);
    $statement =  $this->connection->prepare($sql);
    $statement->execute();
    $count = $statement->rowCount();
    if($count > 0) {
      $message = '';
    }
    else {
      $message = 'No record has been deleted!';
      $message .= ShowErrorCode($statement);
    }
		
		return $message;
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
      if(strstr($key, 'date') && empty($value)) {
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
    
    $sql = 'INSERT INTO studentfee('. $title . ') VALUES('. $context . ')';
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
    
    $ID = $sqlarray->ID;
    if($sqlarray->feetype == '' || $sqlarray->feetype < 0) {
      if($ID > 0) {
        $this->DeleteStudentfee($ID);
      }
      return "";
    }
    //数据表 studentfee
    $array = get_object_vars($sqlarray);
    unset($array['ID']);
    //print_r($array);
    if(!empty($ID)) {
      //查询ID是否存在，不存在则返回错误
      $sql = "SELECT ID from studentfee WHERE ID = :ID";
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
      if(strstr($key, 'date') && empty($value)) {
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
    
    $sql = 'UPDATE studentfee SET ' . $context .' WHERE ID=:ID';
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
      $sql = 'SELECT * FROM studentfee WHERE studentID=:studentID ORDER BY ID ASC';
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
  
	//删除记录
	public function DeleteStudentfee($ID)
	{
		$message = 'Delete record failed!';
		if(empty($ID))
		{
      $message = 'Param error!';
      return $message;
    }
    
    $sql = 'DELETE FROM studentfee WHERE ID =' . $ID;
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
	public function AddStudentscore($studentID, $array)
	{
		$message = 'Add record failed!';
		if(empty($array))
		{
      $message .= 'Param error!';
      return $message;
    }
    
    $title = 'studentID,';
    $context = $studentID . ',';
    foreach($array as $key => $value) {
      $title .= $key . ',';
      if(strstr($key, 'date') && empty($value)) {
        $context .= 'null,';
      } else {
        $context .= '"' . $value . '"' . ',';
      }
    }
    if(!empty($title) && !empty($context)) {
      $title = substr($title, 0, -1); //去掉最后的逗号
      $context = substr($context, 0, -1); //去掉最后的逗号
    } else {
      $message .= 'Param error 2!';
      return $message;
    }
    
    $sql = 'INSERT INTO studentscore('. $title . ') VALUES('. $context . ')';
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
	public function UpdateStudentscore($studentID, $sqlarray)
	{
		$message = 'update record failed!';
		if(empty($studentID) || empty($sqlarray))
		{
      $message .= 'Param error!';
      return $message;
    }
    
    $ID = $sqlarray->ID;
    if($sqlarray->examname == '' || $sqlarray->examname == null) {
      if($ID > 0) {
        $this->DeleteStudentscore($ID);
      }
      return "";
    }
    //数据表 studentscore
    $array = get_object_vars($sqlarray);
    unset($array['ID']);
    //print_r($array);
    if(!empty($ID)) {
      //查询ID是否存在，不存在则返回错误
      $sql = "SELECT ID from studentscore WHERE ID = :ID";
      $statement = $this->connection->prepare($sql);
      $statement->execute([':ID' => $ID]);
      $record = $statement->fetch( PDO::FETCH_OBJ );
      if ( $record == NULL )
      {
        $ID = "";
      }
    }
    if(empty($ID)) {
      $message = $this->AddStudentscore($studentID, $array);
      //$message = 'This record is not exist!';
      return $message;
    }
    
    $context = 'studentID=' . $studentID . ',';
    foreach($array as $key => $value) {
      if(strstr($key, 'date') && empty($value)) {
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
    
    $sql = 'UPDATE studentscore SET ' . $context .' WHERE ID=:ID';
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
	public function GetStudentscore($studentID, &$data)
	{
		if(!empty($studentID))
		{
      $sql = 'SELECT * FROM studentscore WHERE studentID=:studentID ORDER BY ID ASC';
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
  
	//删除记录
	public function DeleteStudentscore($ID)
	{
		$message = 'Delete record failed!';
		if(empty($ID))
		{
      $message .= 'Param error!';
      return $message;
    }
    
    $sql = 'DELETE FROM studentscore WHERE ID =' . $ID;
    //console_log($sql);
    $statement =  $this->connection->prepare($sql);
    $statement->execute();
    $count = $statement->rowCount();
    if($count > 0) {
      $message = '';
    }
    else {
      $message = 'No record has been deleted!';
      $message .= ShowErrorCode($statement);
    }
		
		return $message;
	}
  
	//添加一条记录
	public function AddStudentInterview($studentID, $array)
	{
		$message = 'Add record failed!';
		if(empty($array))
		{
      $message .= 'Param error!';
      return $message;
    }
    
    $title = 'studentID,';
    $context = $studentID . ',';
    foreach($array as $key => $value) {
      $title .= $key . ',';
      if(strstr($key, 'date') && empty($value)) {
        $context .= 'null,';
      } else {
        $context .= '"' . $value . '"' . ',';
      }
    }
    if(!empty($title) && !empty($context)) {
      $title = substr($title, 0, -1); //去掉最后的逗号
      $context = substr($context, 0, -1); //去掉最后的逗号
    } else {
      $message .= 'Param error 2!';
      return $message;
    }
    
    $sql = 'INSERT INTO studentinterview('. $title . ') VALUES('. $context . ')';
    //echo $sql;
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
	public function UpdateStudentInterview($studentID, $sqlarray)
	{
		$message = 'update record failed!';
		if(empty($studentID) || empty($sqlarray))
		{
      $message .= 'Param error!';
      return $message;
    }
    
    $ID = $sqlarray->ID;
    if($sqlarray->title == '' || $sqlarray->title == null) {
      if($ID > 0) {
        $this->DeleteStudentInterview($ID);
      }
      return "";
    }
    //数据表 studentinterview
    $array = get_object_vars($sqlarray);
    unset($array['ID']);
    //print_r($array);
    if(!empty($ID)) {
      //查询ID是否存在，不存在则返回错误
      $sql = "SELECT ID from studentinterview WHERE ID = :ID";
      $statement = $this->connection->prepare($sql);
      $statement->execute([':ID' => $ID]);
      $record = $statement->fetch( PDO::FETCH_OBJ );
      if ( $record == NULL )
      {
        $ID = "";
      }
    }
    if(empty($ID)) {
      $message = $this->AddStudentInterview($studentID, $array);
      //$message = 'This record is not exist!';
      return $message;
    }
    
    $context = 'studentID=' . $studentID . ',';
    foreach($array as $key => $value) {
      if(strstr($key, 'date') && empty($value)) {
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
    
    $sql = 'UPDATE studentinterview SET ' . $context .' WHERE ID=:ID';
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
	public function GetStudentInterview($studentID, &$data)
	{
		if(!empty($studentID))
		{
      $sql = 'SELECT * FROM studentinterview WHERE studentID=:studentID ORDER BY ID ASC';
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
  
	//删除记录
	public function DeleteStudentInterview($ID)
	{
		$message = 'Delete record failed!';
		if(empty($ID))
		{
      $message .= 'Param error!';
      return $message;
    }
    
    $sql = 'DELETE FROM studentinterview WHERE ID =' . $ID;
    //console_log($sql);
    $statement =  $this->connection->prepare($sql);
    $statement->execute();
    $count = $statement->rowCount();
    if($count > 0) {
      $message = '';
    }
    else {
      $message = 'No record has been deleted!';
      $message .= ShowErrorCode($statement);
    }
		
		return $message;
	}
}