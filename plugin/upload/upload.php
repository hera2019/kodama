<?php
namespace Verot\Upload;

error_reporting(E_ALL);

// we first include the upload class, as we will need it here to deal with the uploaded file
include('class.upload.php');

//照片默认目录
$PHOTO_PATH = '../data/photo/';

// set variables
$dest = (isset($_POST['dir']) ? $_POST['dir'] : (isset($_GET['dir']) ? $_GET['dir'] : 'save'));
$dir_dest = '../' . $PHOTO_PATH . $dest;
$pics = (isset($_POST['pics']) ? $_POST['pics'] : (isset($_GET['pics']) ? $_GET['pics'] : $dest));
$dir_pics = '../' . $PHOTO_PATH . $pics;

$log = '';

class RtInfo {
  public $result = 201; //result:(200:success, 201...:failed)
  public $message = '';
  public $filename = '';
  public $log = '';
}

$rtinfo = new RtInfo();
//echo json_encode($rtinfo);

// we have several forms on the test page, so we redirect accordingly
$action = (isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : ''));

if ($action == 'ajax') {

    // we create an instance of the class, giving as argument the PHP object
    // corresponding to the file field from the form
    // This is the fallback, using the standard way
    $handle = new Upload($_FILES['my_field']);

    // then we check if the file has been uploaded properly
    // in its *temporary* location in the server (often, it is /tmp)
    if ($handle->uploaded) {

        // yes, the file is on the server
        // now, we start the upload 'process'. That is, to copy the uploaded file
        // from its temporary location to the wanted location
        // It could be something like $handle->process('/home/www/my_uploads/');
        $handle->process($dir_dest);

        // we check if everything went OK
        if ($handle->processed) {
            $rtinfo->result = 200;
            $rtinfo->message = 'File uploaded with success.';
            $rtinfo->filename = $pics . '/' . $handle->file_dst_name;
        } else {
            // one error occured
            $rtinfo->result = 201;
            $rtinfo->message = 'File not uploaded to the wanted location. Error: ' . $handle->error;
        }

        // we delete the temporary files
        $handle-> clean();

    } else {
        // if we're here, the upload file failed for some reasons
        // i.e. the server didn't receive the file
        $rtinfo->result = 202;
        $rtinfo->message = 'File not uploaded on the server. Error: ' . $handle->error;
    }

    $rtinfo->log .= $handle->log;
    echo json_encode($rtinfo);
} else if ($action == 'xhr') {

    // ---------- XMLHttpRequest UPLOAD ----------

    // we first check if it is a XMLHttpRequest call
    if (isset($_SERVER['HTTP_X_FILE_NAME']) && isset($_SERVER['CONTENT_LENGTH'])) {

        // we create an instance of the class, feeding in the name of the file
        // sent via a XMLHttpRequest request, prefixed with 'php:'
        $handle = new Upload('php:'.$_SERVER['HTTP_X_FILE_NAME']);

    } else {
        // we create an instance of the class, giving as argument the PHP object
        // corresponding to the file field from the form
        // This is the fallback, using the standard way
        $handle = new Upload($_FILES['my_field']);
    }

    // then we check if the file has been uploaded properly
    // in its *temporary* location in the server (often, it is /tmp)
    if ($handle->uploaded) {

        // yes, the file is on the server
        // now, we start the upload 'process'. That is, to copy the uploaded file
        // from its temporary location to the wanted location
        // It could be something like $handle->process('/home/www/my_uploads/');
        $handle->process($dir_dest);

        // we check if everything went OK
        if ($handle->processed) {
            $rtinfo->result = 200;
            $rtinfo->message = 'File uploaded with success.';
            $rtinfo->filename = $pics . '/' . $handle->file_dst_name;
        } else {
            // one error occured
            $rtinfo->result = 201;
            $rtinfo->message = 'File not uploaded to the wanted location. Error: ' . $handle->error;
        }

        // we delete the temporary files
        $handle-> clean();

    } else {
        // if we're here, the upload file failed for some reasons
        // i.e. the server didn't receive the file
        $rtinfo->result = 202;
        $rtinfo->message = 'File not uploaded on the server. Error: ' . $handle->error;
    }

    $rtinfo->log .= $handle->log;
    echo json_encode($rtinfo);
}
?>