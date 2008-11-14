<?php
/**
 * Grabs a list of inputs for a TV.
 *
 * @param string $ctx (optional) The context by which to grab renders from.
 * Defaults to mgr.
 *
 * @package modx
 * @subpackage processors.element.tv.renders
 */
$modx->lexicon->load('tv_widget');

if (!isset($_REQUEST['ctx'])) $_REQUEST['ctx'] = 'mgr';

$renderdir = dirname(__FILE__).'/'.$_REQUEST['ctx'].'/input/';

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

return $this->outputArray($types);