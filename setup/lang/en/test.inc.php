<?php
/**
 * Test-related English Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['test_config_file'] = 'Checking if <span class="mono">%s</span> exists and is writable: ';
$_lang['test_config_file_nw'] = 'For new Linux/Unix installs, please create a blank file named <span class="mono">%s.inc.php</span> in your MODx core <span class="mono">config/</span> directory with permissions set to be writable by PHP.';
$_lang['test_db_check'] = 'Creating connection to the database: ';
$_lang['test_db_check_conn'] = 'Check the connection details and try again.';
$_lang['test_db_failed'] = 'Database connection failed!';
$_lang['test_db_setup_create'] = 'Setup will attempt to create the database.';
$_lang['test_dependencies'] = 'Checking PHP for zlib dependency: ';
$_lang['test_dependencies_fail_zlib'] = 'Your PHP installation does not have the "zlib" extension installed. This extension is necessary for MODx to run. Please enable it to continue.';
$_lang['test_directory_exists'] = 'Checking if <span class="mono">%s</span> directory exists: ';
$_lang['test_directory_writable'] = 'Checking if <span class="mono">%s</span> directory is writable: ';
$_lang['test_memory_limit'] = 'Checking if memory limit is set to at least 24M: ';
$_lang['test_memory_limit_fail'] = 'MODx found your memory_limit setting to be below the recommended setting of 24M. MODx attempted to set the memory_limit to 24M, but was unsuccessful. Please set the memory_limit setting in your php.ini file to at least 24M or higher before proceeding. If you are still having trouble (such as getting a blank white screen on install), set to 32M, 64M or higher.';
$_lang['test_memory_limit_success'] = 'OK! Set to %s';
$_lang['test_mysql_version_5051'] = 'MODx will have issues on your MySQL version (%s), because of the many bugs related to the PDO drivers on this version. Please upgrade MySQL to patch these problems. Even if you choose not to use MODx, it is recommended you upgrade to this version for the security and stability of your own website.';
$_lang['test_mysql_version_client_nf'] = 'Could not detect MySQL client version!';
$_lang['test_mysql_version_client_nf_msg'] = 'MODx could not detect your MySQL client version via mysql_get_client_info(). Please manually make sure that your MySQL client version is at least 4.1.20 before proceeding.';
$_lang['test_mysql_version_client_start'] = 'Checking MySQL client version:';
$_lang['test_mysql_version_fail'] = 'You are running on MySQL %s, and MODx Revolution requires MySQL 4.1.20 or later. Please upgrade MySQL to at least 4.1.20.';
$_lang['test_mysql_version_server_nf'] = 'Could not detect MySQL server version!';
$_lang['test_mysql_version_server_nf_msg'] = 'MODx could not detect your MySQL server version via mysql_get_server_info(). Please manually make sure that your MySQL server version is at least 4.1.20 before proceeding.';
$_lang['test_mysql_version_server_start'] = 'Checking MySQL server version:';
$_lang['test_mysql_version_success'] = 'OK! Running: %s';
$_lang['test_php_version_fail'] = 'You are running on PHP %s, and MODx Revolution requires PHP 5.1.1 or later. Please upgrade PHP to at least 5.1.1. MODx recommends upgrading to 5.3.0.';
$_lang['test_php_version_516'] = 'MODx will have issues on your PHP version (%s), because of the many bugs related to the PDO drivers on this version. Please upgrade PHP to version 5.2.0 or higher, which patches these problems. MODx recommends upgrading to 5.3.0. Even if you choose not to use MODx, it is recommended you upgrade to this version for the security and stability of your own website.';
$_lang['test_php_version_start'] = 'Checking PHP version:';
$_lang['test_php_version_success'] = 'OK! Running: %s';
$_lang['test_sessions_start'] = 'Checking if sessions are properly configured:';
$_lang['test_table_prefix'] = 'Checking table prefix `%s`: ';
$_lang['test_table_prefix_inuse'] = 'Table prefix is already in use in this database!';
$_lang['test_table_prefix_inuse_desc'] = 'Setup couldn\'t install into the selected database, as it already contains tables with the prefix you specified. Please choose a new table_prefix, and run Setup again.';
$_lang['test_table_prefix_nf'] = 'Table prefix does not exist in this database!';
$_lang['test_table_prefix_nf_desc'] = 'Setup couldn\'t install into the selected database, as it does not contain existing tables with the prefix you specified to be upgraded. Please choose an existing table_prefix, and run Setup again.';
$_lang['test_zip_memory_limit'] = 'Checking if memory limit is set to at least 24M for zip extensions: ';
$_lang['test_zip_memory_limit_fail'] = 'MODx found your memory_limit setting to be below the recommended setting of 24M. MODx attempted to set the memory_limit to 24M, but was unsuccessful. Please set the memory_limit setting in your php.ini file to 24M or higher before proceeding, so that the zip extensions can work properly.';