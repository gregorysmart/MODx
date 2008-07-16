<?php
$collection['allow_tags_in_post']= $xpdo->newObject('modSystemSetting');
$collection['allow_tags_in_post']->fromArray(array (
  'key' => 'allow_tags_in_post',
  'value' => '1',
  'xtype' => 'combo-boolean',
), '', true, true);
$collection['auto_menuindex']= $xpdo->newObject('modSystemSetting');
$collection['auto_menuindex']->fromArray(array (
  'key' => 'auto_menuindex',
  'value' => '1',
  'xtype' => 'combo-boolean',
), '', true, true);
$collection['blocked_minutes']= $xpdo->newObject('modSystemSetting');
$collection['blocked_minutes']->fromArray(array (
  'key' => 'blocked_minutes',
  'value' => '60',
  'xtype' => 'textfield',
), '', true, true);
$collection['cache_db']= $xpdo->newObject('modSystemSetting');
$collection['cache_db']->fromArray(array (
  'key' => 'cache_db',
  'value' => '0',
  'xtype' => 'combo-boolean',
), '', true, true);
$collection['cache_db_expires']= $xpdo->newObject('modSystemSetting');
$collection['cache_db_expires']->fromArray(array (
  'key' => 'cache_db_expires',
  'value' => '0',
  'xtype' => 'textfield',
), '', true, true);
$collection['cache_default']= $xpdo->newObject('modSystemSetting');
$collection['cache_default']->fromArray(array (
  'key' => 'cache_default',
  'value' => '1',
  'xtype' => 'combo-boolean',
), '', true, true);
$collection['cache_disabled']= $xpdo->newObject('modSystemSetting');
$collection['cache_disabled']->fromArray(array (
  'key' => 'cache_disabled',
  'value' => '0',
  'xtype' => 'combo-boolean',
), '', true, true);
$collection['cache_json']= $xpdo->newObject('modSystemSetting');
$collection['cache_json']->fromArray(array (
  'key' => 'cache_json',
  'value' => '0',
  'xtype' => 'combo-boolean',
), '', true, true);
$collection['cache_json_expires']= $xpdo->newObject('modSystemSetting');
$collection['cache_json_expires']->fromArray(array (
  'key' => 'cache_json_expires',
  'value' => '0',
  'xtype' => 'textfield',
), '', true, true);
$collection['cache_resource']= $xpdo->newObject('modSystemSetting');
$collection['cache_resource']->fromArray(array (
  'key' => 'cache_resource',
  'value' => '1',
  'xtype' => 'combo-boolean',
), '', true, true);
$collection['cache_resource_expires']= $xpdo->newObject('modSystemSetting');
$collection['cache_resource_expires']->fromArray(array (
  'key' => 'cache_resource_expires',
  'value' => '0',
  'xtype' => 'textfield',
), '', true, true);
$collection['captcha_words']= $xpdo->newObject('modSystemSetting');
$collection['captcha_words']->fromArray(array (
  'key' => 'captcha_words',
  'value' => 'MODx,Access,Better,BitCode,Cache,Desc,Design,Excell,Enjoy,URLs,TechView,Gerald,Griff,Humphrey,Holiday,Intel,Integration,Joystick,Join(),Tattoo,Genetic,Light,Likeness,Marit,Maaike,Niche,Netherlands,Ordinance,Oscillo,Parser,Phusion,Query,Question,Regalia,Righteous,Snippet,Sentinel,Template,Thespian,Unity,Enterprise,Verily,Veri,Website,WideWeb,Yap,Yellow,Zebra,Zygote',
  'xtype' => 'textfield',
), '', true, true);
$collection['compress_js']= $xpdo->newObject('modSystemSetting');
$collection['compress_js']->fromArray(array (
  'key' => 'compress_js',
  'value' => '0',
  'xtype' => 'combo-boolean',
), '', true, true);
$collection['container_suffix']= $xpdo->newObject('modSystemSetting');
$collection['container_suffix']->fromArray(array (
  'key' => 'container_suffix',
  'value' => '/',
  'xtype' => 'textfield',
), '', true, true);
$collection['default_template']= $xpdo->newObject('modSystemSetting');
$collection['default_template']->fromArray(array (
  'key' => 'default_template',
  'value' => '1',
  'xtype' => 'combo-template',
), '', true, true);
$collection['editor_css_path']= $xpdo->newObject('modSystemSetting');
$collection['editor_css_path']->fromArray(array (
  'key' => 'editor_css_path',
  'value' => '',
  'xtype' => 'textfield',
), '', true, true);
$collection['editor_css_selectors']= $xpdo->newObject('modSystemSetting');
$collection['editor_css_selectors']->fromArray(array (
  'key' => 'editor_css_selectors',
  'value' => '',
  'xtype' => 'textfield',
), '', true, true);
$collection['emailsender']= $xpdo->newObject('modSystemSetting');
$collection['emailsender']->fromArray(array (
  'key' => 'emailsender',
  'value' => 'you@yourdomain.com',
  'xtype' => 'textfield',
), '', true, true);
$collection['emailsubject']= $xpdo->newObject('modSystemSetting');
$collection['emailsubject']->fromArray(array (
  'key' => 'emailsubject',
  'value' => 'Your login details',
  'xtype' => 'textfield',
), '', true, true);
$collection['error_page']= $xpdo->newObject('modSystemSetting');
$collection['error_page']->fromArray(array (
  'key' => 'error_page',
  'value' => '1',
  'xtype' => 'textfield',
), '', true, true);
$collection['failed_login_attempts']= $xpdo->newObject('modSystemSetting');
$collection['failed_login_attempts']->fromArray(array (
  'key' => 'failed_login_attempts',
  'value' => '3',
  'xtype' => 'textfield',
), '', true, true);
$collection['fck_editor_autolang']= $xpdo->newObject('modSystemSetting');
$collection['fck_editor_autolang']->fromArray(array (
  'key' => 'fck_editor_autolang',
  'value' => '0',
  'xtype' => 'combo-boolean',
), '', true, true);
$collection['fck_editor_toolbar']= $xpdo->newObject('modSystemSetting');
$collection['fck_editor_toolbar']->fromArray(array (
  'key' => 'fck_editor_toolbar',
  'value' => 'standard',
  'xtype' => 'textfield',
), '', true, true);
$collection['fe_editor_lang']= $xpdo->newObject('modSystemSetting');
$collection['fe_editor_lang']->fromArray(array (
  'key' => 'fe_editor_lang',
  'value' => 'english',
  'xtype' => 'combo-language',
), '', true, true);
$collection['feed_modx_news']= $xpdo->newObject('modSystemSetting');
$collection['feed_modx_news']->fromArray(array (
  'key' => 'feed_modx_news',
  'value' => 'http://feeds.feedburner.com/modx-announce',
  'xtype' => 'textfield',
), '', true, true);
$collection['feed_modx_security']= $xpdo->newObject('modSystemSetting');
$collection['feed_modx_security']->fromArray(array (
  'key' => 'feed_modx_security',
  'value' => 'http://feeds.feedburner.com/modxsecurity',
  'xtype' => 'textfield',
), '', true, true);
$collection['filemanager_path']= $xpdo->newObject('modSystemSetting');
$collection['filemanager_path']->fromArray(array (
  'key' => 'filemanager_path',
  'value' => '',
  'xtype' => 'textfield',
), '', true, true);
$collection['friendly_alias_urls']= $xpdo->newObject('modSystemSetting');
$collection['friendly_alias_urls']->fromArray(array (
  'key' => 'friendly_alias_urls',
  'value' => '1',
  'xtype' => 'combo-boolean',
), '', true, true);
$collection['friendly_url_prefix']= $xpdo->newObject('modSystemSetting');
$collection['friendly_url_prefix']->fromArray(array (
  'key' => 'friendly_url_prefix',
  'value' => '',
  'xtype' => 'textfield',
), '', true, true);
$collection['friendly_url_suffix']= $xpdo->newObject('modSystemSetting');
$collection['friendly_url_suffix']->fromArray(array (
  'key' => 'friendly_url_suffix',
  'value' => '.html',
  'xtype' => 'textfield',
), '', true, true);
$collection['friendly_urls']= $xpdo->newObject('modSystemSetting');
$collection['friendly_urls']->fromArray(array (
  'key' => 'friendly_urls',
  'value' => '0',
  'xtype' => 'combo-boolean',
), '', true, true);
$collection['mail_check_timeperiod']= $xpdo->newObject('modSystemSetting');
$collection['mail_check_timeperiod']->fromArray(array (
  'key' => 'mail_check_timeperiod',
  'value' => '60',
  'xtype' => 'textfield',
), '', true, true);
$collection['manager_direction']= $xpdo->newObject('modSystemSetting');
$collection['manager_direction']->fromArray(array (
  'key' => 'manager_direction',
  'value' => 'ltr',
  'xtype' => 'textfield',
), '', true, true);
$collection['manager_lang_attribute']= $xpdo->newObject('modSystemSetting');
$collection['manager_lang_attribute']->fromArray(array (
  'key' => 'manager_lang_attribute',
  'value' => 'en',
  'xtype' => 'textfield',
), '', true, true);
$collection['manager_language']= $xpdo->newObject('modSystemSetting');
$collection['manager_language']->fromArray(array (
  'key' => 'manager_language',
  'value' => 'en',
  'xtype' => 'combo-language',
), '', true, true);
$collection['manager_layout']= $xpdo->newObject('modSystemSetting');
$collection['manager_layout']->fromArray(array (
  'key' => 'manager_layout',
  'value' => '4',
  'xtype' => 'textfield',
), '', true, true);
$collection['manager_theme']= $xpdo->newObject('modSystemSetting');
$collection['manager_theme']->fromArray(array (
  'key' => 'manager_theme',
  'value' => 'default',
  'xtype' => 'textfield',
), '', true, true);
$collection['modx_charset']= $xpdo->newObject('modSystemSetting');
$collection['modx_charset']->fromArray(array (
  'key' => 'modx_charset',
  'value' => 'UTF-8',
  'xtype' => 'combo-charset',
), '', true, true);
$collection['new_file_permissions']= $xpdo->newObject('modSystemSetting');
$collection['new_file_permissions']->fromArray(array (
  'key' => 'new_file_permissions',
  'value' => '0644',
  'xtype' => 'textfield',
), '', true, true);
$collection['new_folder_permissions']= $xpdo->newObject('modSystemSetting');
$collection['new_folder_permissions']->fromArray(array (
  'key' => 'new_folder_permissions',
  'value' => '0755',
  'xtype' => 'textfield',
), '', true, true);
$collection['number_of_logs']= $xpdo->newObject('modSystemSetting');
$collection['number_of_logs']->fromArray(array (
  'key' => 'number_of_logs',
  'value' => '100',
  'xtype' => 'textfield',
), '', true, true);
$collection['number_of_messages']= $xpdo->newObject('modSystemSetting');
$collection['number_of_messages']->fromArray(array (
  'key' => 'number_of_messages',
  'value' => '30',
  'xtype' => 'textfield',
), '', true, true);
$collection['number_of_results']= $xpdo->newObject('modSystemSetting');
$collection['number_of_results']->fromArray(array (
  'key' => 'number_of_results',
  'value' => '20',
  'xtype' => 'textfield',
), '', true, true);
$collection['old_template']= $xpdo->newObject('modSystemSetting');
$collection['old_template']->fromArray(array (
  'key' => 'old_template',
  'value' => '',
  'xtype' => 'textfield',
), '', true, true);
$collection['publish_default']= $xpdo->newObject('modSystemSetting');
$collection['publish_default']->fromArray(array (
  'key' => 'publish_default',
  'value' => '0',
  'xtype' => 'combo-boolean',
), '', true, true);
$collection['rb_base_dir']= $xpdo->newObject('modSystemSetting');
$collection['rb_base_dir']->fromArray(array (
  'key' => 'rb_base_dir',
  'value' => '',
  'xtype' => 'textfield',
), '', true, true);
$collection['rb_base_url']= $xpdo->newObject('modSystemSetting');
$collection['rb_base_url']->fromArray(array (
  'key' => 'rb_base_url',
  'value' => '',
  'xtype' => 'textfield',
), '', true, true);
$collection['request_param_alias']= $xpdo->newObject('modSystemSetting');
$collection['request_param_alias']->fromArray(array (
  'key' => 'request_param_alias',
  'value' => 'q',
  'xtype' => 'textfield',
), '', true, true);
$collection['request_param_id']= $xpdo->newObject('modSystemSetting');
$collection['request_param_id']->fromArray(array (
  'key' => 'request_param_id',
  'value' => 'id',
  'xtype' => 'textfield',
), '', true, true);
$collection['resolve_hostnames']= $xpdo->newObject('modSystemSetting');
$collection['resolve_hostnames']->fromArray(array (
  'key' => 'resolve_hostnames',
  'value' => '0',
  'xtype' => 'combo-boolean',
), '', true, true);
$collection['search_default']= $xpdo->newObject('modSystemSetting');
$collection['search_default']->fromArray(array (
  'key' => 'search_default',
  'value' => '1',
  'xtype' => 'combo-boolean',
), '', true, true);
$collection['server_offset_time']= $xpdo->newObject('modSystemSetting');
$collection['server_offset_time']->fromArray(array (
  'key' => 'server_offset_time',
  'value' => '0',
  'xtype' => 'textfield',
), '', true, true);
$collection['server_protocol']= $xpdo->newObject('modSystemSetting');
$collection['server_protocol']->fromArray(array (
  'key' => 'server_protocol',
  'value' => 'http',
  'xtype' => 'textfield',
), '', true, true);
$collection['session_cookie_domain']= $xpdo->newObject('modSystemSetting');
$collection['session_cookie_domain']->fromArray(array (
  'key' => 'session_cookie_domain',
  'value' => 'localhost',
  'xtype' => 'textfield',
), '', true, true);
$collection['session_cookie_lifetime']= $xpdo->newObject('modSystemSetting');
$collection['session_cookie_lifetime']->fromArray(array (
  'key' => 'session_cookie_lifetime',
  'value' => '604800',
  'xtype' => 'textfield',
), '', true, true);
$collection['session_cookie_path']= $xpdo->newObject('modSystemSetting');
$collection['session_cookie_path']->fromArray(array (
  'key' => 'session_cookie_path',
  'value' => '/',
  'xtype' => 'textfield',
), '', true, true);
$collection['session_cookie_secure']= $xpdo->newObject('modSystemSetting');
$collection['session_cookie_secure']->fromArray(array (
  'key' => 'session_cookie_secure',
  'value' => '0',
  'xtype' => 'combo-boolean',
), '', true, true);
$collection['session_handler_class']= $xpdo->newObject('modSystemSetting');
$collection['session_handler_class']->fromArray(array (
  'key' => 'session_handler_class',
  'value' => 'modSessionHandler',
  'xtype' => 'textfield',
), '', true, true);
$collection['session_name']= $xpdo->newObject('modSystemSetting');
$collection['session_name']->fromArray(array (
  'key' => 'session_name',
  'value' => 'modxcmssession',
  'xtype' => 'textfield',
), '', true, true);
$collection['set_header']= $xpdo->newObject('modSystemSetting');
$collection['set_header']->fromArray(array (
  'key' => 'set_header',
  'value' => '1',
  'xtype' => 'combo-boolean',
), '', true, true);
$collection['settings_version']= $xpdo->newObject('modSystemSetting');
$collection['settings_version']->fromArray(array (
  'key' => 'settings_version',
  'value' => '2.0.0-alpha-2',
  'xtype' => 'textfield',
), '', true, true);
$collection['show_preview']= $xpdo->newObject('modSystemSetting');
$collection['show_preview']->fromArray(array (
  'key' => 'show_preview',
  'value' => '0',
  'xtype' => 'combo-boolean',
), '', true, true);
$collection['signupemail_message']= $xpdo->newObject('modSystemSetting');
$collection['signupemail_message']->fromArray(array (
  'key' => 'signupemail_message',
  'value' => 'Hello [[+uid]]

    Here are your login details for [[+sname]] Content Manager:

    Username: [[+uid]]
    Password: [[+pwd]]

    Once you log into the Content Manager at [[+surl]], you can change your password.

    Regards,
    Site Administrator',
  'xtype' => 'textarea',
), '', true, true);
$collection['site_id']= $xpdo->newObject('modSystemSetting');
$collection['site_id']->fromArray(array (
  'key' => 'site_id',
  'value' => '',
  'xtype' => 'textfield',
), '', true, true);
$collection['site_name']= $xpdo->newObject('modSystemSetting');
$collection['site_name']->fromArray(array (
  'key' => 'site_name',
  'value' => 'My MODx Site',
  'xtype' => 'textfield',
), '', true, true);
$collection['site_start']= $xpdo->newObject('modSystemSetting');
$collection['site_start']->fromArray(array (
  'key' => 'site_start',
  'value' => '1',
  'xtype' => 'textfield',
), '', true, true);
$collection['site_status']= $xpdo->newObject('modSystemSetting');
$collection['site_status']->fromArray(array (
  'key' => 'site_status',
  'value' => '1',
  'xtype' => 'combo-boolean',
), '', true, true);
$collection['site_unavailable_message']= $xpdo->newObject('modSystemSetting');
$collection['site_unavailable_message']->fromArray(array (
  'key' => 'site_unavailable_message',
  'value' => 'The site is currently unavailable',
  'xtype' => 'textfield',
), '', true, true);
$collection['strip_image_paths']= $xpdo->newObject('modSystemSetting');
$collection['strip_image_paths']->fromArray(array (
  'key' => 'strip_image_paths',
  'value' => '1',
  'xtype' => 'combo-boolean',
), '', true, true);
$collection['theme_refresher']= $xpdo->newObject('modSystemSetting');
$collection['theme_refresher']->fromArray(array (
  'key' => 'theme_refresher',
  'value' => '',
  'xtype' => 'textfield',
), '', true, true);
$collection['tinymce_custom_buttons1']= $xpdo->newObject('modSystemSetting');
$collection['tinymce_custom_buttons1']->fromArray(array (
  'key' => 'tinymce_custom_buttons1',
  'value' => 'undo,redo,selectall,separator,pastetext,pasteword,separator,search,replace,separator,nonbreaking,hr,charmap,separator,image,link,unlink,anchor,media,separator,cleanup,removeformat,separator,fullscreen,print,code,help',
  'xtype' => 'textfield',
), '', true, true);
$collection['tinymce_custom_buttons2']= $xpdo->newObject('modSystemSetting');
$collection['tinymce_custom_buttons2']->fromArray(array (
  'key' => 'tinymce_custom_buttons2',
  'value' => 'bold,italic,underline,strikethrough,sub,sup,separator,bullist,numlist,outdent,indent,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,styleselect,formatselect,separator,styleprops',
  'xtype' => 'textfield',
), '', true, true);
$collection['tinymce_custom_plugins']= $xpdo->newObject('modSystemSetting');
$collection['tinymce_custom_plugins']->fromArray(array (
  'key' => 'tinymce_custom_plugins',
  'value' => 'style,advimage,advlink,searchreplace,print,contextmenu,paste,fullscreen,noneditable,nonbreaking,xhtmlxtras,visualchars,media',
  'xtype' => 'textfield',
), '', true, true);
$collection['tinymce_editor_theme']= $xpdo->newObject('modSystemSetting');
$collection['tinymce_editor_theme']->fromArray(array (
  'key' => 'tinymce_editor_theme',
  'value' => 'editor',
  'xtype' => 'textfield',
), '', true, true);
$collection['top_howmany']= $xpdo->newObject('modSystemSetting');
$collection['top_howmany']->fromArray(array (
  'key' => 'top_howmany',
  'value' => '10',
  'xtype' => 'textfield',
), '', true, true);
$collection['track_visitors']= $xpdo->newObject('modSystemSetting');
$collection['track_visitors']->fromArray(array (
  'key' => 'track_visitors',
  'value' => '0',
  'xtype' => 'combo-boolean',
), '', true, true);
$collection['udperms_allowroot']= $xpdo->newObject('modSystemSetting');
$collection['udperms_allowroot']->fromArray(array (
  'key' => 'udperms_allowroot',
  'value' => '0',
  'xtype' => 'combo-boolean',
), '', true, true);
$collection['unauthorized_page']= $xpdo->newObject('modSystemSetting');
$collection['unauthorized_page']->fromArray(array (
  'key' => 'unauthorized_page',
  'value' => '1',
  'xtype' => 'textfield',
), '', true, true);
$collection['upload_files']= $xpdo->newObject('modSystemSetting');
$collection['upload_files']->fromArray(array (
  'key' => 'upload_files',
  'value' => 'txt,php,html,htm,xml,js,css,cache,zip,gz,rar,z,tgz,tar,htaccess,mp3,mp4,aac,wav,au,wmv,avi,mpg,mpeg,pdf,doc,xls,txt',
  'xtype' => 'textfield',
), '', true, true);
$collection['upload_flash']= $xpdo->newObject('modSystemSetting');
$collection['upload_flash']->fromArray(array (
  'key' => 'upload_flash',
  'value' => 'swf,fla',
  'xtype' => 'textfield',
), '', true, true);
$collection['upload_images']= $xpdo->newObject('modSystemSetting');
$collection['upload_images']->fromArray(array (
  'key' => 'upload_images',
  'value' => 'jpg,jpeg,png,gif,psd,ico,bmp',
  'xtype' => 'textfield',
), '', true, true);
$collection['upload_maxsize']= $xpdo->newObject('modSystemSetting');
$collection['upload_maxsize']->fromArray(array (
  'key' => 'upload_maxsize',
  'value' => '1048576',
  'xtype' => 'textfield',
), '', true, true);
$collection['upload_media']= $xpdo->newObject('modSystemSetting');
$collection['upload_media']->fromArray(array (
  'key' => 'upload_media',
  'value' => 'mp3,wav,au,wmv,avi,mpg,mpeg',
  'xtype' => 'textfield',
), '', true, true);
$collection['use_alias_path']= $xpdo->newObject('modSystemSetting');
$collection['use_alias_path']->fromArray(array (
  'key' => 'use_alias_path',
  'value' => '0',
  'xtype' => 'combo-boolean',
), '', true, true);
$collection['use_browser']= $xpdo->newObject('modSystemSetting');
$collection['use_browser']->fromArray(array (
  'key' => 'use_browser',
  'value' => '1',
  'xtype' => 'combo-boolean',
), '', true, true);
$collection['use_captcha']= $xpdo->newObject('modSystemSetting');
$collection['use_captcha']->fromArray(array (
  'key' => 'use_captcha',
  'value' => '0',
  'xtype' => 'combo-boolean',
), '', true, true);
$collection['use_editor']= $xpdo->newObject('modSystemSetting');
$collection['use_editor']->fromArray(array (
  'key' => 'use_editor',
  'value' => '1',
  'xtype' => 'combo-boolean',
), '', true, true);
$collection['use_udperms']= $xpdo->newObject('modSystemSetting');
$collection['use_udperms']->fromArray(array (
  'key' => 'use_udperms',
  'value' => '1',
  'xtype' => 'combo-boolean',
), '', true, true);
$collection['webpwdreminder_message']= $xpdo->newObject('modSystemSetting');
$collection['webpwdreminder_message']->fromArray(array (
  'key' => 'webpwdreminder_message',
  'value' => 'Hello [[+uid]]

    To active you new password click the following link:

    [[+surl]]

    If successful you can use the following password to login:

    Password:[[+pwd]]

    If you did not request this email then please ignore it.

    Regards,
    Site Administrator',
  'xtype' => 'textarea',
), '', true, true);
$collection['websignupemail_message']= $xpdo->newObject('modSystemSetting');
$collection['websignupemail_message']->fromArray(array (
  'key' => 'websignupemail_message',
  'value' => 'Hello [[+uid]]

    Here are your login details for [[+sname]]:

    Username: [[+uid]]
    Password: [[+pwd]]

    Once you log into [[+sname]] at [[+surl]], you can change your password.

    Regards,
    Site Administrator',
  'xtype' => 'textarea',
), '', true, true);
$collection['which_editor']= $xpdo->newObject('modSystemSetting');
$collection['which_editor']->fromArray(array (
  'key' => 'which_editor',
  'value' => 'TinyMCE',
  'xtype' => 'combo-rte',
), '', true, true);
