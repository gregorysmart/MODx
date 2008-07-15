<?php
/**
 * @package modx
 * @subpackage processors.security.access.policy
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('policy');

$name = isset($_POST['name']) ? $_POST['name'] : '';
if (!$name)
    $modx->error->failure('No name specified for policy!');

$c = array('name' => $name);
$policy = $modx->getObject('modAccessPolicy', $c);
if ($policy === null) {
    $policy = $modx->newObject('modAccessPolicy');
    $policy->fromArray($_POST);
    if (!$policy->save()) {
        $modx->error->failure('Error saving policy!');
    }
} else {
    $modx->error->failure('Policy already exists!');
}

// log manager action
$modx->logManagerAction('new_access_policy','modAccessPolicy',$policy->id);

$modx->error->success();
