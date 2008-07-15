<?php
/**
 * @package modx
 * @subpackage processors.security
 */

require_once MODX_PROCESSORS_PATH.'index.php';
require_once MODX_MANAGER_PATH.'assets/captcha/veriword.php';
$modx->lexicon->load('login');

$vword = new VeriWord(148,60,$modx);
$vword->output_image();
$vword->destroy_image();