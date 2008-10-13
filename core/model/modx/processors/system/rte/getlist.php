<?php
/**
 * @package modx
 * @subpackage processors.system.rte
 */
require_once MODX_PROCESSORS_PATH.'index.php';

/* invoke OnRichTextEditorRegister event */
$rs = $modx->invokeEvent('OnRichTextEditorRegister');
if ($rs == '') $rs == array();

$rtes = array();
$rtes[] = array('value' => $modx->lexicon('none'));
if (is_array($rs)) {
    foreach ($rs as $r) {
	   $rtes[] = array('value' => $r);
    }
}
$this->outputArray($rtes,$count);