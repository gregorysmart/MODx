<?php
/**
 * 
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('file');

$file = rawurldecode($_POST['file']);

if (!file_exists($file)) $modx->error->failure($modx->lexicon('file_err_nf'));

$filename = ltrim(strrchr($file,'/'),'/');

$fbuffer = @file_get_contents($file);
$time_format = '%b %d, %Y %H:%I:%S %p';

$fa = array(
    'name' => $filename,
    'size' => filesize($file),
    'last_accessed' => strftime($time_format,fileatime($file)),
    'last_modified' => strftime($time_format,filemtime($file)), 
    'content' => $fbuffer,
);

$modx->error->success('',$fa);