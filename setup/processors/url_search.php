<?php
$output= '';
$agreed= false;
if (isset ($_POST['chkagree']) || isset ($_SESSION['license_agreed'])) {
    $agreed= true;
}
$mode= isset ($_POST['installmode']) ? intval($_POST['installmode']) : 0;
if ($agreed) {
    //validate database settings
    require_once (MODX_CORE_PATH . 'xpdo/xpdo.class.php');
    if (isset ($_REQUEST['search']) && $_REQUEST['search']) {
        $id= 0;
        $results= array();
        $searchString= $_REQUEST['search'];
        $filepart= '';
        if ($searchString{strlen($searchString)-1} != '/') {
            $filepart= basename(trim($searchString, '/'));
            $searchString= strtr(dirname($searchString), '\\', '/');
        } else {
            $searchString= strtr($searchString, '\\', '/');
        }
        $searchString= trim($searchString, '/');
        $docroot= $_SERVER['DOCUMENT_ROOT'];
        $dirname= $docroot . '/' . $searchString;
//        echo '{"results":' . $dirname . '}';

        if ($handle= @ opendir($dirname)) {
            while (false !== ($file= @ readdir($handle))) {
                if ($file != '.' && $file != '..') { //Ignore . and ..
                    $path= $dirname . '/' . $file;
                    $result= '/' . trim(str_replace($docroot, '', $path), '/') . '/';
                    if (is_dir($path) && (!$filepart || (strpos($file, $filepart) === 0))) {
                        $results[]= array (
                            'id' => $result,
                            'value' => $result
//                            'info' => ''
                        );
                    }
                }
            }
            $output= xPDO :: toJSON($results);
        }
    }
    if (empty ($output) || $output == '[]') {
//        $output= xPDO :: toJSON(array ('id' => 1, 'value' => $dirname));
//        $output= "[{$output}]";
        $output= "[]";
    }
    header('Content-Type:text/javascript');
    echo '{"success": true, "results":' . $output . '}';
    exit();
} else {
    $response= 'license';
}
$this->error->success($output);