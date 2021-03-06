<?php
/**
 * Grabs a list of inputs for a TV.
 *
 * @param string $context (optional) The context by which to grab renders from. Defaults to
 * executing context.
 *
 * @package modx
 * @subpackage processors.element.tv.renders
 */
if (!$modx->hasPermission('view_tv')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('tv_widget');

$context = (isset($scriptProperties['context']) && !empty($scriptProperties['context'])) ? $scriptProperties['context'] : $modx->context->get('key');

$renderdir = dirname(__FILE__).'/'.$context.'/input/';

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