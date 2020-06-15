<?php
//指定文件编号，使用日期时间
$PDF_FileNo = date('YmdHis', time());
//指定模板和生成文件前缀名称
$PDF_FileName = '在学証明書';
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
$pdf->setSourceFile('../template/pdf/' . $PDF_FileName . '.pdf');
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
  'No' => new TextArea(44, 28.6, 75, 33, '', false, 'L'),
  'studentnumber' => new TextArea(60, 70, 164, 74.4, '', false, 'L'),
  'builddate' => new TextArea(120, 226, 159, 231, '', false, 'R'),
  'name' => new TextArea(60, 86.6, 164, 91, '', false, 'L'),
  'nationalityregion' => new TextArea(60, 103, 164, 107.4, '', false, 'L'),
  'birthday' => new TextArea(60, 119.6, 164, 124, '', false, 'L'),
  'course' => new TextArea(78, 185.6, 164, 190, '', false, 'L'),
  'schoolentrydate' => new TextArea(63, 152, 164, 157.2, '', false, 'L'),
  'completiondate' => new TextArea(65, 169, 164, 173.6, '', false, 'L'),
  'schoolhours' => new TextArea(63, 202, 164, 206.6, '', false, 'L'),
  'schoolinfo' => new TextArea(75, 238, 190, 275, '', true, 'L', 'T'),
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
    $textobj->text = $PDF_FileNo;
  } elseif($key == 'studentnumber' && !empty($student)) {
    $textobj->text = $student->studentnumber;
  } elseif($key == 'name' && !empty($student)) {
    $textobj->text = $student->lastname . " " . $student->firstname;
  } elseif($key == 'nationalityregion' && !empty($student)) {
    $textobj->text = $student->nationalityregion;
  } elseif($key == 'builddate') {
    $textobj->text = date('Y年m月d日', time());
  } elseif($key == 'birthday' && !empty($student)) {
    $textobj->Date($student->birthday);
  } elseif($key == 'course' && !empty($student2)) {
    $textobj->text = $student2->coursename;
  } elseif($key == 'schoolentrydate' && !empty($student2)) {
    $textobj->Date($student2->schoolentrydate);
  } elseif($key == 'completiondate' && !empty($student2)) {
    $textobj->Date($student2->completiondate);
  } elseif($key == 'schoolhours' && !empty($student2)) {
    $textobj->text = $student2->schoolhours;
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

// ---------------------------------------------------------
//PDF filename build
$outfilename = $PDF_FileName . '_' . $PDF_FileNo . '.pdf';
ob_end_clean();
//Close and output PDF document
$pdf->Output($outfilename, 'I');//

//============================================================+
// END OF FILE
//============================================================+
