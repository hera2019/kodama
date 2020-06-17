<?php
//指定文件编号，使用日期时间
$PDF_FileNo = date('YmdHis', time());
//指定模板和生成文件前缀名称
$PDF_FileName = '在留資格認定証明書交付申請書';
$PDF_StudentName = '学生';
?>
<?php
// check param
$studentID = '';
if(isset($_GET['ID']) && !empty($_GET['ID'])) {
  $studentID = $_GET['ID'];
} elseif ( isset( $_COOKIE[ 'KODAMA_STUDENT_INFO' ] ) && !empty( $_COOKIE[ 'KODAMA_STUDENT_INFO' ] ) ) {
  $StudentInfoString = $_COOKIE[ 'KODAMA_STUDENT_INFO' ];
  $StudentInfo = json_decode($StudentInfoString);
  $studentID = $StudentInfo->studentid;
}
if(empty($studentID)) :?>
<?php $INCLUDE_STUDENT_INFO = true; ?>
<?php require_once( 'frame.php' ); ?>
<section class="content">
  <div class="container-fluid">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <?php
      if(isset($INCLUDE_STUDENT_INFO) && $INCLUDE_STUDENT_INFO) {
        require_once( '../frame/studentinfo.php' );
      }
      ?>
      <div class="card">
        <div class="body">
          <div class="kodama-header col-<?= $KODAMA_THEME_COLOR; ?>">
            <h2>PDF File Build<small class="col-<?= $KODAMA_THEME_COLOR; ?>"><?= (isset($strinfo) && !empty($strinfo) ? $strinfo : 'You must choose a student first. <a href = "../page/studenttable.php">Click here choose a student</a>.') ?></small></h2>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php
  return;
endif;
?>
<?php

function GetStudentData($data, $key) {
  if(empty($data) || empty($key)) {
    return null;
  }
  $data1 = json_decode($data, true);
  return $data1[$key];
}

  //write text
function WriteText($pdf, $textobj, $defaultfontsize) {
  if(empty($textobj->fontsize)) {
    $pdf->SetFont('droidsansfallback', '', $defaultfontsize);
  } else {
    $pdf->SetFont('droidsansfallback', '', $textobj->fontsize);
  }
  
  if($textobj->bmulticell) {
    // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign='M') //T M B
    // 使用\n换行必须双引号
    $pdf->MultiCell($textobj->Width(), $textobj->Height(), $textobj->text, 0, $textobj->align, 0, 0, $textobj->left, $textobj->top, true, 0, false, true, $textobj->Height(), $textobj->valign);
  } else {
    //Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M') // LEFT CENTER RIGHT：L C R //calign整个cell位置停靠
    $pdf->SetXY($textobj->left, $textobj->top);
    $pdf->Cell($textobj->Width(), $textobj->Height(), $textobj->text, 0, 0, $textobj->align, 0, '', 0, false, 'T', $textobj->valign);
  }
}
//============================================================+
/**
 * Modify an PDF document using TCPDF
 * @abstract TCPDF - Example: Include external UTF-8 text file
 */

// Include the main TCPDF library (search for installation path).
require_once('../plugin/pdf/include_tcpdf.php');

// create new PDF document
$pdf = new TCPDI(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
// Add a page from a PDF by file path.
$pdf->setSourceFile('../template/pdf/' . $PDF_FileName . '.pdf');

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/TCPDF/examples/lang/jpn.php')) {
	require_once(dirname(__FILE__).'/TCPDF/examples/lang/jpn.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------
$FONT_DEFAULT_SIZE = 10.5; // default font size
// set default font subsetting mode
$pdf->setFontSubsetting(true);
// set font
$pdf->SetFont('droidsansfallback', '', $FONT_DEFAULT_SIZE);	//完美显示汉字：cid0cs//droidsansfallback//stsongstdlight 
																	//只能部分显示汉字：hysmyeongjostdmedium//kozgopromedium//kozminproregul//msungstdlight//
// add a page
//$pdf->AddPage();
//$pdf->importPDF('PdfTemplate/学业成绩及出席状况证明书-空白.pdf');

// set color for text
$pdf->SetTextColor(0, 0, 0);

// 1 ---------------------------------------------------------
$pdf->AddPage();
$idx = $pdf->importPage(1);
$pdf->useTemplate($idx);

$textarea = array(
  //'No' => new TextArea(23, 18.8, 54, 23, '', false, 'L'),
  //'studentnumber' => new TextArea(32, 44, 58, 48, '', false, 'L'),
  //'period' => new TextArea(27, 49.8, 85, 55, '', false, 'L'),
  //'builddate' => new TextArea(155, 49.8, 191, 55, '', false, 'L'),
  'name' => new TextArea(45, 69, 182, 74),
  'photo' => new TextArea(154.9, 21.5, 184.8, 60.7),
  'nationalityregion' => new TextArea(45, 62, 101, 67),
  'birthdayY' => new TextArea(129, 62, 145, 67),
  'birthdayM' => new TextArea(150, 62, 159.5, 67),
  'birthdayD' => new TextArea(166, 62, 176.5, 67),
  'gendermale' => new TextArea(38, 77, 44, 83),
  'genderfemale' => new TextArea(49, 77, 55, 83),
  'birthplace' => new TextArea(80, 77, 131, 83),
  'maritalYes' => new TextArea(160, 77, 166, 83),
  'maritalNo' => new TextArea(171, 77, 177, 83),
  'occupation' => new TextArea(38, 84, 74, 89),
  'hometown' => new TextArea(110, 84, 182, 89),
  'japanaddress' => new TextArea(53, 90, 182, 96),
  'phone' => new TextArea(53, 97, 97, 102.5),
  'mobile' => new TextArea(129, 97, 177, 102.5),
  'passportnumber' => new TextArea(53, 104, 97, 109),
  'passportexpirationY' => new TextArea(129, 104, 145, 109),
  'passportexpirationM' => new TextArea(151, 104, 159, 109),
  'passportexpirationD' => new TextArea(166, 104, 174, 109),
  'entrypurposeP' => new TextArea(129.4, 132.5, 131.3, 134.4),
  'prevjpentryYes' => new TextArea(66, 182, 72, 188),
  'prevjpentryNo' => new TextArea(74, 182, 80, 188),
  'prevjpentrytimes' => new TextArea(38, 192, 47, 197),
  'prevjpentryY' => new TextArea(83, 192, 95, 197),
  'prevjpentryM' => new TextArea(101, 192, 110, 197),
  'prevjpentryD' => new TextArea(117, 192, 124, 197),
  'prevjpdepartureY' => new TextArea(137, 192, 148, 197),
  'prevjpdepartureM' => new TextArea(154, 192, 163, 197),
  'prevjpdepartureD' => new TextArea(170, 192, 177, 197),
  'jpfamilyYes' => new TextArea(28, 227, 34, 233),
  'jpfamilyNo' => new TextArea(124, 227, 130, 233),
  'jpfamilyrelation' => new TextArea(22, 244, 36, 249),//text_jpfamilyrelation
  'jpfamilyname' => new TextArea(38, 244, 70, 249),//text_jpfamilyname
  'jpfamilybirthday' => new TextArea(72, 244, 86, 249),//time_jpfamilybirthday
  'jpfamilynationality' => new TextArea(87, 244, 101, 249),//text_jpfamilynationality
  'jpfamilylivetogetherYes' => new TextArea(103.5, 244, 109.5, 249),//radio_jpfamilylivetogether
  'jpfamilylivetogetherNo' => new TextArea(109.5, 244, 115.5, 249),//radio_jpfamilylivetogether
  'jpfamilyworkplacename' => new TextArea(118, 244, 150, 249),//text_jpfamilyworkplacename
  'jpfamilyresidencenumber' => new TextArea(152, 244, 188, 249),//text_jpfamilyresidencenumber
  
  //'postcode' => new TextArea(145, 68.7, 196.5, 74.8, '', false, 'L'),
  //'address' => new TextArea(139.6, 75.5, 196.5, 81.7, '', false, 'L'),
  //'residence' => new TextArea(39.6, 82.4, 54.1, 90.3),
  //'residenceperiod' => new TextArea(74.4, 82.4, 96.9, 90.3),
  //'residencedate' => new TextArea(117.4, 82.4, 146.8, 90.3),
  //'course' => new TextArea(169.8, 82.4, 196.5, 90.3),
  //'japanentrydate' => new TextArea(39.6, 90.8, 73.9, 98.8),
  //'schoolentrydate' => new TextArea(104.5, 90.8, 139, 98.8),
  //'scheduledcompletiondate' => new TextArea(162, 90.8, 196.5, 98.8),
  //'residencenumber' => new TextArea(109.5, 99.4, 139, 107.4),
  //'visapermitnumber' => new TextArea(167, 99.4, 196.5, 107.4),
  //'scoretalk' => new TextArea(37.3, 195.8, 59.6, 203.8),
  //'scoreword' => new TextArea(60.1, 195.8, 82.4, 203.8),
  //'scoregrammar' => new TextArea(82.9, 195.8, 105.2, 203.8),
  //'scoreread' => new TextArea(105.8, 195.8, 128, 203.8),
  //'scorewrite' => new TextArea(128.6, 195.8, 150.8, 203.8),
  //'scorelisten' => new TextArea(151.4, 195.8, 173.7, 203.8),
  //'scoresynthesis' => new TextArea(174.2, 195.8, 196.5, 203.8),
  //'description' => new TextArea(37.3, 212.9, 196.5, 244.3, '', true, 'L', 'T'),
  //'schoolinfo' => new TextArea(20, 253, 190, 275, '', true, 'L', 'T'),
);

//数据库操作
$sql = 'SELECT * FROM student WHERE ID=:ID';
$statement = $connection->prepare($sql);
$statement->execute([':ID' => $studentID ]);
$student = $statement->fetch(PDO::FETCH_OBJ);

$sql = 'SELECT *, s.ID AS ID, i.typename AS residencename, i2.typename AS coursename FROM student2 AS s LEFT JOIN idconfig AS i ON i.type="residence" AND i.typeID=s.residence LEFT JOIN idconfig AS i2 ON i2.type="course" AND i2.typeID=s.course WHERE s.ID=:ID';
$statement = $connection->prepare($sql);
$statement->execute([':ID' => $studentID ]);
$student2 = $statement->fetch(PDO::FETCH_OBJ);

$sql = 'SELECT data FROM studentdata WHERE studentID=:ID AND fileID=1 ORDER by ID DESC LIMIT 1';
$statement = $connection->prepare($sql);
$statement->execute([':ID' => $studentID ]);
$studentdata1 = $statement->fetch(PDO::FETCH_OBJ);

$sql = 'SELECT data FROM studentdata WHERE studentID=:ID AND fileID=2 ORDER by ID DESC LIMIT 1';
$statement = $connection->prepare($sql);
$statement->execute([':ID' => $studentID ]);
$studentdata2 = $statement->fetch(PDO::FETCH_OBJ);

foreach($textarea as $key => $textobj) {
  if($key == 'No') {
    $textobj->text = $PDF_FileNo;
  } elseif($key == 'studentnumber' && !empty($student)) {
    $textobj->text = $student->studentnumber;
  } elseif($key == 'period') {
    $time = time();
    $startdate = $time - 2*365*24*3600;
    $enddate = $time;
    if(!empty($student2)) {
      $startdate = strtotime($student2->classstartdate);
      if(empty($startdate) || $time < $startdate) {
        $startdate = $time;
      }
      $enddate = strtotime($student2->graduationdate);
      if(empty($enddate)) {
        $enddate = strtotime($student2->withdrawaldate);
        if(empty($enddate)) {
          $enddate = $time;
        } elseif($time < $enddate) {
          $enddate = $time;
        }
      } elseif($time < $enddate) {
        $enddate = $time;
      }
    }
    $textobj->text = date('Y年m月', $startdate) . ' 至 ' . date('Y年m月', $enddate);
  } elseif($key == 'name' && !empty($student)) {
    $textobj->text = $student->lastname . " " . $student->firstname;
    $PDF_StudentName = $textobj->text;
  } elseif($key == 'photo' && !empty($student) && !empty($student->photo)) {
    $pdf->Rect($textobj->left, $textobj->top, $textobj->Width(), $textobj->Height(), 'F', array(), array(255,255,255));
    $pdf->Image('../data/photo/' . $student->photo, $textobj->left, $textobj->top, $textobj->Width(), $textobj->Height(), '', '', '', false, 300, '', false, false, 0, 'CM', false, false);
  } elseif($key == 'nationalityregion' && !empty($student)) {
    $textobj->text = $student->nationalityregion;
  } elseif($key == 'builddate') {
    $textobj->text = date('Y年m月d日', time());
  } elseif($key == 'furigananame' && !empty($student)) {
    $textobj->text = $student->lastnamefurigana . " " . $student->firstnamefurigana;
  } elseif($key == 'alphabetname' && !empty($student)) {
    $textobj->text = $student->lastnamealphabet . " " . $student->firstnamealphabet;
  } elseif($key == 'genderfemale' && !empty($student)) {
    if($student->genderfemale == 1) {
      $radius = $textobj->Width() / 2.0;
      $pdf->Circle($textobj->left + $radius, $textobj->top + $textobj->Height() / 2, $radius);
    }
  } elseif($key == 'gendermale' && !empty($student)) {
    if($student->genderfemale == 0) {
      $radius = $textobj->Width() / 2.0;
      $pdf->Circle($textobj->left + $radius, $textobj->top + $textobj->Height() / 2, $radius);
    }
  } elseif($key == 'birthdayY' && !empty($student)) {
    if(!empty($student->birthday)) {
      $textobj->text = date('Y', strtotime($student->birthday));
    } else {
      $textobj->text = '';
    }
  } elseif($key == 'birthdayM' && !empty($student)) {
    if(!empty($student->birthday)) {
      $textobj->text = date('m', strtotime($student->birthday));
    } else {
      $textobj->text = '';
    }
  } elseif($key == 'birthdayD' && !empty($student)) {
    if(!empty($student->birthday)) {
      $textobj->text = date('d', strtotime($student->birthday));
    } else {
      $textobj->text = '';
    }
  } elseif($key == 'birthplace' && !empty($studentdata1)) {
      $textobj->text = GetStudentData($studentdata1->data, 'text_birthplace');
  } elseif($key == 'hometown' && !empty($studentdata1)) {
      $textobj->text = GetStudentData($studentdata1->data, 'text_curaddress');
  } elseif($key == 'maritalYes' && !empty($studentdata1)) {
    if(GetStudentData($studentdata1->data, 'radio_married') == 1) {
      $radius = $textobj->Width() / 2.0;
      $pdf->Circle($textobj->left + $radius, $textobj->top + $textobj->Height() / 2, $radius);
    }
  } elseif($key == 'maritalNo' && !empty($studentdata1)) {
    if(GetStudentData($studentdata1->data, 'radio_married') == 0) {
      $radius = $textobj->Width() / 2.0;
      $pdf->Circle($textobj->left + $radius, $textobj->top + $textobj->Height() / 2, $radius);
    }
  } elseif($key == 'passportnumber' && !empty($student2)) {
    $textobj->text = $student2->passportnumber;
  } elseif($key == 'passportexpirationY' && !empty($student2)) {
    if(!empty($student2->passportexpiration)) {
      $textobj->text = date('Y', strtotime($student2->passportexpiration));
    } else {
      $textobj->text = '';
    }
  } elseif($key == 'passportexpirationM' && !empty($student2)) {
    if(!empty($student2->passportexpiration)) {
      $textobj->text = date('m', strtotime($student2->passportexpiration));
    } else {
      $textobj->text = '';
    }
  } elseif($key == 'passportexpirationD' && !empty($student2)) {
    if(!empty($student2->passportexpiration)) {
      $textobj->text = date('d', strtotime($student2->passportexpiration));
    } else {
      $textobj->text = '';
    }
  } elseif($key == 'entrypurposeP') {
    $pdf->Rect($textobj->left, $textobj->top, $textobj->Width(), $textobj->Height(), 'F', array(), array(0,0,0));
  } elseif($key == 'prevjpentryYes' && !empty($studentdata1)) {
    if(GetStudentData($studentdata1->data, 'radio_prevjp') == 1) {
      $radius = $textobj->Width() / 2.0;
      $pdf->Circle($textobj->left + $radius, $textobj->top + $textobj->Height() / 2, $radius);
    }
  } elseif($key == 'prevjpentryNo' && !empty($studentdata1)) {
    if(GetStudentData($studentdata1->data, 'radio_prevjp') == 0) {
      $radius = $textobj->Width() / 2.0;
      $pdf->Circle($textobj->left + $radius, $textobj->top + $textobj->Height() / 2, $radius);
    }
  } elseif($key == 'prevjpentrytimes' && !empty($studentdata1)) {
    $textobj->text = GetStudentData($studentdata1->data, 'text_prevjptimes');
  } elseif($key == 'prevjpentryY' && !empty($studentdata1)) {
    $date1 = GetStudentData($studentdata1->data, 'time_prevjpentry1');
    $date2 = GetStudentData($studentdata1->data, 'time_prevjpentry2');
    if(!empty($date2) && !empty($date1)) {
      if(strtotime($date1) < strtotime($date2)) {
        $date1 = $date2;
      }
    }
    if(!empty($date1)) {
      $textobj->text = date('Y', strtotime($date1));
    } else {
      $textobj->text = '';
    }
  } elseif($key == 'prevjpentryM' && !empty($studentdata1)) {
    $date1 = GetStudentData($studentdata1->data, 'time_prevjpentry1');
    $date2 = GetStudentData($studentdata1->data, 'time_prevjpentry2');
    if(!empty($date2) && !empty($date1)) {
      if(strtotime($date1) < strtotime($date2)) {
        $date1 = $date2;
      }
    }
    if(!empty($date1)) {
      $textobj->text = date('m', strtotime($date1));
    } else {
      $textobj->text = '';
    }
  } elseif($key == 'prevjpentryD' && !empty($studentdata1)) {
    $date1 = GetStudentData($studentdata1->data, 'time_prevjpentry1');
    $date2 = GetStudentData($studentdata1->data, 'time_prevjpentry2');
    if(!empty($date2) && !empty($date1)) {
      if(strtotime($date1) < strtotime($date2)) {
        $date1 = $date2;
      }
    }
    if(!empty($date1)) {
      $textobj->text = date('d', strtotime($date1));
    } else {
      $textobj->text = '';
    }
  } elseif($key == 'prevjpdepartureY' && !empty($studentdata1)) {
    $date1 = GetStudentData($studentdata1->data, 'time_prevjpdeparture1');
    $date2 = GetStudentData($studentdata1->data, 'time_prevjpdeparture2');
    if(!empty($date2) && !empty($date1)) {
      if(strtotime($date1) < strtotime($date2)) {
        $date1 = $date2;
      }
    }
    if(!empty($date1)) {
      $textobj->text = date('Y', strtotime($date1));
    } else {
      $textobj->text = '';
    }
  } elseif($key == 'prevjpdepartureM' && !empty($studentdata1)) {
    $date1 = GetStudentData($studentdata1->data, 'time_prevjpdeparture1');
    $date2 = GetStudentData($studentdata1->data, 'time_prevjpdeparture2');
    if(!empty($date2) && !empty($date1)) {
      if(strtotime($date1) < strtotime($date2)) {
        $date1 = $date2;
      }
    }
    if(!empty($date1)) {
      $textobj->text = date('m', strtotime($date1));
    } else {
      $textobj->text = '';
    }
  } elseif($key == 'prevjpdepartureD' && !empty($studentdata1)) {
    $date1 = GetStudentData($studentdata1->data, 'time_prevjpdeparture1');
    $date2 = GetStudentData($studentdata1->data, 'time_prevjpdeparture2');
    if(!empty($date2) && !empty($date1)) {
      if(strtotime($date1) < strtotime($date2)) {
        $date1 = $date2;
      }
    }
    if(!empty($date1)) {
      $textobj->text = date('d', strtotime($date1));
    } else {
      $textobj->text = '';
    }
  } elseif($key == 'jpfamilyYes' && !empty($studentdata2)) {
    if(!empty(GetStudentData($studentdata2->data, 'text_jpfamilyname'))) {
      $radius = $textobj->Width() / 2.0;
      $pdf->Circle($textobj->left + $radius, $textobj->top + $textobj->Height() / 2, $radius);
    }
  } elseif($key == 'jpfamilyNo' && !empty($studentdata2)) {
    if(empty(GetStudentData($studentdata2->data, 'text_jpfamilyname'))) {
      $radius = $textobj->Width() / 2.0;
      $pdf->Circle($textobj->left + $radius, $textobj->top + $textobj->Height() / 2, $radius);
    }
  } elseif($key == 'jpfamilyrelation' && !empty($studentdata2)) {
    $textobj->text = GetStudentData($studentdata2->data, 'text_jpfamilyrelation');
    $textobj->fontsize = 5.5;
  } elseif($key == 'jpfamilyname' && !empty($studentdata2)) {
    $textobj->text = GetStudentData($studentdata2->data, 'text_jpfamilyname');
    $textobj->fontsize = 5.5;
  } elseif($key == 'jpfamilybirthday' && !empty($studentdata2)) {
    $textobj->text = date('Y年m月d日', GetStudentData($studentdata2->data, 'time_jpfamilybirthday'));
    $textobj->fontsize = 5.5;
  } elseif($key == 'jpfamilynationality' && !empty($studentdata2)) {
    $textobj->text = GetStudentData($studentdata2->data, 'text_jpfamilynationality');
    $textobj->fontsize = 5.5;
  } elseif($key == 'jpfamilylivetogetherYes' && !empty($studentdata2)) {
    if(GetStudentData($studentdata2->data, 'radio_jpfamilylivetogether') == 1) {
      $radius = $textobj->Width() / 2.0;
      $pdf->Circle($textobj->left + $radius, $textobj->top + $textobj->Height() / 2, $radius);
    }
  } elseif($key == 'jpfamilylivetogetherNo' && !empty($studentdata2)) {
    if(GetStudentData($studentdata2->data, 'radio_jpfamilylivetogether') == 0) {
      $radius = $textobj->Width() / 2.0;
      $pdf->Circle($textobj->left + $radius, $textobj->top + $textobj->Height() / 2, $radius);
    }
  } elseif($key == 'jpfamilyworkplacename' && !empty($studentdata2)) {
    $textobj->text = GetStudentData($studentdata2->data, 'text_jpfamilyworkplacename');
    $textobj->fontsize = 5.5;
  } elseif($key == 'jpfamilyresidencenumber' && !empty($studentdata2)) {
    $textobj->text = GetStudentData($studentdata2->data, 'text_jpfamilyresidencenumber');
    $textobj->fontsize = 5.5;
    /*
    */
  } elseif($key == 'postcode') {
    $sql = 'SELECT data FROM studentdata WHERE fileID=4 AND studentID=:ID';
    $statement = $connection->prepare($sql);
    $statement->execute( [ ':ID' => $studentID ] );
    $recordstudentdata4 = $statement->fetch( PDO::FETCH_OBJ );
    if(!empty($recordstudentdata4) && !empty($recordstudentdata4->data)) {
      $objstudentdata4 = json_decode($recordstudentdata4->data);
      $textobj->text = $objstudentdata4->text_postcode;
    }
  } elseif($key == 'address') {
    if(isset($objstudentdata4) && !empty($objstudentdata4)) {
      $textobj->text = $objstudentdata4->text_address;
    } else {
      $sql = 'SELECT data FROM studentdata WHERE fileID=4 AND studentID=:ID';
      $statement = $connection->prepare($sql);
      $statement->execute( [ ':ID' => $studentID ] );
      $recordstudentdata4 = $statement->fetch( PDO::FETCH_OBJ );
      if(!empty($recordstudentdata4) && !empty($recordstudentdata4->data)) {
        $objstudentdata4 = json_decode($recordstudentdata4->data);
        $textobj->text = $objstudentdata4->text_address;
      }
    }
  } elseif($key == 'residence' && !empty($student2)) {
    $textobj->text = $student2->residencename;
  } elseif($key == 'residenceperiod' && !empty($student2)) {
    $str = $student2->residenceperiod;
    $textobj->text = $str;
  } elseif($key == 'residencedate' && !empty($student2)) {
    $textobj->Date($student2->residencedate);
  } elseif($key == 'course' && !empty($student2)) {
    $textobj->text = $student2->coursename;
  } elseif($key == 'japanentrydate' && !empty($student2)) {
    $textobj->Date($student2->japanentrydate);
  } elseif($key == 'schoolentrydate' && !empty($student2)) {
    $textobj->Date($student2->schoolentrydate);
  } elseif($key == 'scheduledcompletiondate' && !empty($student2)) {
    $textobj->Date($student2->scheduledcompletiondate);
  } elseif($key == 'passportnumber' && !empty($student2)) {
    $textobj->text = $student2->passportnumber;
  } elseif($key == 'residencenumber' && !empty($student2)) {
    $textobj->text = $student2->residencenumber;
  } elseif($key == 'visapermitnumber' && !empty($student2)) {
    $textobj->text = $student2->visapermitnumber;
  } elseif($key == 'scoretalk' && !empty($studentscore)) {
    $textobj->text = $studentscore->scoretalk;
  } elseif($key == 'scoreword' && !empty($studentscore)) {
    $textobj->text = $studentscore->scoreword;
  } elseif($key == 'scoregrammar' && !empty($studentscore)) {
    $textobj->text = $studentscore->scoregrammar;
  } elseif($key == 'scoreread' && !empty($studentscore)) {
    $textobj->text = $studentscore->scoreread;
  } elseif($key == 'scorewrite' && !empty($studentscore)) {
    $textobj->text = $studentscore->scorewrite;
  } elseif($key == 'scorelisten' && !empty($studentscore)) {
    $textobj->text = $studentscore->scorelisten;
  } elseif($key == 'scoresynthesis' && !empty($studentscore)) {
    $textobj->text = $studentscore->scoresynthesis;
  } elseif($key == 'description' && !empty($student)) {
    $textobj->text = $student->description;
  } elseif($key == 'schoolinfo') {
    $sql = 'SELECT * FROM school';
    $statement = $connection->prepare($sql);
    $statement->execute();
    $schoolinfo = $statement->fetch(PDO::FETCH_OBJ);
    $pdf->SetFont('droidsansfallback', '', 14);
    $pdf->SetXY($textobj->left, $textobj->top);
    $pdf->Cell($textobj->Width(), $textobj->Height(), $schoolinfo->name, 0, 0, $textobj->align, 0, '', 0, false, 'T', 'T');
    $pdf->SetFont('droidsansfallback', '', $FONT_DEFAULT_SIZE);
    $textobj->top = $textobj->top + 7;
    $textobj->text = $schoolinfo->postcode;
    $textobj->text .= "\n" . $schoolinfo->address;
    $textobj->text .= "\n" . $schoolinfo->contact;
  }
  
  //write text
  WriteText($pdf, $textobj, $FONT_DEFAULT_SIZE);
}

// 2 ---------------------------------------------------------
$pdf->AddPage();
$idx = $pdf->importPage(2);
$pdf->useTemplate($idx);

// 3 ---------------------------------------------------------
$pdf->AddPage();
$idx = $pdf->importPage(3);
$pdf->useTemplate($idx);

// 4 ---------------------------------------------------------
$pdf->AddPage();
$idx = $pdf->importPage(4);
$pdf->useTemplate($idx);

$textarea = array(
  'checkbox_returncountry' => new TextArea(80.6, 107.5, 83.2, 110.1), //
  'checkbox_furtherjpstudy' => new TextArea(24.3, 107.5, 26.9, 110.1), //
  'checkbox_getjpjob' => new TextArea(24.3, 116.9, 26.9, 119.5), //
  'checkbox_otherplan' => new TextArea(80.6, 116.9, 83.2, 119.5), //
  'text_otherplan' => new TextArea(100, 116, 182, 120, '', false, 'L'), //
);

foreach($textarea as $key => $textobj) {
  if($key == 'No') {
  } elseif($key == 'checkbox_returncountry' && !empty($studentdata1)) {
    if(GetStudentData($studentdata1->data, 'checkbox_returncountry')) {
      $pdf->Rect($textobj->left, $textobj->top, $textobj->Width(), $textobj->Height(), 'F', array(), array(0,0,0));
    }
  } elseif($key == 'checkbox_furtherjpstudy' && !empty($studentdata1)) {
    if(GetStudentData($studentdata1->data, 'checkbox_furtherjpstudy')) {
      $pdf->Rect($textobj->left, $textobj->top, $textobj->Width(), $textobj->Height(), 'F', array(), array(0,0,0));
    }
  } elseif($key == 'checkbox_getjpjob' && !empty($studentdata1)) {
    if(GetStudentData($studentdata1->data, 'checkbox_getjpjob')) {
      $pdf->Rect($textobj->left, $textobj->top, $textobj->Width(), $textobj->Height(), 'F', array(), array(0,0,0));
    }
  } elseif($key == 'checkbox_otherplan' && !empty($studentdata1)) {
    if(GetStudentData($studentdata1->data, 'checkbox_otherplan')) {
      $pdf->Rect($textobj->left, $textobj->top, $textobj->Width(), $textobj->Height(), 'F', array(), array(0,0,0));
    }
  } elseif($key == 'text_otherplan' && !empty($studentdata1)) {
    $textobj->text = GetStudentData($studentdata1->data, 'text_otherplan');
  }
  
  //write text
  WriteText($pdf, $textobj, $FONT_DEFAULT_SIZE);
}

// 5 ---------------------------------------------------------
$pdf->AddPage();
$idx = $pdf->importPage(5);
$pdf->useTemplate($idx);

// 6 ---------------------------------------------------------
$pdf->AddPage();
$idx = $pdf->importPage(6);
$pdf->useTemplate($idx);
// ---------------------------------------------------------

//PDF filename build
$outfilename = $PDF_StudentName . '_' . $PDF_FileName . '_' . $PDF_FileNo . '.pdf';
ob_end_clean();
//Close and output PDF document
$pdf->Output($outfilename, 'I');//

//============================================================+
// END OF FILE
//============================================================+
