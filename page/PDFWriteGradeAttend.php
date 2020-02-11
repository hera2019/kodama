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
$pdf->AddPage();
$pdf->setSourceFile('../template/pdf/学業成績及び出席状況証明書.pdf');
$idx = $pdf->importPage(1);
$pdf->useTemplate($idx);
/**
$pdfdata = file_get_contents('PdfTemplate/Test02.pdf'); // Simulate only having raw data available.
$pagecount = $pdf->setSourceData($pdfdata);
for ($i = 1; $i <= $pagecount; $i++) {
	$tplidx = $pdf->importPage($i);
	$pdf->AddPage();
	$pdf->useTemplate($tplidx);
}*/
/**
 * // set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 008');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 008', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
*/
// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/TCPDF/examples/lang/jpn.php')) {
	require_once(dirname(__FILE__).'/TCPDF/examples/lang/jpn.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);
// set font
$pdf->SetFont('droidsansfallback', '', 10.5);	//完美显示汉字：cid0cs//droidsansfallback//stsongstdlight 
																	//只能部分显示汉字：hysmyeongjostdmedium//kozgopromedium//kozminproregul//msungstdlight//
// add a page
//$pdf->AddPage();
//$pdf->importPDF('PdfTemplate/学业成绩及出席状况证明书-空白.pdf');

// set color for text
$pdf->SetTextColor(0, 0, 0);

$textarea = array(
  'No' => new TextArea(23, 18.8, 39, 23, '202001110001', false, 'L'),
  'studentnumber' => new TextArea(32, 44, 58, 48, '', false, 'L'),
  'period' => new TextArea(27, 49.8, 85, 55, '', false, 'L'),
  'builddate' => new TextArea(155, 49.8, 191, 55, '', false, 'L'),
  'name' => new TextArea(39.6, 56.2, 73.9, 68.2),
  'nationalityregion' => new TextArea(89.6, 56.2, 104, 68.2),
  'birthday' => new TextArea(139.6, 56.2, 196.5, 68.2),
  'furigananame' => new TextArea(39.6, 68.7, 73.9, 74.8),
  'alphabetname' => new TextArea(39.6, 75.5, 73.9, 81.7),
  'genderfemale' => new TextArea(89.6, 68.7, 104, 81.7),
  'postcode' => new TextArea(145, 68.7, 196.5, 74.8, '', false, 'L'),
  'address' => new TextArea(139.6, 75.5, 196.5, 81.7, '', false, 'L'),
  'residence' => new TextArea(39.6, 82.4, 54.1, 90.3),
  'residenceperiod' => new TextArea(74.4, 82.4, 96.9, 90.3),
  'residencedate' => new TextArea(117.4, 82.4, 146.8, 90.3),
  'course' => new TextArea(169.8, 82.4, 196.5, 90.3),
  'japanentrydate' => new TextArea(39.6, 90.8, 73.9, 98.8),
  'schoolentrydate' => new TextArea(104.5, 90.8, 139, 98.8),
  'scheduledcompletiondate' => new TextArea(162, 90.8, 196.5, 98.8),
  'passportnumber' => new TextArea(47.1, 99.4, 73.9, 107.4),
  'residencenumber' => new TextArea(109.5, 99.4, 139, 107.4),
  'visapermitnumber' => new TextArea(167, 99.4, 196.5, 107.4),
  'scoretalk' => new TextArea(37.3, 195.8, 59.6, 203.8),
  'scoreword' => new TextArea(60.1, 195.8, 82.4, 203.8),
  'scoregrammar' => new TextArea(82.9, 195.8, 105.2, 203.8),
  'scoreread' => new TextArea(105.8, 195.8, 128, 203.8),
  'scorewrite' => new TextArea(128.6, 195.8, 150.8, 203.8),
  'scorelisten' => new TextArea(151.4, 195.8, 173.7, 203.8),
  'scoresynthesis' => new TextArea(174.2, 195.8, 196.5, 203.8),
  'description' => new TextArea(37.3, 212.9, 196.5, 244.3, '', true, 'L', 'T'),
  'schoolinfo' => new TextArea(20, 253, 190, 275, '', true, 'L', 'T'),
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
$sql = 'SELECT * FROM studentscore WHERE studentID=:ID ORDER by ID DESC LIMIT 1';
$statement = $connection->prepare($sql);
$statement->execute([':ID' => $studentID ]);
$studentscore = $statement->fetch(PDO::FETCH_OBJ);
foreach($textarea as $key => $textobj) {
  if($key == 'No') {
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
      $textobj->text = '女';
    } else if($student->genderfemale == 0) {
      $textobj->text = '男';
    }
  } elseif($key == 'birthday' && !empty($student)) {
    $textobj->Date($student->birthday);
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
    /*$str1 = explode('年', $str, 2);
    $stryear = $str1[0];
    $str2 = $str1[1];
    $str3 = explode('ヶ月', $str2, -1);
    $strmonth = $str3[0];
    if($stryear >0 && $stryear <=9) {
      $stryear = $stryear . '年';
    } else {
      $stryear = '';
    }
    if($strmonth >0 && $strmonth <=12) {
      $strmonth = $strmonth . 'ヶ月';
    } else {
      $strmonth = '';
    }*/
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
    $pdf->SetFont('droidsansfallback', '', 10.5);
    $textobj->top = $textobj->top + 7;
    $textobj->text = $schoolinfo->postcode;
    $textobj->text .= "\n" . $schoolinfo->address;
    $textobj->text .= "\n" . $schoolinfo->contact;
  }
  
  //write text
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

//write attend info
//require_once( '../attend/getstudentmonthattand.php' );
$attendarea = array(
  'year1' => new TextArea(14.5, 117.7, 40.6, 125.7),
  'year2' => new TextArea(14.5, 143.3, 40.6, 151.3),
  'month1' => new TextArea(41.2, 117.7, 52.1, 125.7),
  'month2' => new TextArea(41.2, 143.3, 52.1, 151.3),
  'class1' => new TextArea(41.2, 126.2, 52.1, 134.2),
  'class2' => new TextArea(41.2, 151.8, 52.1, 159.8),
  'attend1' => new TextArea(41.2, 134.8, 52.1, 142.8),
  'attend2' => new TextArea(41.2, 160.4, 52.1, 168.4),
  'classall1' => new TextArea(179.5, 126.2, 196.5, 134.2),
  'classall2' => new TextArea(179.5, 151.8, 196.5, 159.8),
  'attendall1' => new TextArea(179.5, 134.8, 196.5, 142.8),
  'attendall2' => new TextArea(179.5, 160.4, 196.5, 168.4),
  'percent' => new TextArea(168, 170.8, 178, 174.6),
);

require_once '../dataproc/checkin_class.php';
use NS_Kodama_DB\Checkin_Class;

$data = '';
$info = '';
$classdata = new Checkin_Class($connection);
$message = $classdata->GetAttendance($studentID, $data, $info);
if($message == '') {
  if(!empty($data)) {
    $studentattendance = json_decode($data, true);
    //print_r($studentattendance);
  }
}

$time = time();
$classstartdate = NULL;
$classenddate = NULL;
if(isset($studentattendance)) {
  if(!empty($studentattendance['firsttime'])) {
    $classstartdate = $studentattendance['firsttime'];
  }
  if(!empty($studentattendance['lasttime'])) {
    $classenddate = $studentattendance['lasttime'];
  }
}
if(empty($classstartdate)) {
  $classstartdate = date('Y-m-d', $time);
}
if(empty($classenddate)) {
  $classenddate = date('Y-m-d', $time);
}
$firsttime = strtotime($classstartdate);
$lasttime = strtotime($classenddate);
$curtime = strtotime(date('Y-m-d', $time));
if($lasttime - $firsttime > 731*24*3600) { //2年
  $firsttime = strtotime(date('Y-m-d', $lasttime) . ' -2year+1month');
} elseif($firsttime >= $curtime) {
  $firsttime = strtotime(date('Y-m-d', $lasttime) . ' -2year+1month');
}
//strtotime(date('Y-m-d', $time) . ' -2year+1month'); //2020-02-01=>2018-03-01
$year1  = date("Y", $firsttime) + 0;   // 时间1的年份
$month1 = date("m", $firsttime) + 0;   // 时间1的月份
$echo = "";

//课时
$totallessonall = 0;
$totallessonattend = 0;
for($i=0; $i<2; $i++) {
  $year = $year1 + $i;
  $textobj = $attendarea['year' . ($i+1)];
  $textobj->text = $year . '年';
  $pdf->SetXY($textobj->left, $textobj->top);
  $pdf->Cell($textobj->Width(), $textobj->Height(), $textobj->text, 0, 0, $textobj->align, 0, '', 0, false, 'T', $textobj->valign);

  //课时
  $oneylessonall = 0;
  $oneylessonattend = 0;
  for($j=0; $j<12; $j++) {
    $textobj = $attendarea['month' . ($i+1)];
    $month = $month1 + $j;
    if($month > 12) {
      $month = $month - 12;
      $year = $year1 + $i + 1;
    }
    $textobj->text = $month;
    $pdf->SetXY($textobj->left + 11.5 * $j, $textobj->top);
    $pdf->Cell($textobj->Width(), $textobj->Height(), $textobj->text, 0, 0, $textobj->align, 0, '', 0, false, 'T', $textobj->valign);

    if(isset($studentattendance) && isset($studentattendance['months']) && isset($studentattendance['months'][$year]) && isset($studentattendance['months'][$year][$month])) {
      $monthinfo = $studentattendance['months'][$year][$month];
    } else {
      continue;
    }
    //print_r($monthinfo);
    //课时
    $lessonall = empty($monthinfo['lessonall']) ? 0 : $monthinfo['lessonall'];
    $lessonattend = empty($monthinfo['lessonattend']) ? 0 : $monthinfo['lessonattend'];

    if(!empty($lessonall) || !empty($lessonattend)) {
      $textobj = $attendarea['class' . ($i+1)];
      $textobj->text = $lessonall;
      $pdf->SetXY($textobj->left + 11.5 * $j, $textobj->top);
      $pdf->Cell($textobj->Width(), $textobj->Height(), $textobj->text, 0, 0, $textobj->align, 0, '', 0, false, 'T', $textobj->valign);

      $textobj = $attendarea['attend' . ($i+1)];
      $textobj->text = $lessonattend;
      $pdf->SetXY($textobj->left + 11.5 * $j, $textobj->top);
      $pdf->Cell($textobj->Width(), $textobj->Height(), $textobj->text, 0, 0, $textobj->align, 0, '', 0, false, 'T', $textobj->valign);
    }
    $oneylessonall += $lessonall;
    $oneylessonattend += $lessonattend;
  }
  
  if(!empty($oneylessonall) || !empty($oneylessonattend)) {
    $textobj = $attendarea['classall' . ($i+1)];
    $textobj->text = $oneylessonall;
    $pdf->SetXY($textobj->left, $textobj->top);
    $pdf->Cell($textobj->Width(), $textobj->Height(), $textobj->text, 0, 0, $textobj->align, 0, '', 0, false, 'T', $textobj->valign);

    $textobj = $attendarea['attendall' . ($i+1)];
    $textobj->text = $oneylessonattend;
    $pdf->SetXY($textobj->left, $textobj->top);
    $pdf->Cell($textobj->Width(), $textobj->Height(), $textobj->text, 0, 0, $textobj->align, 0, '', 0, false, 'T', $textobj->valign);
  }  
  $totallessonall += $oneylessonall;
  $totallessonattend += $oneylessonattend;
}

$textobj = $attendarea['percent'];
if(empty($totallessonall)) {
  $textobj->text = '  %';
} else {
  $textobj->text = round($totallessonattend / $totallessonall * 100) . "%";
}
$pdf->SetXY($textobj->left, $textobj->top);
$pdf->Cell($textobj->Width(), $textobj->Height(), $textobj->text, 0, 0, $textobj->align, 0, '', 0, false, 'T', $textobj->valign);

// ---------------------------------------------------------
//PDF filename build
$timetxt = date('Ymd_His', time());
$outfilename = 'PDF001_' . $timetxt . '.pdf';
ob_end_clean();
//Close and output PDF document
$pdf->Output($outfilename, 'I');//

//============================================================+
// END OF FILE
//============================================================+
