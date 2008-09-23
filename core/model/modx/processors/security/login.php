<?php
/**
 * @package modx
 * @subpackage processors.security
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('login');

$username = $_POST['username'];
$givenPassword = $_POST['password'];

$rememberme= isset ($_POST['rememberme']) ? ($_POST['rememberme'] == 'on' || $_POST['rememberme'] == true) : false;
$loginContext= isset ($_POST['login_context']) ? $_POST['login_context'] : 'mgr';

$onBeforeLoginParams = array(
    'username' => $username,
    'password' => $givenPassword,
    'attributes' => array(
        'rememberme' => & $rememberme,
        'loginContext' => & $loginContext
    )
);

$rt = false;  //$rt will be an array if the event fires
if ($loginContext == 'mgr' || $loginContext == 'connector') {
    $rt = $modx->invokeEvent("OnBeforeManagerLogin", $onBeforeLoginParams);
} else {
    $rt = $modx->invokeEvent("OnBeforeWebLogin", $onBeforeLoginParams);
}
/* If the event fired, loop through the event array and fail if there's an error message  */
if (is_array($rt)) {
    foreach ($rt as $key => $value) {   //php4 compatible
        if ($value !== true) {
            $modx->error->failure($value);
        }
    }
    unset($key,$value);
}

$user= $modx->getObjectGraph('modUser', '{"modUserProfile":{},"modUserSetting":{}}', array ('modUser.username' => $username));
if (!$user) {
    $modx->invokeEvent("OnUserNotFound", array(
        'user' => & $user,
        'username' => & $username,
        'password' => $password,
        array (
            'rememberme' => & $rememberme,
            'loginContext' => $loginContext,
        )
    ));
    if (!is_object($user) || !is_a($user, 'modUser')) {
        $error->failure($modx->lexicon('login_cannot_locate_account'));
    }
}

$up= & $user->modUserProfile;
$us= & $user->modUserSetting;
foreach ($us as $settingPK => $setting) {
    $sname= $setting->get('key');
    $$sname= $setting->value;
}
if ($up->failed_logins >= $modx->config['failed_login_attempts'] && $up->blockeduntil > time()) {
    $error->failure($modx->lexicon('login_blocked_too_many_attempts'));
}
if ($up->failedlogincount >= $modx->config['failed_login_attempts'] && $up->blockeduntil < time()) {
    $up->set('failedlogincount', 0);
    $up->set('blockeduntil', time() - 1);
    $up->save();
}
if ($up->blocked) {
    $error->failure($modx->lexicon('login_blocked_admin'));
}
if ($up->blockeduntil > time()) {
    $error->failure($modx->lexicon('login_blocked_error'));
}
if ($up->blockedafter > 0 && $up->blockedafter < time()) {
    $error->failure($modx->lexicon('login_blocked_error'));
}
if (isset ($allowed_ip) && $allowed_ip) {
    if (($hostname = gethostbyaddr($_SERVER['REMOTE_ADDR'])) && ($hostname != $_SERVER['REMOTE_ADDR'])) {
        if (gethostbyname($hostname) != $_SERVER['REMOTE_ADDR']) {
            $error->failure($modx->lexicon('login_hostname_error'));
        }
    }
    if (!in_array($_SERVER['REMOTE_ADDR'], explode(',', str_replace(' ', '', $allowed_ip)))) {
        $error->failure($modx->lexicon('login_blocked_ip'));
    }
}
if (isset ($allowed_days) && $allowed_days) {
    $date = getdate();
    $day = $date['wday'] + 1;
    if (strpos($allowed_days, "{$day}") === false) {
        $error->failure($modx->lexicon('login_blocked_time'));
    }
}

$loginAttributes = array(
    "user"       => & $user,
    "password"   => $givenPassword,
    "rememberme" => $rememberme
);
if ($loginContext == 'mgr') {
    $rt = $modx->invokeEvent("OnManagerAuthentication", $loginAttributes);
} else {
    $rt = $modx->invokeEvent("OnWebAuthentication", $loginAttributes);
}
// check if plugin authenticated the user
if (!$rt || (is_array($rt) && !in_array(true, $rt))) {
    // check user password - local authentication
    if($user->password != md5($givenPassword)) {
        $error->failure($modx->lexicon('login_username_password_incorrect'));
    }
}

$user->addSessionContext($loginContext);
if ($loginContext == 'mgr') $user->addSessionContext('connector');

if($rememberme) {
    $_SESSION['modx.' . $loginContext . '.session.cookie.lifetime']= intval($modx->config['session_cookie_lifetime']);
} else {
    $_SESSION['modx.' . $loginContext . '.session.cookie.lifetime']= 0;
}

$postLoginAttributes = array(
    'user' => $user,
    'attributes' => array(
        'rememberme' => & $rememberme,
        'loginContext' => & $loginContext
    )
);
if ($loginContext == 'mgr') {
    $rt = $modx->invokeEvent("OnManagerLogin", $postLoginAttributes);
} else {
    $modx->invokeEvent("OnWebLogin", $postLoginAttributes);
}


if (!isset($manager_login_startup)) {
    $manager_login_startup= 0;
}
else {
    $manager_login_startup= intval($manager_login_startup);
}
$response= $manager_login_startup;

$error->success('',array('id' => $response));
?>