<?php
/**
 * @package modx
 * @subpackage processors.system.settings
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('system_setting');
/***************************************************
 * VALIDATION
 ***************************************************/

/*** SITE ***/
if (!isset($_POST['site_name']) || $_POST['site_name'] == '')
	$error->addField('site_name',$modx->lexicon('system_setting_err_site_name'));

if (!isset($_POST['manager_lang_attribute']) || $_POST['manager_lang_attribute'] == '')
	$_POST['manager_lang_attribute'] = 'en';

if (!isset($_POST['site_start']) || $_POST['site_start'] == '')
	$error->addField('site_start',$modx->lexicon('system_setting_err_site_start'));
$s = $modx->getObject('modResource',$_POST['site_start']);
if ($s == NULL) $error->addField('site_start',sprintf($modx->lexicon('system_setting_err_invalid_document'),$_POST['site_start']));

if (!isset($_POST['error_page']) || $_POST['error_page'] == '')
	$error->addField('error_page',$modx->lexicon('system_setting_err_error_page'));
$s = $modx->getObject('modResource',$_POST['error_page']);
if ($s == NULL) $error->addField('error_page',sprintf($modx->lexicon('system_setting_err_invalid_document'),$_POST['error_page']));

if (!isset($_POST['unauthorized_page']) || $_POST['unauthorized_page'] == '')
	$error->addField('unauthorized_page',$modx->lexicon('system_setting_err_unauthorized_page'));
$s = $modx->getObject('modResource',$_POST['unauthorized_page']);
if ($s == NULL) $error->addField('unauthorized_page',sprintf($modx->lexicon('system_setting_err_invalid_document'),$_POST['unauthorized_page']));

if (!isset($_POST['site_unavailable_page']) || $_POST['site_unavailable_page'] == '')
	$error->addField('site_unavailable_page',$modx->lexicon('system_setting_err_site_unavailable_page'));
$s = $modx->getObject('modResource',$_POST['site_unavailable_page']);
if ($s == NULL) $error->addField('site_unavailable_page',sprintf($modx->lexicon('system_setting_err_invalid_document'),$_POST['site_unavailable_page']));

if (!isset($_POST['site_status']) || $_POST['site_status'] == '')
	$error->addField('site_status',$modx->lexicon('system_setting_err_site_status'));

if (!isset($_POST['track_visitors']) || $_POST['track_visitors'] == '')
	$_POST['track_visitors'] = 0;

if (!isset($_POST['resolve_hostnames']) || $_POST['resolve_hostnames'] == '')
	$_POST['resolve_hostnames'] = 0;

if (!isset($_POST['top_howmany']) || !is_numeric($_POST['top_howmany']) || $_POST['top_howmany'] <= 0)
	$error->addField('top_howmany',$modx->lexicon('system_setting_err_top_howmany'));

if (!isset($_POST['publish_default']) || $_POST['publish_default'] == '')
	$error->addField('publish_default',$modx->lexicon('system_setting_err_cache_default'));
if (!isset($_POST['cache_default']) || $_POST['cache_default'] == '')
	$error->addField('cache_default',$modx->lexicon('system_setting_err_cache_default'));
if (!isset($_POST['search_default']) || $_POST['search_default'] == '')
	$error->addField('search_default',$modx->lexicon('system_setting_err_search_default'));

if (!isset($_POST['auto_menuindex']) || $_POST['auto_menuindex'] == '')
	$_POST['auto_menuindex'] = 1;

if (!isset($_POST['server_protocol']) || $_POST['server_protocol'] == '')
	$error->addField('server_protocol',$modx->lexicon('system_setting_err_server_protocol'));

/*** Friendly URLs ***/

if (!isset($_POST['friendly_urls']) || $_POST['friendly_urls'] == '')
	$error->addField('friendly_urls',$modx->lexicon('system_setting_err_friendly_urls'));


//die($error->process($_POST['friendly_urls']));

/*** User ***/

if (!isset($_POST['use_udperms']) || $_POST['use_udperms'] == '')
	$error->addField('use_udperms',$modx->lexicon('system_setting_err_use_udperms'));

if (!isset($_POST['udperms_allowroot']) || $_POST['udperms_allowroot'] == '')
	$_POST['udperms_allowroot'] = 0;

if (!isset($_POST['use_captcha']) || $_POST['use_captcha'] == '')
	$error->addField('use_captcha',$modx->lexicon('system_setting_err_use_captcha'));
if (!isset($_POST['captcha_words']) || $_POST['captcha_words'] == '')
	$error->addField('captcha_words',$modx->lexicon('system_setting_err_captcha_words'));

if (!isset($_POST['emailsender']) || $_POST['emailsender'] == '')
	$error->addField('emailsender',$modx->lexicon('system_setting_err_emailsender'));
if (!isset($_POST['emailsubject']) || $_POST['emailsubject'] == '')
	$error->addField('emailsubject',$modx->lexicon('system_setting_err_emailsubject'));

/*** Interface & Features ***/

if (!isset($_POST['show_preview']) || $_POST['show_preview'] == '')
	$error->addField('show_preview',$modx->lexicon('system_setting_err_show_preview'));

if (!isset($_POST['number_of_logs']) || $_POST['number_of_logs'] == '')
	$error->addField('number_of_logs',$modx->lexicon('system_setting_err_number_of_logs'));
if (!isset($_POST['mail_check_timeperiod']) || $_POST['mail_check_timeperiod'] == '')
	$error->addField('mail_check_timeperiod',$modx->lexicon('system_setting_err_mail_check_timeperiod'));
if (!isset($_POST['number_of_messages']) || $_POST['number_of_messages'] == '')
	$error->addField('number_of_messages',$modx->lexicon('system_setting_err_number_of_messages'));
if (!isset($_POST['number_of_results']) || $_POST['number_of_results'] == '')
	$error->addField('number_of_results',$modx->lexicon('system_setting_err_number_of_results'));

if (!isset($_POST['use_browser']) || $_POST['use_browser'] == '')
	$error->addField('use_browser',$modx->lexicon('system_setting_err_use_browser'));
if (!isset($_POST['strip_image_paths']) || $_POST['strip_image_paths'] = '')
	$_POST['strip_image_paths'] = 0;

if (!isset($_POST['rb_base_dir']) || $_POST['rb_base_dir'] == '')
	$error->addField('rb_base_dir',$modx->lexicon('system_setting_err_rb_base_dir'));

$rb_base_dir = strtr($_POST['rb_base_dir'],'\\','/');
if (!is_dir($rb_base_dir))
	$error->addField('rb_base_dir',$rb_base_dir.$modx->lexicon('system_setting_err_rb_base_dir_invalid'));

if (!isset($_POST['rb_base_url']) || $_POST['rb_base_url'] == '')
	$error->addField('rb_base_url',$modx->lexicon('system_setting_err_rb_base_url'));

if (!isset($_POST['upload_images']) || $_POST['upload_images'] = '')
	$_POST['upload_images'] = 'jpg,jpeg,png,gif,psd,ico,bmp';
if (!isset($_POST['upload_media']) || $_POST['upload_media'] = '')
	$_POST['upload_media'] = 'mp3,wav,au,wmv,avi,mpg,mpeg';
if (!isset($_POST['upload_flash']) || $_POST['upload_flash'] = '')
	$_POST['upload_flash'] = 'swf,fla';

if (!isset($_POST['use_editor']) || $_POST['use_editor'] == '')
	$error->addField('use_editor',$modx->lexicon('system_setting_err_use_editor'));

/*** Miscellaneous ***/

if (!isset($_POST['filemanager_path']) || $_POST['filemanager_path'] == '')
	$error->addField('filemanager_path',$modx->lexicon('system_setting_err_filemanager_path'));
if (!is_dir($_POST['filemanager_path']))
	$error->addField('filemanager_path',$modx->lexicon('system_setting_err_filemanager_path_invalid'));

if (!isset($_POST['upload_files']) || $_POST['upload_files'] = '')
	$_POST['upload_files'] = 'txt,php,html,htm,xml,js,css,cache,zip,gz,rar,z,tgz,tar,htaccess,mp3,mp4,aac,wav,au,wmv,avi,mpg,mpeg,pdf,doc,xls,txt';
if (!isset($_POST['upload_maxsize']) || $_POST['upload_maxsize'] = '')
	$_POST['upload_maxsize'] = '1048576';

if (!isset($_POST['new_file_permissions']) || $_POST['new_file_permissions'] = '')
	$_POST['new_file_permissions'] = '0644';
if (!isset($_POST['new_folder_permissions']) || $_POST['new_folder_permissions'] = '')
	$_POST['new_folder_permissions'] = '0755';

if (!isset($_POST['compress_js']) || $_POST['compress_js'] == '')
	$_POST['compress_js'] = 0;

if (!isset($_POST['cache_disabled']) || $_POST['cache_disabled'] == '')
	$error->addField('cache_disabled',$modx->lexicon('system_setting_err_cache_disabled'));

if (!isset($_POST['cache_resource']) || $_POST['cache_resource'] == '')
	$_POST['cache_resource'] = 0;
if (!isset($_POST['cache_db']) || $_POST['cache_db'] == '')
	$_POST['cache_db'] = 0;

if (!isset($_POST['session_handler_class']) || $_POST['session_handler_class'] == '')
	$_POST['session_handler_class'] = 'modSessionHandler';
if (!isset($_POST['session_cookie_path']) || $_POST['session_cookie_path'] == '')
	$_POST['session_cookie_path'] = '/';
if (!isset($_POST['session_cookie_lifetime']) || $_POST['session_cookie_lifetime'] == '')
	$_POST['session_cookie_lifetime'] = '604800';
if (!isset($_POST['session_cookie_domain']) || $_POST['session_cookie_domain'] == '')
	$_POST['session_cookie_domain'] = 'localhost';
if (!isset($_POST['session_name']) || $_POST['session_name'] == '')
	$_POST['session_name'] = 'modxcmssession';

/*** OUTPUT VALIDATION ***/

$fs = $error->getFields();
$fields = '<ul>';
foreach ($fs as $f) {
	$fields .= '<li>'.ucwords(str_replace('_',' ',$f)).'</li>';
}
$fields .= '</ul>';

if ($error->hasError()) $error->failure($modx->lexicon('system_setting_err').$fields);


/* END VALIDATION
 ****************************************************/

foreach ($_POST as $k => $v) {
	$v = is_array($v) ? implode(',', $v) : $v;
	$setting = $modx->getObject('modSystemSetting',$k);
	if ($setting == NULL) {
		$setting = $modx->newObject('modSystemSetting');
		$setting->set('setting_name',$k);
	}
	$setting->set('setting_value',$v);

	if (!$setting->save()) $error->failure($modx->lexicon('system_setting_err_save'));
}

// reload config cache
$s = $modx->reloadConfig();

// log manager action
$modx->logManagerAction('save_configuration','',0);

$error->success();