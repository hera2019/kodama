<?php
require_once('../plugin/pdf/TCPDF/config/tcpdf_config.php');
require_once('../plugin/pdf/TCPDF/tcpdf.php');
require_once('../plugin/pdf/TCPDF/tcpdi.php');
require_once('../include/include_database.php');

class TextArea {
  public $left;
  public $top;
  public $right;
  public $bottom;
  public $text;
  public $bmulticell;
  public $align;
  public $valign;
  function __construct( $left, $top, $right, $bottom, $text='', $bmulticell=false, $align='C', $valign='M' ) {
    $this->left = $left;
    $this->top = $top;
    $this->right = $right;
    $this->bottom = $bottom;
    $this->text = $text;
    $this->bmulticell = $bmulticell;
    $this->align = $align;
    $this->valign = $valign;
  }
  function Width() {
    return $this->right - $this->left;
  }
  function Height() {
    return $this->bottom - $this->top;
  }
  function Date($date) {
    if(!empty($date)) {
      $this->text = date('Y年m月d日', strtotime($date));
    } else {
      $this->text = '';
    }
    
    return $this->text;
  }
}
?>