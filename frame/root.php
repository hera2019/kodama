<?php
$WEBROOT = dirname(__FILE__);
function import($path) {    
    $old_dir = getcwd();        // 保存原“参照目录”
    chdir(dirname(__FILE__));    // 将“参照目录”更改为当前脚本的绝对路径
    require_once($path);
    chdir($old_dir);            // 改回原“参照目录”
}

function importjs($path) {    
    $old_dir = getcwd();        // 保存原“参照目录”
    chdir(dirname(__FILE__));    // 将“参照目录”更改为当前脚本的绝对路径
    echo "<script type=\'text/javascript\' src=\'" . $path . "\'></script>";
    chdir($old_dir);            // 改回原“参照目录”
}
?>