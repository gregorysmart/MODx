<?php
/**
 * @package modx
 * @subpackage processors.system.registry.register
 */
require_once MODX_PROCESSORS_PATH.'index.php';

if (!isset($_POST['register']) || empty($_POST['register']) || !preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $_POST['register'])) $modx->error->failure($modx->lexicon('error'));
if (!isset($_POST['topic']) || empty($_POST['topic'])) $modx->error->failure($modx->lexicon('error'));

$register = trim($_POST['register']);
$register_class = isset($_POST['register_class']) ? trim($_POST['register_class']) : 'registry.modFileRegister';

$topic = trim($_POST['topic']);

$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$message_format = isset($_POST['message_format']) ? trim($_POST['message_format']) : 'string';

$options = array();
$options['delay'] = isset($_POST['delay']) ? intval($_POST['delay']) : 0;
$options['ttl'] = isset($_POST['ttl']) ? intval($_POST['ttl']) : 0;
$options['kill'] = (isset($_POST['kill']) && !empty($_POST['kill'])) ? true : false;

$modx->getService('registry', 'registry.modRegistry');
$modx->registry->addRegister($register, $register_class, array('directory' => $register));
if (!$modx->registry->$register->connect()) $modx->error->failure($modx->lexicon('error'));

$modx->registry->$register->subscribe($topic);

switch ($message_format) {
    case 'string':
    default:
        $message = array($message);
        break;
}

if (!$modx->registry->$register->send($topic, $message, $options)) {
    $modx->error->failure($modx->lexicon('error'));
}

$modx->error->success('');
