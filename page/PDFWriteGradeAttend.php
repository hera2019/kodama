<?php 
// check param
$ID = '';
if(isset($_GET['ID']) && !empty($_GET['ID'])) {
  $ID = $_GET['ID'];
} elseif ( isset( $_COOKIE[ 'KODAMA_STUDENT_INFO' ] ) && !empty( $_COOKIE[ 'KODAMA_STUDENT_INFO' ] ) ) {
  $StudentInfoString = $_COOKIE[ 'KODAMA_STUDENT_INFO' ];
  $StudentInfo = json_decode($StudentInfoString);
  $ID = $StudentInfo->studentid;
}
if(empty($ID)) :?>
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
$pdf->SetFont('droidsansfallback', '', 10);	//完美显示汉字：cid0cs//droidsansfallback//stsongstdlight 
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
);

//数据库操作
$sql = 'SELECT * FROM student WHERE ID=:ID';
$statement = $connection->prepare($sql);
$statement->execute([':ID' => $ID ]);
$student = $statement->fetch(PDO::FETCH_OBJ);
$sql = 'SELECT *, s.ID AS ID, i.typename AS residencename, i2.typename AS coursename FROM student2 AS s LEFT JOIN idconfig AS i ON i.type="residence" AND i.typeID=s.residence LEFT JOIN idconfig AS i2 ON i2.type="course" AND i2.typeID=s.course WHERE s.ID=:ID';
$statement = $connection->prepare($sql);
$statement->execute([':ID' => $ID ]);
$student2 = $statement->fetch(PDO::FETCH_OBJ);
foreach($textarea as $key => $textobj) {
  $txt = $student->nationalityregion;
  if($key == 'No') {
  } elseif($key == 'studentnumber') {
    $textobj->text = $student->studentnumber;
  } elseif($key == 'period') {
    $time = time();
    $sql = 'SELECT classstartdate, graduationdate, withdrawaldate FROM student2 WHERE ID=:ID';
    $statement = $connection->prepare($sql);
    $statement->execute( [ ':ID' => $ID ] );
    $recordclassstartdate = $statement->fetch( PDO::FETCH_OBJ );
    $startdate = $time - 2*365*24*3600;
    $enddate = $time;
    if(!empty($recordclassstartdate)) {
      $startdate = strtotime($recordclassstartdate->classstartdate);
      if(empty($startdate) || $time < $startdate) {
        $startdate = $time;
      }
      $enddate = strtotime($recordclassstartdate->graduationdate);
      if(empty($enddate)) {
        $enddate = strtotime($recordclassstartdate->withdrawaldate);
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
  } elseif($key == 'name') {
    $textobj->text = $student->lastname . " " . $student->firstname;
  } elseif($key == 'nationalityregion') {
    $textobj->text = $student->nationalityregion;
  } elseif($key == 'builddate') {
    $textobj->text = date('Y年m月d日', time());
  } elseif($key == 'furigananame') {
    $textobj->text = $student->lastnamefurigana . " " . $student->firstnamefurigana;
  } elseif($key == 'alphabetname') {
    $textobj->text = $student->lastnamealphabet . " " . $student->firstnamealphabet;
  } elseif($key == 'genderfemale') {
    if($student->genderfemale == 1) {
      $textobj->text = '女';
    } else if($student->genderfemale == 0) {
      $textobj->text = '男';
    }
  } elseif($key == 'birthday') {
    if(!empty($student->birthday)) {
      $textobj->text = date('Y年m月d日', strtotime($student->birthday));
    }
  } elseif($key == 'postcode') {
    $sql = 'SELECT data FROM studentdata WHERE fileID=4 AND studentID=:ID';
    $statement = $connection->prepare($sql);
    $statement->execute( [ ':ID' => $ID ] );
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
      $statement->execute( [ ':ID' => $ID ] );
      $recordstudentdata4 = $statement->fetch( PDO::FETCH_OBJ );
      if(!empty($recordstudentdata4) && !empty($recordstudentdata4->data)) {
        $objstudentdata4 = json_decode($recordstudentdata4->data);
        $textobj->text = $objstudentdata4->text_address;
      }
    }
  } elseif($key == 'residence') {
    $textobj->text = $student2->residencename;
  } elseif($key == 'residenceperiod') {
    $str = $student2->residenceperiod;
    $str1 = explode('年', $str, 2);
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
    }
    $textobj->text = $stryear . $strmonth;
  } elseif($key == 'residencedate') {
    $textobj->text = date('Y年m月d日', strtotime($student2->residencedate));
  } elseif($key == 'course') {
    $textobj->text = $student2->coursename;
  } elseif($key == 'japanentrydate') {
    $textobj->text = date('Y年m月d日', strtotime($student2->japanentrydate));
  } elseif($key == 'schoolentrydate') {
    $textobj->text = date('Y年m月d日', strtotime($student2->schoolentrydate));
  } elseif($key == 'scheduledcompletiondate') {
    $textobj->text = date('Y年m月d日', strtotime($student2->scheduledcompletiondate));
  } elseif($key == 'passportnumber') {
    $textobj->text = $student2->passportnumber;
  } elseif($key == 'residencenumber') {
    $textobj->text = $student2->residencenumber;
  } elseif($key == 'visapermitnumber') {
    $textobj->text = $student2->visapermitnumber;
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

// ---------------------------------------------------------
$timetxt = date('Ymd_His', time());
$outfilename = 'PDF001_' . $timetxt . '.pdf';
ob_end_clean();
//Close and output PDF document
$pdf->Output($outfilename, 'I');//

//============================================================+
// END OF FILE
//============================================================+
