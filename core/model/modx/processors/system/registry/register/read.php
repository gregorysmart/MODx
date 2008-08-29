<?php
/**
 * @package modx
 * @subpackage processors.system.registry.register
 */
require_once MODX_PROCESSORS_PATH.'index.php';

if (!isset($_POST['register']) || empty($_POST['register']) || !preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $_POST['register'])) $modx->error->failure($modx->lexicon('error'));
if (!isset($_POST['topic']) || empty($_POST['topic'])) $modx->error->failure($modx->lexicon('error'));

$register = trim($_POST['register']);
$topic = trim($_POST['topic']);
$format = isset($_POST['format']) ? trim($_POST['format']) : 'json';

$modx->getService('registry', 'registry.modRegistry');
$modx->registry->addRegister($register, 'registry.modFileRegister', array('directory' => $register));
if (!$modx->registry->$register->connect()) $modx->error->failure($modx->lexicon('error'));

$modx->registry->$register->subscribe($topic);

$msgs = $modx->registry->$register->read();
if (!empty($msgs)) {
    if ($format == 'html_log') {
        $message = '';
        foreach ($msgs as $msgKey => $msg) {
            if (!empty ($msg['def'])) $msg['def']= "{$def} ";
            if (!empty ($msg['file'])) $msg['file']= "@ {$file} ";
            if (!empty ($msg['line'])) $msg['line']= "line {$line} ";
            $message .= '<span class="' . strtolower($msg['level']) . '"><small>(' . trim($msg['def'] . $msg['file'] . $msg['line']) . ')</small>'.$msg['msg']."</span><br />\n";
        }
        if (!empty($message)) {
            $modx->error->success($message);
        }
    } elseif (!empty($msgs)) {
        $modx->error->success($modx->toJSON($msgs));
    }
}
$modx->error->success('');
