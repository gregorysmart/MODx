<?php
/**
 * @package modx
 * @subpackage processors.browser.file
 */
$modx->lexicon->load('file');

if (!$modx->hasPermission('file_manager')) return $modx->error->failure($modx->lexicon('permission_denied'));

$file = rawurldecode($_REQUEST['file']);
$newname = $_POST['name'];

if (!file_exists($file)) return $modx->error->failure($modx->lexicon('file_err_nf'));

/* write file */
$f = @fopen($file,'w+');
fwrite($f,$_POST['content']);
fclose($f);

/* rename if necessary */
$filename = ltrim(strrchr($file,'/'),'/');
$path = str_replace(strrchr($file,'/'),'',$file);

if ($filename != $newname) {
    if (!@rename($path.$filename,$path.$newname)) {
        return $modx->error->failure($modx->lexicon('file_err_rename'));
    }
    $fullname = $path.$newname;
} else {
    $fullname = $file;
}


return $modx->error->success('',array(
    'file' => rawurlencode($fullname),
));