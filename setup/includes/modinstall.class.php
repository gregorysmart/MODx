<?php
/*
 * MODx Revolution
 * 
 * Copyright 2006, 2007, 2008 by the MODx Team.
 * All rights reserved.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 */

/**
 * Common classes for the MODx installation and provisioning services.
 *
 * @package setup
 */

/**
 * Provides common functionality and data for installation and provisioning.
 *
 * @package setup
 */
class modInstall {
    var $xpdo = null;
    var $options = array ();
    var $config = array ();
    var $action = '';

    /**#@+
     * The constructor for the modInstall object.
     *
     * @constructor
     * @param array $options An array of configuration options.
     */
    function modInstall($options = array ()) {
        $this->__construct($options);
    }
    /** @ignore */
    function __construct($options = array()) {
    	if (isset ($_REQUEST['action'])) {
            $this->action = $_REQUEST['action'];
        }
        if (is_array($options)) {
            $this->options = $options;
        }
    }
    /**#@- */

    /**
     * Loads the request handler for the setup.
     * @return boolean True if successful.
     */
    function loadRequestHandler($class = 'modInstallRequest') {
        $included = @include dirname(__FILE__).'/'.strtolower($class).'.class.php';
        if ($included) {
            $this->request = new $class($this);
        } else {
        	die('<html><head><title></title></head><body><h1>FATAL ERROR: MODx Setup cannot continue.</h1><p>Make sure you have uploaded all the necessary files.</p></body></html>');
        }
        return $included;
    }

    /**
     * Load the language strings.
     */
    function loadLang() {
        $_lang= array ();
        @ include (MODX_SETUP_PATH . "lang/english.php");

        $language= 'english';
        if (isset ($_COOKIE['modx.setup.language'])) {
            $language= $_COOKIE['modx.setup.language'];
        }
        $language= isset ($_REQUEST['language']) ? $_REQUEST['language'] : $language;
        if ($language && $language != 'english') {
            @ include (MODX_SETUP_PATH . "lang/{$language}.php");
        }
        $this->lexicon = $_lang;
    }

    /**
     * Get the existing or create a new configuration.
     *
     * @param integer $mode The install mode.
     * @param array $config An array of config attributes.
     * @return array A copy of the config attributes array.
     */
    function getConfig($mode = 0, $config = array ()) {
        global $database_type, $database_server, $dbase, $database_user, $database_password, $database_connection_charset, $table_prefix;
        if (!is_array($config)) {
            $config = array ();
        }
        switch ($mode) {
            case 2 :
                $included = @ include (MODX_INSTALL_PATH . 'manager/includes/config.inc.php');
                if ($included && isset ($dbase))
                    break;

            case 1 :
                $included = @ include (MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php');
                if ($included && isset ($dbase))
                    break;

            default :
                $included = false;
                $database_server = isset ($_POST['databasehost']) ? $_POST['databasehost'] : 'localhost';
                $database_user = isset ($_POST['databaseloginname']) ? $_POST['databaseloginname'] : '';
                $database_password = isset ($_POST['databaseloginpassword']) ? $_POST['databaseloginpassword'] : '';
                $database_collation = isset ($_POST['database_collation']) ? $_POST['database_collation'] : 'utf8_unicode_ci';
                $database_charset = substr($database_collation, 0, strpos($database_collation, '_'));
                $database_connection_charset = isset ($_POST['database_connection_charset']) ? $_POST['database_connection_charset'] : $database_charset;
                $dbase = isset ($_POST['database_name']) ? $_POST['database_name'] : 'modx';
                $table_prefix = isset ($_POST['tableprefix']) ? $_POST['tableprefix'] : 'modx_';
                $https_port = isset ($_POST['httpsport']) ? $_POST['httpsport'] : '443';
                $cache_disabled = isset ($_POST['cache_disabled']) ? $_POST['cache_disabled'] : 'false';
                $site_sessionname = 'SN' . uniqid('');
                break;
        }
        $config['database_type'] = 'mysql';
        $config['database_server'] = $database_server;
        $config['dbase'] = trim($dbase, '`');
        $config['database_user'] = $database_user;
        $config['database_password'] = $database_password;
        $config['database_collation'] = isset ($database_collation) ? $database_collation : 'utf8_unicode_ci';
        $config['database_charset'] = $database_charset;
        $config['database_connection_charset'] = $database_connection_charset;
        $config['table_prefix'] = $table_prefix;
        $config['https_port'] = isset ($https_port) ? $https_port : '443';
        $config['site_sessionname'] = isset ($site_sessionname) ? $site_sessionname : 'SN' . uniqid('');
        $config['cache_disabled'] = isset ($cache_disabled) && $cache_disabled ? 'true' : 'false';
        $config['inplace'] = isset ($_POST['inplace']) ? 1 : 0;
        $config['unpacked'] = isset ($_POST['unpacked']) ? 1 : 0;
        $this->config = array_merge($this->config, $config);
        return $this->config;
    }

    /**
     * Set the install configuration settings.
     *
     * @param integer $mode The install mode.
     */
    function setConfig($mode = 0) {
        $database_type = 'mysql';
        $database_server = isset ($_POST['databasehost']) ? $_POST['databasehost'] : 'localhost';
        $database_user = isset ($_POST['databaseloginname']) ? $_POST['databaseloginname'] : '';
        $database_password = isset ($_POST['databaseloginpassword']) ? $_POST['databaseloginpassword'] : '';
        $database_collation = isset ($_POST['database_collation']) ? $_POST['database_collation'] : 'utf8_unicode_ci';
        $database_charset = substr($database_collation, 0, strpos($database_collation, '_'));
        $database_connection_charset = isset ($_POST['database_connection_charset']) ? $_POST['database_connection_charset'] : $database_charset;
        $dbase = isset ($_POST['database_name']) ? $_POST['database_name'] : 'modx';
        $table_prefix = isset ($_POST['tableprefix']) ? $_POST['tableprefix'] : 'modx_';
        $https_port = isset ($_POST['httpsport']) ? $_POST['httpsport'] : '443';
        $cache_disabled = isset ($_POST['cachedisabled']) ? $_POST['cachedisabled'] : 'false';
        $site_sessionname = isset ($_POST['site_sessionname']) ? $_POST['site_sessionname'] : 'SN' . uniqid('');
        $config['database_type'] = $database_type;
        $config['database_server'] = $database_server;
        $config['dbase'] = $dbase;
        $config['database_user'] = $database_user;
        $config['database_password'] = $database_password;
        $config['database_collation'] = $database_collation;
        $config['database_charset'] = $database_charset;
        $config['database_connection_charset'] = $database_connection_charset;
        $config['table_prefix'] = $table_prefix;
        $config['install_mode'] = $mode;
        $config['https_port'] = $https_port;
        $config['site_sessionname'] = $site_sessionname;
        $config['cache_disabled'] = $cache_disabled;
        $config['inplace'] = isset ($_POST['inplace']) ? 1 : 0;
        $config['unpacked'] = isset ($_POST['unpacked']) ? 1 : 0;
        $this->config = array_merge($this->config, $config);
    }

    /**
     * Get an xPDO connection to the database.
     *
     * @return xPDO A copy of the xpdo object.
     */
    function getConnection($mode = 0) {
        if ($mode === 1) {
            $errors = array ();
            $this->xpdo = $this->_modx($errors);
        }
        elseif (!is_object($this->xpdo)) {
            $this->xpdo = $this->_connect($this->config['database_type'] . ':host=' . $this->config['database_server'] . ';dbname=' . trim($this->config['dbase'], '`') . ';charset=' . $this->config['database_connection_charset'], $this->config['database_user'], $this->config['database_password'], $this->config['table_prefix']);
            $this->xpdo->config['cache_path'] = MODX_CORE_PATH . 'cache/';
        }
        if (is_object($this->xpdo)) {
//            $this->xpdo->setLogLevel(XPDO_LOG_LEVEL_INFO);
            $this->xpdo->setLogTarget('HTML');
        }
        return $this->xpdo;
    }

    /**
     * Get the initial admin user settings indicated by user.
     *
     * @return array A copy of the install config array merged with the retrieved admin user attributes.
     */
    function getAdminUser() {
        $config = array ();
        $config['cmsadmin'] = $_POST['cmsadmin'];
        $config['cmsadminemail'] = $_POST['cmsadminemail'];
        $config['cmspassword'] = $_POST['cmspassword'];
        $config['cmspasswordconfirm'] = $_POST['cmspasswordconfirm'];
        $this->config = array_merge($this->config, $config);
        return $this->config;
    }

    /**
     * Get the installation paths indicated by user.
     *
     * @return array A copy of the install config array merged with the retrieved context paths.
     */
    function getContextPaths() {
        $config = array ();
        $config['core_path'] = MODX_CORE_PATH;
        $config['web_path_auto'] = isset ($_POST['context_web_path_toggle']) && $_POST['context_web_path_toggle'] ? 1 : 0;
        $config['web_path'] = $_POST['context_web_path'];
        $config['web_url_auto'] = isset ($_POST['context_web_url_toggle']) && $_POST['context_web_url_toggle'] ? 1 : 0;
        $config['web_url'] = $_POST['context_web_url'];
        $config['mgr_path_auto'] = isset ($_POST['context_mgr_path_toggle']) && $_POST['context_mgr_path_toggle'] ? 1 : 0;
        $config['mgr_path'] = $_POST['context_mgr_path'];
        $config['mgr_url_auto'] = isset ($_POST['context_mgr_url_toggle']) && $_POST['context_mgr_url_toggle'] ? 1 : 0;
        $config['mgr_url'] = $_POST['context_mgr_url'];
        $config['connectors_path_auto'] = isset ($_POST['context_connectors_path_toggle']) && $_POST['context_connectors_path_toggle'] ? 1 : 0;
        $config['connectors_path'] = $_POST['context_connectors_path'];
        $config['connectors_url_auto'] = isset ($_POST['context_connectors_url_toggle']) && $_POST['context_connectors_url_toggle'] ? 1 : 0;
        $config['connectors_url'] = $_POST['context_connectors_url'];
        $config['processors_path'] = MODX_CORE_PATH . 'model/modx/processors/';
        $config['assets_path'] = $config['web_path'] . 'assets/';
        $config['assets_url'] = $config['web_url'] . 'assets/';
        $this->config = array_merge($this->config, $config);
        return $this->config;
    }

    /**
     * Perform a series of pre-installation tests.
     *
     * @todo Internationalization of error messages.
     * @param integer $mode The install mode.
     * @return array An array of result messages collected during the process.
     */
    function test($mode = 0) {
        $results = array ();

        // check PHP version
        $results['php_version']['msg'] = "<p>Checking PHP version: ";
        $php_ver_comp = version_compare(phpversion(), "4.3.0");
        $php_ver_comp2 = version_compare(phpversion(), "4.3.11");
        // -1 if left is less, 0 if equal, +1 if left is higher
        if ($php_ver_comp < 0) {
            $results['php_version']['msg'] .= "<span class=\"notok\">Failed!</span> - You are running on PHP " . phpversion() . ", and MODx Revolution requires PHP 4.3.0 or later</p>";
            $results['php_version']['class'] = 'testFailed';
        } else {
            $results['php_version']['msg'] .= "<span class=\"ok\">OK!</span></p>";
            if ($php_ver_comp2 < 0) {
                $results['php_version']['msg'] .= "<fieldset><legend>Security notice</legend><p>While MODx will work on your PHP version (" . phpversion() . "), usage of MODx on this version is not recommended. Your version of PHP is vulnerable to numerous security holes. Please upgrade to PHP version is 4.3.11 or higher, which patches these holes. It is recommended you upgrade to this version for the security of your own website.</p></fieldset>";
                $results['php_version']['class'] = 'testWarn';
            } else {
                $results['php_version']['class'] = 'testPassed';
            }
        }

        // check sessions
        $results['sessions']['msg'] = "<p>Checking if sessions are properly configured: ";
        if ($_SESSION['session_test'] != 1) {
            $results['sessions']['msg'] .= "<span class=\"notok\">Failed!</span></p>";
            $results['sessions']['class'] = 'testFailed';
        } else {
            $results['sessions']['msg'] .= "<span class=\"ok\">OK!</span></p>";
            $results['sessions']['class'] = 'testPassed';
        }

        // check directories
        // cache exists?
        $results['cache_exists']['msg'] = "<p>Checking if <span class=\"mono\">core/cache</span> directory exists: ";
        if (!file_exists(MODX_CORE_PATH . "cache")) {
            $results['cache_exists']['msg'] .= "<span class=\"notok\">Failed!</span></p>";
            $results['cache_exists']['class'] = 'testFailed';
        } else {
            $results['cache_exists']['msg'] .= "<span class=\"ok\">OK!</span></p>";
            $results['cache_exists']['class'] = 'testPassed';
        }

        // cache writable?
        $results['cache_writable']['msg'] = "<p>Checking if <span class=\"mono\">core/cache</span> directory is writable: ";
        if (!is_writable(MODX_CORE_PATH . "cache")) {
            $results['cache_writable']['msg'] .= "<span class=\"notok\">Failed!</span></p>";
            $results['cache_writable']['class'] = 'testFailed';
        } else {
            $results['cache_writable']['msg'] .= "<span class=\"ok\">OK!</span></p>";
            $results['cache_writable']['class'] = 'testPassed';
        }

        // images exists?
        //        $results['assets_images_exists']['msg']= "<p>Checking if <span class=\"mono\">assets/images</span> directory exists: ";
        //        if (!file_exists($this->config['web_path'] . "assets/images")) {
        //            $results['assets_images_exists']['msg'].= "<span class=\"notok\">Failed!</span></p>";
        //            $results['assets_images_exists']['class']= 'testFailed';
        //        } else {
        //            $results['assets_images_exists']['msg'].= "<span class=\"ok\">OK!</span></p>";
        //            $results['assets_images_exists']['class']= 'testPassed';
        //        }

        // images writable?
        //        $results['assets_images_writable']['msg']= "<p>Checking if <span class=\"mono\">assets/images</span> directory is writable: ";
        //        if (!is_writable($this->config['web_path'] . "assets/images")) {
        //            $results['assets_images_writable']['msg'].= "<span class=\"notok\">Failed!</span></p>";
        //            $results['assets_images_writable']['class']= 'testFailed';
        //        } else {
        //            $results['assets_images_writable']['msg'].= "<span class=\"ok\">OK!</span></p>";
        //            $results['assets_images_writable']['class']= 'testPassed';
        //        }

        // export exists?
        $results['assets_export_exists']['msg'] = "<p>Checking if <span class=\"mono\">core/export</span> directory exists: ";
        if (!file_exists(MODX_CORE_PATH . 'export')) {
            $results['assets_export_exists']['msg'] .= "<span class=\"notok\">Failed!</span></p>";
            $results['assets_export_exists']['class'] = 'testFailed';
        } else {
            $results['assets_export_exists']['msg'] .= "<span class=\"ok\">OK!</span></p>";
            $results['assets_export_exists']['class'] = 'testPassed';
        }

        // export writable?
        $results['assets_export_writable']['msg'] = "<p>Checking if <span class=\"mono\">core/export</span> directory is writable: ";
        if (!is_writable(MODX_CORE_PATH . 'export')) {
            $results['assets_export_writable']['msg'] .= "<span class=\"notok\">Failed!</span></p>";
            $results['assets_export_writable']['class'] = 'testFailed';
        } else {
            $results['assets_export_writable']['msg'] .= "<span class=\"ok\">OK!</span></p>";
            $results['assets_export_writable']['class'] = 'testPassed';
        }

        // packages exists?
        $results['core_packages_exists']['msg'] = "<p>Checking if <span class=\"mono\">core/packages</span> directory exists: ";
        if (!file_exists(MODX_CORE_PATH . 'packages')) {
            $results['core_packages_exists']['msg'] .= "<span class=\"notok\">Failed!</span></p>";
            $results['core_packages_exists']['class'] = 'testFailed';
        } else {
            $results['core_packages_exists']['msg'] .= "<span class=\"ok\">OK!</span></p>";
            $results['core_packages_exists']['class'] = 'testPassed';
        }

        // packages writable?
        if (!$this->config['unpacked']) {
            $results['core_packages_writable']['msg'] = "<p>Checking if <span class=\"mono\">core/packages</span> directory is writable: ";
            if (!is_writable(MODX_CORE_PATH . 'packages')) {
                $results['core_packages_writable']['msg'] .= "<span class=\"notok\">Failed!</span></p>";
                $results['core_packages_writable']['class'] = 'testFailed';
            } else {
                $results['core_packages_writable']['msg'] .= "<span class=\"ok\">OK!</span></p>";
                $results['core_packages_writable']['class'] = 'testPassed';
            }
        }

        // check context paths if inplace, else make sure paths can be written
        if ($this->config['inplace']) {
            // web_path
            $results['context_web_exists']['msg'] = "<p>Checking if <span class=\"mono\">{$this->config['web_path']}</span> directory exists: ";
            if (!file_exists($this->config['web_path'])) {
                $results['context_web_exists']['msg'] .= "<span class=\"notok\">Failed!</span></p>";
                $results['context_web_exists']['class'] = 'testFailed';
            } else {
                $results['context_web_exists']['msg'] .= "<span class=\"ok\">OK!</span></p>";
                $results['context_web_exists']['class'] = 'testPassed';
            }
        } else {
            $results['context_web_writable']['msg'] = "<p>Checking if <span class=\"mono\">{$this->config['web_path']}</span> directory is writable: ";
            if (!$this->_inWritableContainer($this->config['web_path'])) {
                $results['context_web_writable']['msg'] .= "<span class=\"notok\">Failed!</span></p>";
                $results['context_web_writable']['class'] = 'testFailed';
            } else {
                $results['context_web_writable']['msg'] .= "<span class=\"ok\">OK!</span></p>";
                $results['context_web_writable']['class'] = 'testPassed';
            }
        }

        // config file writable?
        $configFileDisplay= 'config/' . MODX_CONFIG_KEY . '.inc.php';
        $configFilePath= MODX_CORE_PATH . $configFileDisplay;
        $results['config_writable']['msg'] = '<p>Checking if <span class="mono">' . $configFileDisplay . '</span> exists and is writable: ';
        if (!file_exists($configFilePath)) {
            // make an attempt to create the file
            @ $hnd = fopen($configFilePath, 'w');
            @ fwrite($hnd, "<?php //MODx configuration file ?>");
            @ fclose($hnd);
        }
        $isWriteable = is_writable($configFilePath);
        if (!$isWriteable) {
            $results['config_writable']['msg'] .= "<span class=\"notok\">Failed!</span></p><p><strong>For new Linux/Unix installs, please create a blank file named <span class=\"mono\">" . MODX_CONFIG_KEY . ".inc.php</span> in your MODx core <span class=\"mono\">config/</span> directory with permissions set to 755.</strong></p>";
            $results['config_writable']['class'] = 'testFailed';
        } else {
            $results['config_writable']['msg'] .= "<span class=\"ok\">OK!</span></p>";
            $results['config_writable']['class'] = 'testPassed';
        }

        // connect to the database
        $results['dbase_connection']['msg'] = "<p>Creating connection to the database: ";
        $xpdo = $this->getConnection();
        if (!$xpdo || !$xpdo->connect()) {
            if ($mode > 0) {
                $results['dbase_connection']['msg'] .= "<span class=\"notok\">Database connection failed!</span><p />Check the connection details and try again.</p>";
                $results['dbase_connection']['class'] = 'testFailed';
            } else {
                $results['dbase_connection']['msg'] .= "<span class=\"notok\">Database connection failed!</span><p />Setup will attempt to create the database.</p>";
                $results['dbase_connection']['class'] = 'testWarn';
            }
        } else {
            $results['dbase_connection']['msg'] .= "<span class=\"ok\">OK!</span></p>";
            $results['dbase_connection']['class'] = 'testPassed';
        }

        //        // check the database collation if not specified in the configuration
        //        if (!isset ($database_connection_charset) || empty ($database_connection_charset)) {
        //            if (!$rs = @ mysql_query("show session variables like 'collation_database'")) {
        //                $rs = @ mysql_query("show session variables like 'collation_server'");
        //            }
        //            if ($rs && $collation = mysql_fetch_row($rs)) {
        //                $database_collation = $collation[1];
        //            }
        //            if (empty ($database_collation)) {
        //                $database_collation = 'utf8_unicode_ci';
        //            }
        //            $database_charset = substr($database_collation, 0, strpos($database_collation, '_') - 1);
        //            $database_connection_charset = $database_charset;
        //        }

        // check table prefix
        if ($xpdo && $xpdo->connect()) {
            $results['table_prefix']['msg'] = "<p>Checking table prefix `" . $this->config['table_prefix'] . "`: ";
            $count = 0;
            if ($stmt = $this->xpdo->query("SELECT COUNT(*) FROM `" . $this->config['table_prefix'] . "system_settings`")) {
                $count = $stmt->fetchColumn();
                $stmt->closeCursor();
            }
            if ($mode == 0) {
                if ($count > 0) {
                    $results['table_prefix']['msg'] .= "<span class=\"notok\">Failed!</span></b> - Table prefix is already in use in this database!</p>";
                    $results['table_prefix']['class'] = 'testFailed';
                    $results['table_prefix']['msg'] .= "<p>Setup couldn't install into the selected database, as it already contains tables with the prefix you specified. Please choose a new table_prefix, and run Setup again.</p>";
                } else {
                    $results['table_prefix']['msg'] .= "<span class=\"ok\">OK!</span></p>";
                    $results['table_prefix']['class'] = 'testPassed';
                }
            } else {
                if ($count < 1) {
                    $results['table_prefix']['msg'] .= "<span class=\"notok\">Failed!</span></b> - Table prefix does not exist in this database!</p>";
                    $results['table_prefix']['class'] = 'testFailed';
                    $results['table_prefix']['msg'] .= "<p>Setup couldn't install into the selected database, as it does not contain existing tables with the prefix you specified to be upgraded. Please choose an existing table_prefix, and run Setup again.</p>";
                } else {
                    $results['table_prefix']['msg'] .= "<span class=\"ok\">OK!</span></p>";
                    $results['table_prefix']['class'] = 'testPassed';
                }
            }
        }

        // andrazk 20070416 - add install flag and disable manager login
        // assets/cache writable?
        /*
        if (is_writable(MODX_CORE_PATH . "cache")) {
            if (file_exists('../core/cache/installProc.inc.php')) {
                @chmod('../core/cache/installProc.inc.php', 0755);
                unlink('../core/cache/installProc.inc.php');
            }

            // make an attempt to create the file
            @ $hnd = fopen(MODX_CORE_PATH . "cache/installProc.inc.php", 'w');
            @ fwrite($hnd, '<?php $installStartTime = '.time().'; ?>');
            @ fclose($hnd);
        }
        */
        return $results;
    }

    /**
     * Execute the installation process.
     *
     * @todo Internationalization of error messages.
     * @param integer $mode The install mode.
     * @return array An array of result messages collected during execution.
     */
    function execute($mode) {
        $results = array ();

        // set the time limit infinite in case it takes a bit
        // TODO: fix this by allowing resume when it takes a long time
        @ set_time_limit(0);

        // get connection
        $this->getConnection($mode);

        // run appropriate database routines
        switch ($mode) {
            //TODO: MODx Evolution to Revolution migration
            case 2 :
                $results = include (MODX_SETUP_PATH . 'includes/tables_migrate.php');
                break;
                // 0.9.7-alpha+ upgrades
            case 1 :
                $results = include (MODX_SETUP_PATH . 'includes/tables_upgrade.php');
                break;
                // create tables
            default :
                $results = include (MODX_SETUP_PATH . 'includes/tables_create.php');
                break;
        }

        // write config file
        $this->writeConfig($results);

        if ($this->xpdo) {
            // add required core data
            $this->xpdo->loadClass('transport.xPDOTransport', XPDO_CORE_PATH, true, true);

            $this->xpdo->setPackage('modx', MODX_CORE_PATH . 'model/');

            $packageDirectory = MODX_CORE_PATH . 'packages/';
            $packageState = $this->config['unpacked'] == 1 ? XPDO_TRANSPORT_STATE_UNPACKED : XPDO_TRANSPORT_STATE_PACKED;
            $package = xPDOTransport :: retrieve($this->xpdo, $packageDirectory . 'core.transport.zip', $packageDirectory, $packageState);

            if (!defined('MODX_BASE_PATH'))
                define('MODX_BASE_PATH', $this->config['web_path']);
            if (!defined('MODX_ASSETS_PATH'))
                define('MODX_ASSETS_PATH', $this->config['assets_path']);
            if (!defined('MODX_MANAGER_PATH'))
                define('MODX_MANAGER_PATH', $this->config['mgr_path']);
            if (!defined('MODX_CONNECTORS_PATH'))
                define('MODX_CONNECTORS_PATH', $this->config['connectors_path']);

            $package->install(array (
                XPDO_TRANSPORT_RESOLVE_FILES => ($this->config['inplace'] == 0 ? 1 : 0)
            ));

            // set default workspace path
            if ($workspace = $this->xpdo->getObject('modWorkspace', array (
                    'active' => 1
                ))) {
                if ($path = $workspace->get('path')) {
                    $path = trim($path);
                }
                if (empty ($path) || !file_exists($path)) {
                    $workspace->set('path', MODX_CORE_PATH);
                    if (!$workspace->save()) {
                        $results[] = array (
                            'class' => 'error',
                            'msg' => '<p class="notok">Error setting the active workspace path.</p>'
                        );
                    } else {
                        $results[] = array (
                            'class' => 'success',
                            'msg' => '<p class="ok">Updated the active workspace path.</p>'
                        );
                    }
                }
            } else {
                $results[] = array (
                    'class' => 'error',
                    'msg' => '<p class="notok">Could not find the active workspace.</p>'
                );
            }

            if ($mode == 0) {
                // add default admin user
                $user = $this->xpdo->newObject('modUser');
                $user->set('username', $this->config['cmsadmin']);
                $user->set('password', md5($this->config['cmspassword']));
                if ($saved = $user->save()) {
                    $userProfile = $this->xpdo->newObject('modUserProfile');
                    $userProfile->set('internalKey', $user->get('id'));
                    $userProfile->set('fullname', 'Default Admin User');
                    $userProfile->set('email', $this->config['cmsadminemail']);
                    $userProfile->set('role', 1);
                    $saved = $userProfile->save();
                }
                if (!$saved) {
                    $results[] = array (
                        'class' => 'error',
                        'msg' => '<p class="notok">Error saving the default admin user.<br />' . print_r($this->xpdo->errorInfo(), true) . '</p>'
                    );
                } else {
                    $results[] = array (
                        'class' => 'success',
                        'msg' => '<p class="ok">Created default admin user.</p>'
                    );
                }
            } else {
                // handle change of manager_theme to default (FIXME: temp hack)
                if ($managerTheme = $this->xpdo->getObject('modSystemSetting', array(
                        'key' => 'manager_theme',
                        'value:!=' => 'default'
                    ))) {
                    $managerTheme->set('value', 'default');
                    $managerTheme->save();
                }
                
                // handle change of default language to proper IANA code (FIXME: just forcing en for now)
                if ($managerLanguage = $this->xpdo->getObject('modSystemSetting', array(
                        'key' => 'manager_language',
                        'value:!=' => 'en'
                    ))) {
                    $managerLanguage->set('value', 'en');
                    $managerLanguage->save();
                }
                
                // update settings_version
                if ($settings_version = $this->xpdo->getObject('modSystemSetting', array(
                        'key' => 'settings_version'
                    ))) {
                    $currentVersion = include(MODX_CORE_PATH . 'config/version.inc.php');
                    $settings_version->set('value', $currentVersion['full_version']);
                    $settings_version->save();
                }
            }
        }

        return $results;
    }

    /**
     * Verify that the modX class can be initialized.
     *
     * @param integer $mode Indicates the installation mode.
     * @return array An array of error messages collected during the process.
     */
    function verify($mode) {
        $errors = array ();
        if ($modx = $this->_modx($errors)) {
            if ($modx->getCacheManager()) {
                $modx->cacheManager->clearCache(array(), array(
                    'objects' => '*',
                    'publishing' => 1
                ));
            }
        }
        return $errors;
    }

    function cleanup($options = array ()) {
        //TODO: implement this function to cleanup any temporary files
    }

    /**
     * Writes the config file.
     *
     * @todo Internationalization of error messages.
     * @param array $results An array of result messages.
     * @return boolean Returns true if successful; false otherwise.
     */
    function writeConfig(& $results) {
        $written = false;
        $configTpl = MODX_CORE_PATH . 'config/config.inc.tpl';
        $configFile = MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
        $this->config['last_install_time'] = time();
        if (file_exists($configTpl)) {
            if ($tplHandle = @ fopen($configTpl, 'rb')) {
                $content = @ fread($tplHandle, filesize($configTpl));
                @ fclose($tplHandle);
                if ($content) {
                    $replace = array ();
                    while (list ($key, $value) = each($this->config)) {
                        $replace['{' . $key . '}'] = "{$value}";
                    }
                    $content = str_replace(array_keys($replace), array_values($replace), $content);
                    if ($configHandle = @ fopen($configFile, 'wb')) {
                        $written = @ fwrite($configHandle, $content);
                        @ fclose($configHandle);
                    }
                }
            }
        }
        // try to chmod the config file go-rwx (for suexeced php)
        // FIXME: need some way to configure the actual permissions to set
        $chmodSuccess = @ chmod($configFile, 0600);
        if (!is_array($results)) {
            $results = array ();
        }
        if ($written) {
            $results[] = array (
                'class' => 'success',
                'msg' => '<p class="ok">Config file successfully written.</p>'
            );
        } else {
            $results[] = array (
                'class' => 'failed',
                'msg' => '<p class="notok">Error writing config file.</p>'
            );
        }
        if ($chmodSuccess) {
            $results[] = array (
                'class' => 'success',
                'msg' => '<p class="ok">Config file permissions successfully updated.</p>'
            );
        } else {
            $results[] = array (
                'class' => 'warning',
                'msg' => '<p>Config file permissions were not updated. You may want to change the permissions on your config file to secure the file from tampering.</p>'
            );
        }
        return $written;
    }

    /**
     * Installs a transport package.
     *
     * @todo Internationalization of error messages.
     * @param string The package signature.
     * @param array $attributes An array of installation attributes.
     * @return array An array of error messages collected during the process.
     */
    function installPackage($pkg, $attributes = array ()) {
        $errors = array ();

        // instantiate the modX class
        if (@ require_once (MODX_CORE_PATH . 'model/modx/modx.class.php')) {
            $modx = new modX(MODX_CORE_PATH . 'config/');
            if (!is_object($modx) || !is_a($modx, 'modX')) {
                $errors[] = '<p>Could not instantiate the MODx class.</p>';
            } else {
                $modx->setPackage('modx', MODX_CORE_PATH . 'model/');

                // try to initialize the mgr context
                $modx->initialize('mgr');
                if (!$modx->_initialized) {
                    $errors[] = '<p>Could not initialize the MODx manager context.</p>';
                } else {
                    $loaded = $modx->loadClass('transport.xPDOTransport', XPDO_CORE_PATH, true, true);
                    if (!$loaded)
                        $errors[] = '<p>Error loading transport class.</p>';

                    $packageDirectory = MODX_CORE_PATH . 'packages/';
                    $packageState = (isset ($attributes[XPDO_TRANSPORT_PACKAGE_STATE]) ? $attributes[XPDO_TRANSPORT_PACKAGE_STATE] : XPDO_TRANSPORT_STATE_PACKED);
                    $package = xPDOTransport :: retrieve($modx, $packageDirectory . $pkg . '.transport.zip', $packageDirectory, $packageState);
                    if ($package) {
                        if (!$package->install($attributes)) {
                            $errors[] = "<p>Could not install package {$pkg}.</p>";
                        } else {
                            $modx->_log(XPDO_LOG_LEVEL_INFO, "Successfully installed package {$pkg}");
                        }
                    } else {
                        $errors[] = "<p>Could not retrieve package {$pkg} for installation.</p>";
                    }
                }
            }
        } else {
            $errors[] = '<p>Could not include the MODx class file.</p>';
        }

        return $errors;
    }

    /**
     * Gets the manager login URL.
     *
     * @return string The URL of the installed manager context.
     */
    function getManagerLoginUrl() {
        $url = '';

        // instantiate the modX class
        if (@ require_once (MODX_CORE_PATH . 'model/modx/modx.class.php')) {
            $modx = new modX(MODX_CORE_PATH . 'config/');
            if (is_object($modx) && is_a($modx, 'modX')) {
                // try to initialize the mgr context
                $modx->initialize('mgr');
                $url = MODX_MANAGER_URL;
            }
        }

        return $url;
    }

    /**
     * Determines the possible install modes.
     *
     * @access public
     * @return integer One of three possible mode indicators:<ul>
     * <li>0 = new install only</li>
     * <li>1 = new OR upgrade from MODx Evolution</li>
     * <li>2 = new OR upgrade from older versions of MODx Revolution</li>
     * </ul>
     */
    function getInstallMode() {
        $mode = 0;
        if (isset ($_POST['installmode'])) {
            $mode = intval($_POST['installmode']);
        } else {
            if (file_exists(MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php')) {
                // Include the file so we can test its validity
                $included = @ include (MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php');
                $mode = ($included && isset ($dbase)) ? 1 : 0;
            }
            if (!$mode && file_exists(MODX_INSTALL_PATH . "manager/includes/config.inc.php")) {
                $included = @ include (MODX_INSTALL_PATH . "manager/includes/config.inc.php");
                $mode = ($included && isset ($dbase)) ? 2 : 0;
            }
        }
        return $mode;
    }

    /**
     * Creates the database connection for the installation process.
     *
     * @access private
     * @return xPDO The xPDO instance to be used by the installation.
     */
    function _connect($dsn, $user = '', $password = '', $prefix = '') {
        @ require_once (MODX_CORE_PATH . "xpdo/xpdo.class.php");
        $xpdo = new xPDO($dsn, $user, $password, array(
                XPDO_OPT_CACHE_PATH => MODX_CORE_PATH . 'cache/',
                XPDO_OPT_TABLE_PREFIX => $prefix,
                XPDO_OPT_LOADER_CLASSES => array('modAccessibleObject')
            ),
            array (
                PDO_ATTR_ERRMODE => PDO_ERRMODE_SILENT,
                PDO_ATTR_PERSISTENT => false,
                PDO_MYSQL_ATTR_USE_BUFFERED_QUERY => true
            )
        );
        $xpdo->cachePath = MODX_CORE_PATH . 'cache/';
        return $xpdo;
    }

    /**
     * Instantiate an existing modX configuration.
     *
     * @todo Internationalization of error messages.
     * @param array &$errors An array in which error messages are collected.
     * @return modX|null The modX instance, or null if it could not be instantiated.
     */
    function _modx(& $errors) {
        $modx = null;

        // to validate installation, instantiate the modX class and run a few tests
        if (@ require_once (MODX_CORE_PATH . 'model/modx/modx.class.php')) {
            $modx = new modX(MODX_CORE_PATH . 'config/');
            if (!is_object($modx) || !is_a($modx, 'modX')) {
                $errors[] = '<p>Could not instantiate the MODx class.</p>';
            } else {
                // try to initialize the mgr context
                $modx->initialize('mgr');
                if (!$modx->_initialized) {
                    $errors[] = '<p>Could not initialize the MODx manager context.</p>';
                }
            }
        } else {
            $errors[] = '<p>Could not include the MODx class file.</p>';
        }

        return $modx;
    }

    /**
     * Checks to see if a given path is in a writable container.
     *
     * @param string $path The file path to test.
     * @return boolean Returns true if the path is in a writable container.
     */
    function _inWritableContainer($path) {
        $writable = false;
        if (file_exists($path) && is_dir($path))
            $writable = is_writable($path);
        while (!file_exists($path)) {
            $path = dirname($path);
            if (!$path)
                break;
            if (!file_exists($path))
                break;
            $writable = is_writable($path);
        }
        return $writable;
    }
}