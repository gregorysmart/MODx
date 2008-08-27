<?php
/**
 * @package modx
 * @subpackage processors.element.tv.renders
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('tv_widget');

if (!isset($_REQUEST['ctx'])) $_REQUEST['ctx'] = 'web';

$renderdir = dirname(__FILE__).'/'.$_REQUEST['ctx'].'/output/';

$types = array();
if ($handle = opendir($renderdir)) {
    while (false !== ($file = readdir($handle))) {
        if (!is_file($renderdir.$file)) continue;
        $type = str_replace('.php','',$file);
        $types[] = array(
            'name' => $modx->lexicon($type),
            'value' => $type,
        );
    }

    closedir($handle);
}

$this->outputArray($types);