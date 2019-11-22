<?php
//============================================================+
/**
 * Modify an PDF document using TCPDF
 * @abstract TCPDF - Example: Include external UTF-8 text file
 */

// Include the main TCPDF library (search for installation path).
require_once('TCPDF/config/tcpdf_config.php');
require_once('TCPDF/tcpdf.php');
require_once('TCPDF/tcpdi.php');
require_once('../include/include_database.php');

// create new PDF document
$pdf = new TCPDI(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
// Add a page from a PDF by file path.
$pdf->AddPage();
$pdf->setSourceFile('PdfTemplate/学业成绩及出席状况证明书-空白.pdf');
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

//数据库操作
$ID = $_GET['ID'];
$sql = 'SELECT * FROM user WHERE ID=:ID';
$statement = $connection->prepare($sql);
$statement->execute([':ID' => $ID ]);
$person = $statement->fetch(PDO::FETCH_OBJ);
//Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')
$x1 = 13.2;
$y1 = 63.9;
$x2 = 42.2;
$y2 = 78.8;
$txt=$person->nationalityregion;
$pdf->SetXY($x1, $y1);
$pdf->Cell($x2 - $x1, $y2 - $y1, $txt, 0, 0, 'C', 0, '', 0); // LEFT CENTER RIGHT：L C R

// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
// 使用\n换行必须双引号
$x1 = 46.6;
$y1 = 63.9;
$x2 = 95.4;
$y2 = 78.8;
$txt=$person->lastnamemotherland . " " . $person->firstnamemotherland . "\n" . $person->lastnamealphabet . " " . $person->firstnamealphabet . "\n" . $person->lastnamefurigana . " " . $person->firstnamefurigana;
$pdf->MultiCell($x2 - $x1, $y2 - $y1, $txt, 0, 'L', 0, 0, $x1, $y1, true, 0, false, true, 0);

// ---------------------------------------------------------
$timetxt = date('Ymd_His', time());
$outfilename = 'PDF001_' . $timetxt . '.pdf';
ob_end_clean();
//Close and output PDF document
$pdf->Output($outfilename, 'I');//

//============================================================+
// END OF FILE
//============================================================+
