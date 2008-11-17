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
define('MODX_INSTALL_MODE_NEW',0);
define('MODX_INSTALL_MODE_UPGRADE_REVO',1);
define('MODX_INSTALL_MODE_UPGRADE_EVO',2);
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
    var $lexicon = array ();

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
        include MODX_SETUP_PATH . 'lang/en.php';

        $language= 'en';
        if (isset ($_COOKIE['modx_setup_language'])) {
            $language= $_COOKIE['modx_setup_language'];
        }
        $language= isset ($_REQUEST['language']) ? $_REQUEST['language'] : $language;
        if ($language && $language != 'en') {
            include MODX_SETUP_PATH . 'lang/'.$language.'.php';
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
            case MODX_INSTALL_MODE_UPGRADE_EVO :
                $included = @ include MODX_INSTALL_PATH . 'manager/includes/config.inc.php';
                if ($included && isset ($dbase))
                    break;

            case MODX_INSTALL_MODE_UPGRADE_REVO :
                $included = @ include MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
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
        $config = array_merge($config,array(
            'database_type' => 'mysql',
            'database_server' => $database_server,
            'dbase' => trim($dbase,'`'),
            'database_user' => $database_user,
            'database_password' => $database_password,
            'database_collation' => isset ($database_collation) ? $database_collation : 'utf8_unicode_ci',
            'database_charset' => $database_charset,
            'database_connection_charset' => $database_connection_charset,
            'table_prefix' => $table_prefix,
            'https_port' => isset ($https_port) ? $https_port : '443',
            'site_sessionname' => isset ($site_sessionname) ? $site_sessionname : 'SN' . uniqid(''),
            'cache_disabled' => isset ($cache_disabled) && $cache_disabled ? 'true' : 'false',
            'inplace' => isset ($_POST['inplace']) ? 1 : 0,
            'unpacked' => isset ($_POST['unpacked']) ? 1 : 0,
        ));
        $this->config = array_merge($this->config, $config);
        return $this->config;
    }

    /**
     * Set the install configuration settings.
     *
     * @param integer $mode The install mode.
     */
    function setConfig($mode = MODX_INSTALL_MODE_NEW) {
        $config = array(
            'database_type' => 'mysql',
            'database_server' => isset ($_POST['databasehost']) ? $_POST['databasehost'] : 'localhost',
            'database_user' => isset ($_POST['databaseloginname']) ? $_POST['databaseloginname'] : '',
            'database_password' => isset ($_POST['databaseloginpassword']) ? $_POST['databaseloginpassword'] : '',
            'database_collation' => isset ($_POST['database_collation']) ? $_POST['database_collation'] : 'utf8_unicode_ci',
            'dbase' => isset ($_POST['database_name']) ? $_POST['database_name'] : 'modx',
            'table_prefix' => isset ($_POST['tableprefix']) ? $_POST['tableprefix'] : 'modx_',
            'https_port' => isset ($_POST['httpsport']) ? $_POST['httpsport'] : '443',
            'cache_disabled' => isset ($_POST['cachedisabled']) ? $_POST['cachedisabled'] : 'false',
            'site_sessionname' => isset ($_POST['site_sessionname']) ? $_POST['site_sessionname'] : 'SN' . uniqid(''),
            'inplace' => isset ($_POST['inplace']) ? 1 : 0,
            'unpacked' => isset ($_POST['unpacked']) ? 1 : 0,
        );
        $config['database_charset'] = substr($config['database_collation'], 0, strpos($config['database_collation'], '_'));
        $config['database_connection_charset'] = isset($_POST['database_connection_charset']) ? $_POST['database_connection_charset'] : $config['database_charset'];

        $this->config = array_merge($this->config, $config);
    }

    /**
     * Get an xPDO connection to the database.
     *
     * @return xPDO A copy of the xpdo object.
     */
    function getConnection($mode = MODX_INSTALL_MODE_NEW) {
        if ($mode === MODX_INSTALL_MODE_UPGRADE_REVO) {
            $errors = array ();
            $this->xpdo = $this->_modx($errors);
        } else if (!is_object($this->xpdo)) {
            $this->xpdo = $this->_connect($this->config['database_type'] . ':host=' . $this->config['database_server'] . ';dbname=' . trim($this->config['dbase'], '`') . ';charset=' . $this->config['database_connection_charset'], $this->config['database_user'], $this->config['database_password'], $this->config['table_prefix']);
            $this->xpdo->config['cache_path'] = MODX_CORE_PATH . 'cache/';
        }
        if (is_object($this->xpdo)) {
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
        $config = array (
            'cmsadmin' => $_POST['cmsadmin'],
            'cmsadminemail' => $_POST['cmsadminemail'],
            'cmspassword' => $_POST['cmspassword'],
            'cmspasswordconfirm' => $_POST['cmspasswordconfirm'],
        );
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
        $webUrl= substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], 'setup/'));
        $config['core_path'] = MODX_CORE_PATH;
        $config['web_path_auto'] = isset ($_POST['context_web_path_toggle']) && $_POST['context_web_path_toggle'] ? 1 : 0;
        $config['web_path'] = isset($_POST['context_web_path']) ? $_POST['context_web_path'] : MODX_INSTALL_PATH;
        $config['web_url_auto'] = isset ($_POST['context_web_url_toggle']) && $_POST['context_web_url_toggle'] ? 1 : 0;
        $config['web_url'] = isset($_POST['context_web_url']) ? $_POST['context_web_url'] : $webUrl;
        $config['mgr_path_auto'] = isset ($_POST['context_mgr_path_toggle']) && $_POST['context_mgr_path_toggle'] ? 1 : 0;
        $config['mgr_path'] = isset($_POST['context_mgr_path']) ? $_POST['context_mgr_path'] : MODX_INSTALL_PATH . 'manager/';
        $config['mgr_url_auto'] = isset ($_POST['context_mgr_url_toggle']) && $_POST['context_mgr_url_toggle'] ? 1 : 0;
        $config['mgr_url'] = isset($_POST['context_mgr_url']) ? $_POST['context_mgr_url'] : $webUrl . 'manager/';
        $config['connectors_path_auto'] = isset ($_POST['context_connectors_path_toggle']) && $_POST['context_connectors_path_toggle'] ? 1 : 0;
        $config['connectors_path'] = isset($_POST['context_connectors_path']) ? $_POST['context_connectors_path'] : MODX_INSTALL_PATH . 'connectors/';
        $config['connectors_url_auto'] = isset ($_POST['context_connectors_url_toggle']) && $_POST['context_connectors_url_toggle'] ? 1 : 0;
        $config['connectors_url'] = isset($_POST['context_connectors_url']) ? $_POST['context_connectors_url'] : $webUrl . 'connectors/';
        $config['processors_path'] = MODX_CORE_PATH . 'model/modx/processors/';
        $config['assets_path'] = $config['web_path'] . 'assets/';
        $config['assets_url'] = $config['web_url'] . 'assets/';
        $this->config = array_merge($this->config, $config);
        return $this->config;
    }

    function loadTestHandler($class = 'modInstallTest') {
        $included = @include dirname(__FILE__).'/'.strtolower($class).'.class.php';
        if ($included) {
            $this->test = new $class($this);
            return $this->test;
        } else {
            die('<html><head><title></title></head><body><h1>FATAL ERROR: MODx Setup cannot continue.</h1><p>Make sure you have uploaded all the necessary files.</p></body></html>');
        }
    }

    /**
     * Perform a series of pre-installation tests.
     *
     * @param integer $mode The install mode.
     * @param string $test_class The class to run tests with
     * @return array An array of result messages collected during the process.
     */
    function test($mode = MODX_INSTALL_MODE_NEW,$test_class = 'modInstallTest') {
        $test = $this->loadTestHandler($test_class);
        $results = $this->test->run($mode);
        return $results;
    }

    /**
     * Execute the installation process.
     *
     * @param integer $mode The install mode.
     * @return array An array of result messages collected during execution.
     */
    function execute($mode) {
        $results = array ();
        /* set the time limit infinite in case it takes a bit
         * TODO: fix this by allowing resume when it takes a long time
         */
        @ set_time_limit(0);
        @ ini_set('max_execution_time', 240);

        /* get connection */
        $this->getConnection($mode);

        /* run appropriate database routines */
        switch ($mode) {
            /* TODO: MODx Evolution to Revolution migration */
            case MODX_INSTALL_MODE_UPGRADE_EVO :
                $results = include MODX_SETUP_PATH . 'includes/tables_migrate.php';
                break;
                /* revo-alpha+ upgrades */
            case MODX_INSTALL_MODE_UPGRADE_REVO :
                $results = include MODX_SETUP_PATH . 'includes/tables_upgrade.php';
                break;
                /* new install, create tables */
            default :
                $results = include MODX_SETUP_PATH . 'includes/tables_create.php';
                break;
        }

        /* write config file */
        $this->writeConfig($results);

        if ($this->xpdo) {
            /* add required core data */
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

            /* set default workspace path */
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
                            'msg' => '<p class="notok">'.$this->lexicon['workspace_err_path'].'</p>'
                        );
                    } else {
                        $results[] = array (
                            'class' => 'success',
                            'msg' => '<p class="ok">'.$this->lexicon['workspace_path_updated'].'</p>'
                        );
                    }
                }
            } else {
                $results[] = array (
                    'class' => 'error',
                    'msg' => '<p class="notok">'.$this->lexicon['workspace_err_nf'].'</p>'
                );
            }

            /* if new install */
            if ($mode == MODX_INSTALL_MODE_NEW) {
                /* add default admin user */
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
                    if ($saved) {
                        $userGroupMembership = $this->xpdo->newObject('modUserGroupMember');
                        $userGroupMembership->set('user_group', 1);
                        $userGroupMembership->set('member', $user->get('id'));
                        $userGroupMembership->set('role', 2);
                        $saved = $userGroupMembership->save();
                    }
                }
                if (!$saved) {
                    $results[] = array (
                        'class' => 'error',
                        'msg' => '<p class="notok">'.$this->lexicon['dau_err_save'].'<br />' . print_r($this->xpdo->errorInfo(), true) . '</p>'
                    );
                } else {
                    $results[] = array (
                        'class' => 'success',
                        'msg' => '<p class="ok">'.$this->lexicon['dau_saved'].'</p>'
                    );
                }
            /* if upgrade */
            } else {
                /* handle change of manager_theme to default (FIXME: temp hack) */
                if ($managerTheme = $this->xpdo->getObject('modSystemSetting', array(
                        'key' => 'manager_theme',
                        'value:!=' => 'default'
                    ))) {
                    $managerTheme->set('value', 'default');
                    $managerTheme->save();
                }

                /* handle change of default language to proper IANA code (FIXME: just forcing en for now) */
                if ($managerLanguage = $this->xpdo->getObject('modSystemSetting', array(
                        'key' => 'manager_language',
                        'value:!=' => 'en'
                    ))) {
                    $managerLanguage->set('value', 'en');
                    $managerLanguage->save();
                }

                /* update settings_version */
                if ($settings_version = $this->xpdo->getObject('modSystemSetting', array(
                        'key' => 'settings_version'
                    ))) {
                    $currentVersion = include MODX_CORE_PATH . 'config/version.inc.php';
                    $settings_version->set('value', $currentVersion['full_version']);
                    $settings_version->save();
                }

                /* make sure admin user (1) has proper group and role */
                $adminUser = $this->xpdo->getObject('modUser', 1);
                if ($adminUser) {
                    $userGroupMembership = $this->xpdo->getObject('modUserGroupMember', array('user_group' => true, 'member' => true));
                    if (!$userGroupMembership) {
                        $userGroupMembership = $this->xpdo->newObject('modUserGroupMember');
                        $userGroupMembership->set('user_group', 1);
                        $userGroupMembership->set('member', 1);
                        $userGroupMembership->set('role', 2);
                        $userGroupMembership->save();
                    } else {
                        $userGroupMembership->set('role', 2);
                        $userGroupMembership->save();
                    }
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

    /**
     *
     * @param array $options
     */
    function cleanup($options = array ()) {
        /*
         * TODO: implement this function to cleanup any temporary files
         */
    }

    /**
     * Writes the config file.
     *
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
        /* try to chmod the config file go-rwx (for suexeced php)
         * FIXME: need some way to configure the actual permissions to set
         */
        $chmodSuccess = @ chmod($configFile, 0600);
        if (!is_array($results)) {
            $results = array ();
        }
        if ($written) {
            $results[] = array (
                'class' => 'success',
                'msg' => '<p class="ok">'.$this->lexicon['config_file_written'].'</p>'
            );
        } else {
            $results[] = array (
                'class' => 'failed',
                'msg' => '<p class="notok">'.$this->lexicon['config_file_err_w'].'</p>'
            );
        }
        if ($chmodSuccess) {
            $results[] = array (
                'class' => 'success',
                'msg' => '<p class="ok">'.$this->lexicon['config_file_perms_set'].'</p>'
            );
        } else {
            $results[] = array (
                'class' => 'warning',
                'msg' => '<p>'.$this->lexicon['config_file_perms_notset'].'</p>'
            );
        }
        return $written;
    }

    /**
     * Installs a transport package.
     *
     * @param string The package signature.
     * @param array $attributes An array of installation attributes.
     * @return array An array of error messages collected during the process.
     */
    function installPackage($pkg, $attributes = array ()) {
        $errors = array ();

        /* instantiate the modX class */
        if (@ require_once (MODX_CORE_PATH . 'model/modx/modx.class.php')) {
            $modx = new modX(MODX_CORE_PATH . 'config/');
            if (!is_object($modx) || !is_a($modx, 'modX')) {
                $errors[] = '<p>'.$this->lexicon['modx_err_instantiate'].'</p>';
            } else {
                $modx->setPackage('modx', MODX_CORE_PATH . 'model/');

                /* try to initialize the mgr context */
                $modx->initialize('mgr');
                if (!$modx->_initialized) {
                    $errors[] = '<p>'.$this->lexicon['modx_err_instantiate_mgr'].'</p>';
                } else {
                    $loaded = $modx->loadClass('transport.xPDOTransport', XPDO_CORE_PATH, true, true);
                    if (!$loaded)
                        $errors[] = '<p>'.$this->lexicon['transport_class_err_load'].'</p>';

                    $packageDirectory = MODX_CORE_PATH . 'packages/';
                    $packageState = (isset ($attributes[XPDO_TRANSPORT_PACKAGE_STATE]) ? $attributes[XPDO_TRANSPORT_PACKAGE_STATE] : XPDO_TRANSPORT_STATE_PACKED);
                    $package = xPDOTransport :: retrieve($modx, $packageDirectory . $pkg . '.transport.zip', $packageDirectory, $packageState);
                    if ($package) {
                        if (!$package->install($attributes)) {
                            $errors[] = '<p>'.sprintf($this->lexicon['package_err_install'],$pkg).'</p>';
                        } else {
                            $modx->log(XPDO_LOG_LEVEL_INFO,sprintf($this->lexicon['package_installed'],$pkg));
                        }
                    } else {
                        $errors[] = '<p>'.sprintf($this->lexicon['package_err_nf'],$pkg).'</p>';
                    }
                }
            }
        } else {
            $errors[] = '<p>'.$this->lexicon['modx_class_err_nf'].'</p>';
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

        /* instantiate the modX class */
        if (@ require_once (MODX_CORE_PATH . 'model/modx/modx.class.php')) {
            $modx = new modX(MODX_CORE_PATH . 'config/');
            if (is_object($modx) && is_a($modx, 'modX')) {
                /* try to initialize the mgr context */
                $modx->initialize('mgr');
                $url = $modx->config['manager_url'];
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
     * <li>1 = new OR upgrade from older versions of MODx Revolution</li>
     * <li>2 = new OR upgrade from MODx Evolution</li>
     * </ul>
     */
    function getInstallMode() {
        $mode = MODX_INSTALL_MODE_NEW;
        if (isset ($_POST['installmode'])) {
            $mode = intval($_POST['installmode']);
        } else {
            if (file_exists(MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php')) {
                /* Include the file so we can test its validity */
                $included = @ include (MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php');
                $mode = ($included && isset ($dbase)) ? MODX_INSTALL_MODE_UPGRADE_REVO : MODX_INSTALL_MODE_NEW;
            }
            if (!$mode && file_exists(MODX_INSTALL_PATH . 'manager/includes/config.inc.php')) {
                $included = @ include (MODX_INSTALL_PATH . 'manager/includes/config.inc.php');
                $mode = ($included && isset ($dbase)) ? MODX_INSTALL_MODE_UPGRADE_EVO : MODX_INSTALL_MODE_NEW;
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
        require_once MODX_CORE_PATH . 'xpdo/xpdo.class.php';
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

        /* to validate installation, instantiate the modX class and run a few tests */
        if (require_once (MODX_CORE_PATH . 'model/modx/modx.class.php')) {
            $modx = new modX(MODX_CORE_PATH . 'config/');
            if (!is_object($modx) || !is_a($modx, 'modX')) {
                $errors[] = '<p>'.$this->lexicon['modx_err_instantiate'].'</p>';
            } else {
                $modx->setDebug(E_ALL & ~E_STRICT);
                $modx->setLogTarget('HTML');

                /* try to initialize the mgr context */
                $modx->initialize('mgr');
                if (!$modx->_initialized) {
                    $errors[] = '<p>'.$this->lexicon['modx_err_instantiate_mgr'].'</p>';
                }
            }
        } else {
            $errors[] = '<p>'.$this->lexicon['modx_class_err_nf'].'</p>';
        }

        return $modx;
    }
}