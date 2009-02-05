<?php
$_lang['area'] = 'Area';
$_lang['area_authentication'] = 'Authentication and Security';
$_lang['area_caching'] = 'Caching';
$_lang['area_file'] = 'File System';
$_lang['area_furls'] = 'Friendly URL';
$_lang['area_language'] = 'Lexicon and Language';
$_lang['area_manager'] = 'Back-end Manager';
$_lang['area_session'] = 'Session and Cookie';
$_lang['area_lexicon_string'] = 'Area Lexicon Entry';
$_lang['area_lexicon_string_msg'] = 'Enter the key of the lexicon entry for the area here. If there is no lexicon entry, it will just display the area key.<br />Core Areas:<ul><li>authentication</li><li>caching</li><li>file</li><li>furls</li><li>language</li><li>manager</li><li>session</li><li>site</li><li>system</li></ul>';
$_lang['area_site'] = 'Site';
$_lang['area_system'] = 'System and Server';
$_lang['areas'] = 'Areas';
$_lang['namespace'] = 'Namespace';
$_lang['namespace_filter'] = 'Filter by namespace...';
$_lang['search_by_key'] = 'Search by key...';
$_lang['setting_create'] = 'Create New Setting';
$_lang['setting_err'] = 'Please check your data for the following fields: ';
$_lang['setting_err_ae'] = 'Setting with that key already exists. Please specify another key name.';
$_lang['setting_err_nf'] = 'Setting not found.';
$_lang['setting_err_ns'] = 'Setting not specified';
$_lang['setting_err_remove'] = 'An error occurred while trying to remove the setting.';
$_lang['setting_err_save'] = 'An error occurred while trying to save the setting.';
$_lang['setting_err_invalid_document'] = 'There is no document with ID %d. Please specify an existing document.';
$_lang['setting_remove'] = 'Remove Setting';
$_lang['setting_remove_confirm'] = 'Are you sure you want to remove this setting? This might break your MODx installation.';
$_lang['setting_update'] = 'Update Setting';
$_lang['settings_after_install'] = 'As this is a new install, you are required to control these settings, and change any that you may wish to. After you\'ve controlled the settings, press \'Save\' to update the settings database.<br /><br />';
$_lang['settings_desc'] = 'Here you can set general preferences and configuration settings for the MODx manager interface, as well as how your MODx site runs. Double-click on the value column for the setting you\'d like to edit to dynamically edit via the grid, or right-click on a setting for more options. You can also click the "+" sign for a description of the setting.';
$_lang['settings_furls'] = 'Friendly URLs';
$_lang['settings_misc'] = 'Miscellaneous';
$_lang['settings_site'] = 'Site';
$_lang['settings_ui'] = 'Interface &amp; Features';
$_lang['settings_users'] = 'User';
$_lang['system_settings'] = 'System Settings';

// user settings
$_lang['setting_allow_mgr_access'] = 'Manager Interface Access';
$_lang['setting_allow_mgr_access_desc'] = 'Select this option to enable or disable access to the manager interface. <strong>NOTE: If this option is set to no then the user will be redirected the the Manager Login Startup or Site Start web page.</strong>';

$_lang['setting_failed_login'] = 'Failed Login Attempts';
$_lang['setting_failed_login_desc'] = 'Here you can enter the number of failed login attempts that are allowed before a user is blocked.';

$_lang['setting_login_allowed_days'] = 'Allowed Days';
$_lang['setting_login_allowed_days_desc'] = 'Select the days that this user is allowed to login.';

$_lang['setting_login_allowed_ip'] = 'Allowed IP Address';
$_lang['setting_login_allowed_ip_desc'] = 'Enter the IP addresses that this user is allowed to login from. <strong>NOTE: Separate multiple IP addresses with a comma (,)</strong>';

$_lang['setting_login_homepage'] = 'Login Home Page';
$_lang['setting_login_homepage_desc'] = 'Enter the ID of the document you want to send user to after he/she has logged in. <strong>NOTE: make sure the ID you enter belongs to an existing document, and that it has been published and is accessible by this user!</strong>';

// system settings
$_lang['setting_allow_duplicate_alias'] = 'Allow duplicate aliases';
$_lang['setting_allow_duplicate_alias_desc'] = 'If set to \'yes\', this will allow duplicate aliases to be saved. <strong>NOTE: This option should be used with \'Friendly alias path\' option set to \'Yes\' in order to avoid problems when referencing a resource.</strong>';

$_lang['setting_allow_tags_in_post'] = 'Allow HTML Tags in POST';
$_lang['setting_allow_tags_in_post_desc'] = '';

$_lang['setting_auto_menuindex'] = 'Menu indexing default';
$_lang['setting_auto_menuindex_desc'] = 'Select \'Yes\' to turn on automatic menu index incrementing by default.';

$_lang['setting_automatic_alias'] = 'Automatically generate alias';
$_lang['setting_automatic_alias_desc'] = 'Select \'yes\' to have the system automatically generate an alias based on the document\'s page title when saving.';


$_lang['setting_blocked_minutes'] = 'Blocked Minutes';
$_lang['setting_blocked_minutes_desc'] = 'Here you can enter the number of minutes that a user will be blocked for if they reach their maximum number of allowed failed login attempts. Please enter this value as numbers only (no commas, spaces etc.)';

$_lang['setting_cache_db'] = 'Enable Database Cache';
$_lang['setting_cache_db_desc'] = 'When enabled, objects and raw result sets from SQL queries are cached to significantly reduce database loads.';

$_lang['setting_cache_db_expires'] = 'Expiration Time for DB Cache';
$_lang['setting_cache_db_expires_desc'] = '';

$_lang['setting_cache_default'] = 'Cacheable default';
$_lang['setting_cache_default_desc'] = 'Select \'Yes\' to make all new resources cacheable by default.';
$_lang['setting_cache_default_err'] = 'Please state whether or not you want documents to be cached by default.';

$_lang['setting_cache_disabled'] = 'Disable Global Cache Options';
$_lang['setting_cache_disabled_desc'] = 'Select yes to disable all MODx caching features.';
$_lang['setting_cache_disabled_err'] = 'Please state whether or not you want the cache enabled.';

$_lang['setting_cache_json'] = 'Cache JSON Data';
$_lang['setting_cache_json_desc'] = '';

$_lang['setting_cache_json_expires'] = 'Expiration Time for JSON Cache';
$_lang['setting_cache_json_expires_desc'] = '';

$_lang['setting_cache_lang_js'] = 'Cache Lexicon JS Strings';
$_lang['setting_cache_lang_js_desc'] = 'If set to true, this will use server headers to cache the lexicon strings loaded into JavaScript for the manager interface.';

$_lang['setting_cache_resource'] = 'Enable Partial Resource Cache';
$_lang['setting_cache_resource_desc'] = 'Partial resource caching is configurable by resource when this feature is enabled.  Disabling this feature will disable it globally.';

$_lang['setting_cache_resource_expires'] = 'Expiration Time for Partial Resource Cache';
$_lang['setting_cache_resource_expires_desc'] = '';

$_lang['setting_compress_js'] = 'Use Compressed Javascript Libraries';
$_lang['setting_compress_js_desc'] = 'When this is enabled, MODx will use a compressed version of its custom JavaScript libraries. This greatly reduces load and execution time. Disable only if you are modifying core elements. LEAVE OFF UNTIL 097-final IS RELEASED!';

$_lang['setting_default_template'] = 'Default template';
$_lang['setting_default_template_desc'] = 'Select the default template you wish to use for new documents. You can still select a different template in the document editor, this setting just pre-selects one of your templates for you.';

$_lang['setting_editor_css_path'] = 'Path to CSS file';
$_lang['setting_editor_css_path_desc'] = 'Enter the path to your CSS file that you wish to use within the editor. The best way to enter the path is to enter the path from the root of your server, for example: /assets/site/style.css. If you do not wish to load a style sheet into the editor, leave this field blank.';

$_lang['setting_editor_css_selectors'] = 'CSS Selectors for Editor';
$_lang['setting_editor_css_selectors_desc'] = '';

$_lang['setting_emailsender'] = 'E-mail address';
$_lang['setting_emailsender_desc'] = 'Here you can specify the e-mail address used when sending users their usernames and passwords.';
$_lang['setting_emailsender_err'] = 'Please state the administration email address.';

$_lang['setting_emailsubject'] = 'E-mail subject';
$_lang['setting_emailsubject_desc'] = 'The subject line for the default signup email.';
$_lang['setting_emailsubject_err'] = 'Please state the subject line for the signup email.';

$_lang['setting_error_page'] = 'Error page';
$_lang['setting_error_page_desc'] = 'Enter the ID of the document you want to send users to if they request a document which doesn\'t actually exist. <strong>NOTE: make sure this ID you enter belongs to an existing document, and that it has been published!</strong>';
$_lang['setting_error_page_err'] = 'Please specify a document ID that is the error page.';

$_lang['setting_failed_login_attempts'] = 'Failed Login Attempts';
$_lang['setting_failed_login_attempts_desc'] = '';


$_lang['setting_fe_editor_lang'] = 'Front-end Editor Language';
$_lang['setting_fe_editor_lang_desc'] = 'Choose a language for the editor to use when used as a front-end editor.';

$_lang['setting_feed_modx_news'] = 'MODx News Feed URL';
$_lang['setting_feed_modx_news_desc'] = 'Set the URL for the RSS feed for the MODx News panel in the manager.';

$_lang['setting_feed_modx_security'] = 'MODx Security Notices Feed URL';
$_lang['setting_feed_modx_security_desc'] = 'Set the URL for the RSS feed for the MODx Security Notices panel in the manager.';

$_lang['setting_filemanager_path'] = 'File Manager Path';
$_lang['setting_filemanager_path_desc'] = 'IIS often does not populate the document_root setting properly, which is used by the file manager to determine what you can look at. If you\'re having problems using the file manager, make sure this path points to the root of your MODx installation.';
$_lang['setting_filemanager_path_err'] = 'Please state the absoulte document root path for the filemanager.';
$_lang['setting_filemanager_path_err_invalid'] = 'This filemanager directory either does not exist or cannot be accessed. Please state a valid directory or adjust the permissions of this directory.';

$_lang['setting_friendly_alias_urls'] = 'Use friendly aliases';
$_lang['setting_friendly_alias_urls_desc'] = 'If you are using friendly URLs, and the resource has an alias, the alias will always have precedence over the friendly URL. By setting this option to \'yes\', the friendly URL prefix and suffix will also be applied to the alias. For example, if your resource with ID 1 has an alias of `introduction`, and you\'ve set a prefix of `` and a suffix of `.html`, setting this option to `yes` will generate `introduction.html`. If there\'s no alias, MODx will generate `1.html` as link.';

$_lang['setting_friendly_url_prefix'] = 'Prefix for friendly URLs';
$_lang['setting_friendly_url_prefix_desc'] = 'Here you can specify the prefix to use for friendly URLs. For example, a prefix setting of \'page\' will turn the URL /index.php?id=2 to the friendly URL /page2.html (assuming the suffix is set to .html). This way you can specify what your users (and search engines) see for links on your site.';

$_lang['setting_friendly_url_suffix'] = 'Suffix for friendly URLs';
$_lang['setting_friendly_url_suffix_desc'] = 'Here you can specify the suffix for Friendly URLs. Specifying \'.html\' will append .html to all your friendly URLs.';


$_lang['setting_friendly_urls'] = 'Use friendly URLs';
$_lang['setting_friendly_urls_desc'] = 'This allows you to use search engine friendly URLs with MODx. Please note, this only works for MODx installations running on Apache, and you\'ll need to write a .htaccess file for this to work. See the .htaccess file included in the distribution for more info.';
$_lang['setting_friendly_urls_err'] = 'Please state whether or not you want to use friendly URLs.';

$_lang['setting_mail_check_timeperiod'] = 'Mail Check Time Period';
$_lang['setting_mail_check_timeperiod_desc'] = 'How often to check for new mail messages in the Manager, in seconds.';
$_lang['setting_mail_check_timeperiod_err'] = 'Please state how often you want to check for new messages.';

$_lang['setting_manager_direction'] = 'Manager Text Direction';
$_lang['setting_manager_direction_desc'] = 'Choose the direction that the text will be rendered in the Manager, left to right or right to left.';

$_lang['setting_manager_lang_attribute'] = 'Manager HTML and XML Language Attribute';
$_lang['setting_manager_lang_attribute_desc'] = 'Enter the language code that best fits with your chosen manager language, this will ensure that the browser can present content in the best format for you.';

$_lang['setting_manager_language'] = 'Language';
$_lang['setting_manager_language_desc'] = 'Select the language for the MODx Content Manager.';

$_lang['setting_manager_login_start'] = 'Manager Login Startup';
$_lang['setting_manager_login_start_desc'] = 'Enter the ID of the document you want to send the user to after he/she has logged into the manager. <strong>NOTE: make sure the ID you\'ve enter belongs to an existing document, and that it has been published and is accessible by this user!</strong>';

$_lang['setting_manager_theme'] = 'Manager Theme';
$_lang['setting_manager_theme_desc'] = 'Select the Theme for the Content Manager.';

$_lang['setting_modRequest.class'] = 'Request Handler Class';
$_lang['setting_modRequest.class_desc'] = '';

$_lang['setting_modx_charset'] = 'Character encoding';
$_lang['setting_modx_charset_desc'] = 'Please select which character encoding you wish to use in the manager. Please note that MODx has been tested with a number of these encodings, but not all of them. For most languages, the default setting of UTF-8 is preferrable.';

$_lang['setting_new_file_permissions'] = 'New File Permissions';
$_lang['setting_new_file_permissions_desc'] = 'When uploading a new file in the File Manager, the File Manager will attempt to change the file permissions to those entered in this setting. This may not work on some setups, such as IIS, in which case you will need to manually change the permissions.';

$_lang['setting_new_folder_permissions'] = 'New Folder Permissions';
$_lang['setting_new_folder_permissions_desc'] = 'When creating a new folder in the File Manager, the File Manager will attempt to change the folder permissions to those entered in this setting. This may not work on some setups, such as IIS, in which case you will need to manually change the permissions.';

$_lang['setting_number_of_logs'] = 'Number of log entries';
$_lang['setting_number_of_logs_desc'] = 'Enter the number of log entries shown per page when you browse the Audit trail.';
$_lang['setting_number_of_logs_err'] = 'Please state the number of log entries you wish to show.';

$_lang['setting_number_of_messages'] = 'Number of messages';
$_lang['setting_number_of_messages_desc'] = 'Enter the number of messages to show in inbox when viewing messages.';
$_lang['setting_number_of_messages_err'] = 'Please state how many messages you would like to show in the inbox.';

$_lang['setting_number_of_results'] = 'Number of Results';
$_lang['setting_number_of_results_desc'] = 'Enter the number of results to show in the data grid when viewing listings and search results.';
$_lang['setting_number_of_results_err'] = 'Please state the default number of results to view in search requests.';

$_lang['setting_publish_default'] = 'Published default';
$_lang['setting_publish_default_desc'] = 'Select \'Yes\' to make all new resources published by default.';
$_lang['setting_publish_default_err'] = 'Please state whether or not you want documents to be published by default.';

$_lang['setting_rb_base_dir'] = 'Resource path';
$_lang['setting_rb_base_dir_desc'] = 'Enter the physical path to the resource directory. This setting is usually automatically generated. If you\'re using IIS, however, MODx may not be able to work the path out on its own, causing the Resource Browser to show an error. In that case, you can enter the path to the images directory here (the path as you\'d see it in Windows Explorer). <strong>NOTE:</strong> The resource directory must contain the subfolders images, files, flash and media in order for the resource browser to function correctly.';
$_lang['setting_rb_base_dir_err'] = 'Please state the resource browser base directory.';
$_lang['setting_rb_base_dir_err_invalid'] = 'This resource directory either does not exist or cannot be accessed. Please state a valid directory or adjust the permissions of this directory.';

$_lang['setting_rb_base_url'] = 'Resource URL';
$_lang['setting_rb_base_url_desc'] = 'Enter the virtual path to resource directory. This setting is usually automatically generated. If you\'re using IIS, however, MODx may not be able to work the URL out on it\'s own, causing the Resource Browser to show an error. In that case, you can enter the URL to the images directory here (the URL as you\'d enter it on Internet Explorer).';
$_lang['setting_rb_base_url_err'] = 'Please state the resource browser base URL.';

$_lang['setting_resolve_hostnames'] = 'Resolve hostnames';
$_lang['setting_resolve_hostnames_desc'] = 'Do you want MODx to try to resolve your visitors\' hostnames when they visit your site? Resolving hostnames may create some extra server load, although your visitors won\'t notice this in any way.';

$_lang['setting_search_default'] = 'Searchable default';
$_lang['setting_search_default_desc'] = 'Select \'Yes\' to make all new resources searchable by default.';
$_lang['setting_search_default_err'] = 'Please specify whether or not you want documents to be searchable by default.';

$_lang['setting_server_offset_time'] = 'Server offset time';
$_lang['setting_server_offset_time_desc'] = 'Select the number of hours time difference between where you are and where the server is. Current time on server is <strong>[%s]</strong>, current time on server using the currently saved offset is <strong>[%s]</strong>.';

$_lang['setting_server_protocol'] = 'Server type';
$_lang['setting_server_protocol_desc'] = 'If your site is on a https connection, please specify so here.';
$_lang['setting_server_protocol_err'] = 'Please specify whether or not your site is a secure site.';
$_lang['setting_server_protocol_http'] = 'http';
$_lang['setting_server_protocol_https'] = 'https';

$_lang['setting_session_cookie_domain'] = 'Session Cookie Domain';
$_lang['setting_session_cookie_domain_desc'] = 'Use this setting to customize the session cookie domain.';

$_lang['setting_session_cookie_lifetime'] = 'Session Cookie Lifetime';
$_lang['setting_session_cookie_lifetime_desc'] = 'Use this setting to customize the session cookie lifetime in seconds.  This is used to set the lifetime of a client session cookie when they choose the \'remember me\' option on login.';

$_lang['setting_session_cookie_path'] = 'Session Cookie Path';
$_lang['setting_session_cookie_path_desc'] = 'Use this setting to customize the cookie path for identifying site specific session cookies.';

$_lang['setting_session_cookie_secure'] = 'Session Cookie Secure';
$_lang['setting_session_cookie_secure_desc'] = 'Enable this setting to use secure session cookies.';

$_lang['setting_session_handler_class'] = 'Session Handler Classname';
$_lang['setting_session_handler_class_desc'] = 'For database managed sessions, use \'modSessionHandler\'.  Leave this blank to use standard PHP session management.';

$_lang['setting_session_name'] = 'Session Name';
$_lang['setting_session_name_desc'] = 'Use this setting to customize the session name used for the sessions in MODx.';

$_lang['setting_settings_version'] = 'Settings Version';
$_lang['setting_settings_version_desc'] = '';

$_lang['setting_set_header'] = '';
$_lang['setting_set_header_desc'] = '';

$_lang['setting_show_preview'] = 'Show Resource Preview';
$_lang['setting_show_preview_desc'] = '';
$_lang['setting_show_preview_err'] = 'Please state whether or not you want the document preview window.';

$_lang['setting_signupemail_message'] = 'Signup e-mail';
$_lang['setting_signupemail_message_desc'] = 'Here you can set the message sent to your users when you create an account for them and let MODx send them an e-mail containing their username and password. <br /><strong>Note:</strong> The following placeholders are replaced by the Content Manager when the message is sent: <br /><br />[[+sname]] - Name of your web site, <br />[[+saddr]] - Your web site email address, <br />[[+surl]] - Your site url, <br />[[+uid]] - User\'s Login name or id, <br />[[+pwd]] - User\'s password, <br />[[+ufn]] - User\'s full name. <br /><br /><strong>Leave the [[+uid]] and [[+pwd]] in the e-mail, or else the username and password won\'t be sent in the mail and your users won\'t know their username or password!</strong>';
$_lang['setting_signupemail_message_default'] = 'Hello [[+uid]] \n\nHere are your login details for [[+sname]] Content Manager:\n\nUsername: [[+uid]]\nPassword: [[+pwd]]\n\nOnce you log into the Content Manager ([[+surl]]), you can change your password.\n\nRegards,\nSite Administrator';

$_lang['setting_site_id'] = '';
$_lang['setting_site_id_desc'] = '';

$_lang['setting_site_name'] = 'Site name';
$_lang['setting_site_name_desc'] = 'Enter the name of your site here.';
$_lang['setting_site_name_err']  = 'Please enter a site name.';

$_lang['setting_site_start'] = 'Site start';
$_lang['setting_site_start_desc'] = 'Enter the ID of the document you want to use as homepage here. <strong>NOTE: make sure this ID you enter belongs to an existing document, and that it has been published!</strong>';
$_lang['setting_site_start'] = 'Please specify a document ID that is the site start.';

$_lang['setting_site_status'] = 'Site status';
$_lang['setting_site_status_desc'] = 'Select \'Online\' to publish your site on the web. If you select \'Offline\', your visitors will see the \'Site unavailable message\', and won\'t be able to browse the site.';
$_lang['setting_site_status_err'] = 'Please select whether or not the site is online or offline.';

$_lang['setting_site_unavailable_message'] = 'Site unavailable message';
$_lang['setting_site_unavailable_message_desc'] = 'Message to show when the site is offline or if an error occurs. <strong>Note: This message will only be displayed if the Site unavailable page option is not set.</strong>';

$_lang['setting_site_unavailable_page'] = 'Site unavailable page';
$_lang['setting_site_unavailable_page_desc'] = 'Enter the ID of the document you want to use as an offline page here. <strong>NOTE: make sure this ID you enter belongs to an existing document, and that it has been published!</strong>';
$_lang['setting_site_unavailable_page_err'] = 'Please specify the document ID for the site unavailable page.';

$_lang['setting_strip_image_paths'] = 'Rewrite browser paths?';
$_lang['setting_strip_image_paths_desc'] = 'If this is set to \'No\', MODx  will write file browser resource src\'s (images, files, flash, etc.) as absolute URLs. Relative URLs are helpful should you wish to move your MODx install, e.g., from a staging site to a production site. If you have no idea what this means, it\'s best just to leave it set to \'Yes\'.';

$_lang['setting_top_howmany'] = 'Top how many';
$_lang['setting_top_howmany_desc'] = 'When viewing reports, how large should the \'Top ...\' lists be?';
$_lang['setting_top_howmany_err'] = 'Please state how many records you would like to show.';

$_lang['setting_track_visitors'] = 'Log visits (stats)';
$_lang['setting_track_visitors_desc'] = 'This setting has no effect unless you have a visitor tracking or statistics resource installed that supports this setting. Logging visits will allow you to view your site\'s website usage statistics.';

$_lang['setting_udperms_allowroot'] = 'Allow root';
$_lang['setting_udperms_allowroot_desc'] = 'Do you want to allow your users to create new documents in the root of the site? ';

$_lang['setting_unauthorized_page'] = 'Unauthorized page';
$_lang['setting_unauthorized_page_desc'] = 'Enter the ID of the document you want to send users to if they have requested a secured or unauthorized document. <strong>NOTE: make sure the ID you enter belongs to an existing document, and that it has been published and is publicly accessible!</strong>';
$_lang['setting_unauthorized_page_err'] = 'Please specify a document ID for the unauthorized page.';

$_lang['setting_upload_files'] = 'Uploadable File Types';
$_lang['setting_upload_files_desc'] = 'Here you can enter a list of files that can be uploaded into \'assets/files/\' using the Resource Manager. Please enter the extensions for the filetypes, seperated by commas.';

$_lang['setting_upload_flash'] = 'Uploadable Flash Types';
$_lang['setting_upload_flash_desc'] = 'Here you can enter a list of files that can be uploaded into \'assets/flash/\' using the Resource Manager. Please enter the extensions for the flash types, separated by commas.';

$_lang['setting_upload_images'] = 'Uploadable Image Types';
$_lang['setting_upload_images_desc'] = 'Here you can enter a list of files that can be uploaded into \'assets/images/\' using the Resource Manager. Please enter the extensions for the image types, separated by commas.';

$_lang['setting_upload_maxsize'] = 'Maximum upload size';
$_lang['setting_upload_maxsize_desc'] = 'Enter the maximum file size that can be uploaded via the file manager. Upload file size must be entered in bytes. <strong>NOTE: Large files can take a very long time to upload!</strong>';

$_lang['setting_upload_media'] = 'Uploadable Media Types';
$_lang['setting_upload_media_desc'] = 'Here you can enter a list of files that can be uploaded into \'assets/media/\' using the Resource Manager. Please enter the extensions for the media types, separated by commas.';

$_lang['setting_use_alias_path'] = 'Use friendly alias path';
$_lang['setting_use_alias_path_desc'] = 'Setting this option to \'yes\' will display the full path to the document if the document has an alias. For example, if a document with an alias called \'child\' is located inside a container document with an alias called \'parent\', then the full alias path to the document will be displayed as \'/parent/child.html\'.<br /><strong>NOTE: When setting this option to \'Yes\' (turning on alias paths), reference items (such as images, css, javascripts, etc) using the absolute path: e.g., \'/assets/images\' as oppose to \'assets/images\'. By doing so you will prevent the browser (or web server) from appending the relative path to the alias path.</strong>';

$_lang['setting_use_browser'] = 'Enable Resource Browser';
$_lang['setting_use_browser_desc'] = 'Select yes to enable the resource browser. This will allow your users to browse and upload resources such as images, flash and media files on the server.';
$_lang['setting_use_browser_err'] = 'Please state whether or not you want to use the resource browser.';

$_lang['setting_use_editor'] = 'Enable editor';
$_lang['setting_use_editor_desc'] = 'Do you want to enable the rich text editor? If you\'re more comfortable writing HTML, then you can turn the editor off using this setting. Note that this setting applies to all documents and all users!';
$_lang['setting_use_editor_err'] = 'Please state whether or not you want to use an RTE editor.';

$_lang['setting_use_udperms'] = 'Use access permissions';
$_lang['setting_use_udperms_desc'] = 'Access permissions allow you to specify which pages your users are allowed to edit. You\'ll need to assign your users to user groups, your documents to document groups, and then you\'ll need to specify which user groups are allowed to access which document groups. When you first turn this on, only administrators will be allowed to edit any documents.';
$_lang['setting_use_udperms_err'] = 'Please state whether or not you want to use access permissions.';

$_lang['setting_webpwdreminder_message'] = 'Web Reminder Email';
$_lang['setting_webpwdreminder_desc'] = 'Enter a message to be sent to your web users whenever they request a new password via email. The Content Manager will send an e-mail containing their new password and activation information. <br /><strong>Note:</strong> The following placeholders are replaced by the Content Manager when the message is sent: <br /><br />[[+sname]] - Name of your web site, <br />[[+saddr]] - Your web site email address, <br />[[+surl]] - Your site url, <br />[[+uid]] - User\'s Login name or id, <br />[[+pwd]] - User\'s password, <br />[[+ufn]] - User\'s full name. <br /><br /><strong>Leave the [[+uid]] and [[+pwd]] in the e-mail, or else the username and password won\'t be sent in the mail and your users won\'t know their username or password!</strong>';


$_lang['setting_webpwdreminder_message_default'] = 'Hello [[+uid]]\n\nTo active you new password click the following link:\n\n[[+surl]]\n\nIf successful you can use the following password to login:\n\nPassword:[[+pwd]]\n\nIf you did not request this email then please ignore it.\n\nRegrads,\nSite Administrator';

$_lang['setting_websignupemail_messasge'] = 'Web Signup e-mail';
$_lang['setting_websignupemail_message_desc'] = 'Here you can set the message sent to your web users when you create a web account for them and let the Content Manager send them an e-mail containing their username and password. <br /><strong>Note:</strong> The following placeholders are replaced by the Content Manager when the message is sent: <br /><br />[[+sname]] - Name of your web site, <br />[[+saddr]] - Your web site email address, <br />[[+surl]] - Your site url, <br />[[+uid]] - User\'s Login name or id, <br />[[+pwd]] - User\'s password, <br />[[+ufn]] - User\'s full name. <br /><br /><strong>Leave the [[+uid]] and [[+pwd]] in the e-mail, or else the username and password won\'t be sent in the mail and your users won\'t know their username or password!</strong>';
$_lang['setting_websignupemail_message_default'] = 'Hello [[+uid]] \n\nHere are your login details for [[+sname]]:\n\nUsername: [[+uid]]\nPassword: [[+pwd]]\n\nOnce you log into [[+sname]] ([[+surl]]), you can change your password.\n\nRegards,\nSite Administrator';

$_lang['setting_which_editor'] = 'Editor to use';
$_lang['setting_which_editor_desc'] = 'Here you can select which rich text editor you wish to use. You can download and install additional Rich Text editors from the MODx download page.';