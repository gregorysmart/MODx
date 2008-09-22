<?php
/**
 * Loads the login screen
 *
 * @package modx
 * @subpackage manager.security
 */
$modx->lexicon->load('login');
$modx->smarty->assign('_lang',$modx->lexicon->fetch());

// invoke OnManagerLoginFormPrerender event
$eventInfo= $modx->invokeEvent('OnManagerLoginFormPrerender');
$eventInfo= is_array($eventInfo) ? implode("\n", $eventInfo) : (string) $eventInfo;
$modx->smarty->assign('onManagerLoginFormPrerender', $eventInfo);

$captcha_image= '';
$captcha_input= '';
if (isset ($modx->config['use_captcha']) && $modx->config['use_captcha'] == 1) {
    $captcha_image= '<a href="'.$_SERVER['PHP_SELF'].'" class="loginCaptcha"><img src="'.$modx->config['connectors_url'].'security/captcha.php?rand='.rand().'" alt="'.$modx->lexicon("login_captcha_message").'" /></a>';
    $captcha_input= '<label>'.$modx->lexicon("captcha_code").'</label> <input type="text" name="captcha_code" tabindex="3" value="" />';
}
$modx->smarty->assign('captcha_image', $captcha_image);
$modx->smarty->assign('captcha_input', $captcha_input);

// andrazk 20070416 - notify user of install/update
if (isset($_REQUEST['installGoingOn'])) {
    $installGoingOn = $_REQUEST['installGoingOn'];
}
if (isset ($installGoingOn)) {
    switch ($installGoingOn) {
        case 1 : $modx->setPlaceholder('login_message',$modx->lexicon("login_cancelled_install_in_progress").$modx->lexicon("login_message")); break;
        case 2 : $modx->setPlaceholder('login_message',$modx->lexicon("login_cancelled_site_was_updated").$modx->lexicon("login_message")); break;
    }
}

// invoke OnManagerLoginFormRender event
$eventInfo= $modx->invokeEvent('OnManagerLoginFormRender');
$eventInfo= is_array($eventInfo) ? implode("\n", $eventInfo) : (string) $eventInfo;
$eventInfo= str_replace('\'','\\\'',$eventInfo);

$modx->smarty->assign('onManagerLoginFormRender', $eventInfo);

$modx->smarty->display('security/login.tpl');