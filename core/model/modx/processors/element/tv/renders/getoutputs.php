<?php
/**
 * Grabs a list of output renders for the tv.
 *
 * @param string $ctx (optional) The context by which to grab renders from.
 * Defaults to web.
 *
 * @package modx
 * @subpackage processors.element.tv.renders
 */
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

return $this->outputArray($types);