<?php
/**
 * @package modx
 * @subpackage processors.security.profile
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('user');

$profile = $modx->user->getOne('modUserProfile');
if ($profile == null) $error->failure($modx->lexicon('user_profile_err_not_found'));

$_POST['dob'] = strtotime($_POST['dob']);
$profile->fromArray($_POST);

if (!$profile->save()) $error->failure($modx->lexicon('user_profile_err_save'));

// log manager action
$modx->logManagerAction('save_profile','modUser',$modx->user->id);

$error->success($modx->lexicon('success'));