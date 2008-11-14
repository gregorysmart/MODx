<?php
class modInstallTest {
    var $results = array();
    var $mode;

    function modInstallTest(&$install) {
        $this->__construct($install);
    }
    function __construct(&$install) {
        $this->install =& $install;
    }

    /**
     * Run tests.
     *
     * @param integer $mode The install mode.
     * @return array An array of result messages collected during the process.
     */
    function run($mode = MODX_INSTALL_MODE_NEW) {
        $this->results = array();
        $this->mode = $mode;

        $this->checkPHPVersion();
        $this->checkSessions();
        $this->checkCache();
        $this->checkExport();
        $this->checkPackages();
        $this->checkContexts();
        $this->checkConfig();
        $this->checkDatabase();

        return $this->results;
    }

    /**
     * Checks PHP version
     */
    function checkPHPVersion() {
        $this->results['php_version']['msg'] = '<p>'.$this->install->lexicon['test_php_version_start'].' ';
        $php_ver_comp = version_compare(phpversion(),'4.3.0');
        $php_ver_comp2 = version_compare(phpversion(), '4.3.11');
        /* -1 if left is less, 0 if equal, +1 if left is higher */
        if ($php_ver_comp < 0) {
            $this->results['php_version']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span> - '.sprintf($this->install->lexicon['test_php_version_fail'],phpversion()).'</p>';
            $this->results['php_version']['class'] = 'testFailed';
        } else {
            $this->results['php_version']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
            if ($php_ver_comp2 < 0) {
                $this->results['php_version']['msg'] .= '<div class="notes"><h3>'.$this->install->lexicon['security_notice'].'</h3><p>'.sprintf($this->install->lexicon['test_php_version_sn'],phpversion()).'</p></div>';
                $this->results['php_version']['class'] = 'testWarn';
            } else {
                $this->results['php_version']['class'] = 'testPassed';
            }
        }
    }

    /**
     * Check sessions
     */
    function checkSessions() {
        $this->results['sessions']['msg'] = '<p>'.$this->install->lexicon['test_sessions_start'].' ';
        if ($_SESSION['session_test'] != 1) {
            $this->results['sessions']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span></p>';
            $this->results['sessions']['class'] = 'testFailed';
        } else {
            $this->results['sessions']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
            $this->results['sessions']['class'] = 'testPassed';
        }

    }

    /**
     * Check if cache exists and is writable
     */
    function checkCache() {
        /* cache exists? */
        $this->results['cache_exists']['msg'] = '<p>'.sprintf($this->install->lexicon['test_directory_exists'],'core/cache');
        if (!file_exists(MODX_CORE_PATH . 'cache')) {
            $this->results['cache_exists']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span></p>';
            $this->results['cache_exists']['class'] = 'testFailed';
        } else {
            $this->results['cache_exists']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
            $this->results['cache_exists']['class'] = 'testPassed';
        }

        /* cache writable? */
        $this->results['cache_writable']['msg'] = '<p>'.sprintf($this->install->lexicon['test_directory_writable'],'core/cache');
        if (!is_writable(MODX_CORE_PATH . 'cache')) {
            $this->results['cache_writable']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span></p>';
            $this->results['cache_writable']['class'] = 'testFailed';
        } else {
            $this->results['cache_writable']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
            $this->results['cache_writable']['class'] = 'testPassed';
        }
    }

    /**
     * Check if core/export exists and is writable
     */
    function checkExport() {
        /* export exists? */
        $this->results['assets_export_exists']['msg'] = '<p>'.sprintf($this->install->lexicon['test_directory_exists'],'core/export');
        if (!file_exists(MODX_CORE_PATH . 'export')) {
            $this->results['assets_export_exists']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span></p>';
            $this->results['assets_export_exists']['class'] = 'testFailed';
        } else {
            $this->results['assets_export_exists']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
            $this->results['assets_export_exists']['class'] = 'testPassed';
        }

        /* export writable? */
        $this->results['assets_export_writable']['msg'] = '<p>'.sprintf($this->install->lexicon['test_directory_writable'],'core/export');
        if (!is_writable(MODX_CORE_PATH . 'export')) {
            $this->results['assets_export_writable']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span></p>';
            $this->results['assets_export_writable']['class'] = 'testFailed';
        } else {
            $this->results['assets_export_writable']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
            $this->results['assets_export_writable']['class'] = 'testPassed';
        }
    }

    /**
     * Verify if core/packages exists and is writable
     */
    function checkPackages() {
        /* core/packages exists? */
        $this->results['core_packages_exists']['msg'] = '<p>'.sprintf($this->install->lexicon['test_directory_exists'],'core/packages');
        if (!file_exists(MODX_CORE_PATH . 'packages')) {
            $this->results['core_packages_exists']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span></p>';
            $this->results['core_packages_exists']['class'] = 'testFailed';
        } else {
            $this->results['core_packages_exists']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
            $this->results['core_packages_exists']['class'] = 'testPassed';
        }

        /* packages writable? */
        if (!$this->install->config['unpacked']) {
            $this->results['core_packages_writable']['msg'] = '<p>'.sprintf($this->install->lexicon['test_directory_writable'],'core/packages');
            if (!is_writable(MODX_CORE_PATH . 'packages')) {
                $this->results['core_packages_writable']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span></p>';
                $this->results['core_packages_writable']['class'] = 'testFailed';
            } else {
                $this->results['core_packages_writable']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
                $this->results['core_packages_writable']['class'] = 'testPassed';
            }
        }
    }

    /**
     * Check context paths if inplace, else make sure paths can be written
     */
    function checkContexts() {
        $coreConfigsExist = false;
        if ($this->install->config['inplace']) {
            /* web_path */
            $this->results['context_web_exists']['msg'] = '<p>'.sprintf($this->install->lexicon['test_directory_exists'],$this->install->config['web_path']);
            if (!file_exists($this->install->config['web_path'])) {
                $this->results['context_web_exists']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span></p>';
                $this->results['context_web_exists']['class'] = 'testFailed';
            } else {
                $this->results['context_web_exists']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
                $this->results['context_web_exists']['class'] = 'testPassed';
            }

            /* mgr_path */
            $this->results['context_mgr_exists']['msg'] = '<p>'.sprintf($this->install->lexicon['test_directory_exists'],$this->install->config['mgr_path']);
            if (!file_exists($this->install->config['mgr_path'])) {
                $this->results['context_mgr_exists']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span></p>';
                $this->results['context_mgr_exists']['class'] = 'testFailed';
            } else {
                $this->results['context_mgr_exists']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
                $this->results['context_mgr_exists']['class'] = 'testPassed';
            }

            /* connectors_path */
            $this->results['context_connectors_exists']['msg'] = '<p>'.sprintf($this->install->lexicon['test_directory_exists'],$this->install->config['connectors_path']);
            if (!file_exists($this->install->config['connectors_path'])) {
                $this->results['context_connectors_exists']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span></p>';
                $this->results['context_connectors_exists']['class'] = 'testFailed';
            } else {
                $this->results['context_connectors_exists']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
                $this->results['context_connectors_exists']['class'] = 'testPassed';
            }
            if (file_exists($this->install->config['web_path'] . 'config.core.php') &&
                file_exists($this->install->config['connectors_path'] . 'config.core.php') &&
                file_exists($this->install->config['mgr_path'] . 'config.core.php')) {
                $coreConfigsExist = true;
            }
        }


        if ($this->mode == MODX_INSTALL_MODE_NEW || !$coreConfigsExist) {
            /* web_path */
            $this->results['context_web_writable']['msg'] = '<p>'.sprintf($this->install->lexicon['test_directory_writable'],$this->install->config['web_path']);
            if (!$this->_inWritableContainer($this->install->config['web_path'])) {
                $this->results['context_web_writable']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span></p>';
                $this->results['context_web_writable']['class'] = 'testFailed';
            } else {
                $this->results['context_web_writable']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
                $this->results['context_web_writable']['class'] = 'testPassed';
            }

            /* mgr_path */
            $this->results['context_mgr_writable']['msg'] = '<p>'.sprintf($this->install->lexicon['test_directory_writable'],$this->install->config['mgr_path']);
            if (!$this->_inWritableContainer($this->install->config['mgr_path'])) {
                $this->results['context_mgr_writable']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span></p>';
                $this->results['context_mgr_writable']['class'] = 'testFailed';
            } else {
                $this->results['context_mgr_writable']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
                $this->results['context_mgr_writable']['class'] = 'testPassed';
            }

            /* connectors_path */
            $this->results['context_connectors_writable']['msg'] = '<p>'.sprintf($this->install->lexicon['test_directory_writable'],$this->install->config['connectors_path']);
            if (!$this->_inWritableContainer($this->install->config['connectors_path'])) {
                $this->results['context_connectors_writable']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span></p>';
                $this->results['context_connectors_writable']['class'] = 'testFailed';
            } else {
                $this->results['context_connectors_writable']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
                $this->results['context_connectors_writable']['class'] = 'testPassed';
            }
        }
    }

    /**
     * Config file writable?
     */
    function checkConfig() {
        $configFileDisplay= 'config/' . MODX_CONFIG_KEY . '.inc.php';
        $configFilePath= MODX_CORE_PATH . $configFileDisplay;
        $this->results['config_writable']['msg'] = '<p>'.sprintf($this->install->lexicon['test_config_file'],'core/' . $configFileDisplay);
        if (!file_exists($configFilePath)) {
            /* make an attempt to create the file */
            @ $hnd = fopen($configFilePath, 'w');
            @ fwrite($hnd, '<?php // '.$this->install->lexicon['modx_configuration_file'].' ?>');
            @ fclose($hnd);
        }
        $isWriteable = is_writable($configFilePath);
        if (!$isWriteable) {
            $this->results['config_writable']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span></p><p><strong>'.sprintf($this->install->lexicon['test_config_file_nw'],MODX_CONFIG_KEY).'</strong></p>';
            $this->results['config_writable']['class'] = 'testFailed';
        } else {
            $this->results['config_writable']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
            $this->results['config_writable']['class'] = 'testPassed';
        }
    }

    /**
     * Check connection to database, as well as table prefix
     */
    function checkDatabase() {
        /* connect to the database */
        $this->results['dbase_connection']['msg'] = '<p>'.$this->install->lexicon['test_db_check'];
        $xpdo = $this->install->getConnection();
        if (!$xpdo || !$xpdo->connect()) {
            if ($this->mode > MODX_INSTALL_MODE_NEW) {
                $this->results['dbase_connection']['msg'] .= '<span class="notok">'.$this->install->lexicon['test_db_failed'].'</span><p />'.$this->install->lexicon['test_db_check_conn'].'</p>';
                $this->results['dbase_connection']['class'] = 'testFailed';
            } else {
                $this->results['dbase_connection']['msg'] .= '<span class="notok">'.$this->install->lexicon['test_db_failed'].'</span><p />'.$this->install->lexicon['test_db_setup_create'].'</p>';
                $this->results['dbase_connection']['class'] = 'testWarn';
            }
        } else {
            $this->results['dbase_connection']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
            $this->results['dbase_connection']['class'] = 'testPassed';
        }
        /* check the database collation if not specified in the configuration */
        /*
        if (!isset ($database_connection_charset) || empty ($database_connection_charset)) {
            if (!$rs = @ mysql_query("show session variables like
'collation_database'")) {
                $rs = @ mysql_query("show session variables like
'collation_server'");
            }
            if ($rs && $collation = mysql_fetch_row($rs)) {
                $database_collation = $collation[1];
            }
            if (empty ($database_collation)) {
                $database_collation = 'utf8_unicode_ci';
            }
            $database_charset = substr($database_collation, 0, strpos
($database_collation, '_') - 1);
            $database_connection_charset = $database_charset;
        }
        */

        /* check table prefix */
        if ($xpdo && $xpdo->connect()) {
            $this->results['table_prefix']['msg'] = '<p>'.sprintf($this->install->lexicon['test_table_prefix'],$this->install->config['table_prefix']);
            $count = 0;
            if ($stmt = $xpdo->query('SELECT COUNT(*) FROM `' . $this->install->config['table_prefix'] . 'system_settings`')) {
                $count = $stmt->fetchColumn();
                $stmt->closeCursor();
            }
            if ($this->mode == MODX_INSTALL_MODE_NEW) { /* if new install */
                if ($count > 0) {
                    $this->results['table_prefix']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span></b> - '.$this->install->lexicon['test_table_prefix_inuse'].'</p>';
                    $this->results['table_prefix']['class'] = 'testFailed';
                    $this->results['table_prefix']['msg'] .= '<p>'.$this->install->lexicon['test_table_prefix_inuse_desc'].'</p>';
                } else {
                    $this->results['table_prefix']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
                    $this->results['table_prefix']['class'] = 'testPassed';
                }
            } else { /* if upgrade */
                if ($count < 1) {
                    $this->results['table_prefix']['msg'] .= '<span class="notok">'.$this->install->lexicon['failed'].'</span></b> - '.$this->install->lexicon['test_table_prefix_nf'].'</p>';
                    $this->results['table_prefix']['class'] = 'testFailed';
                    $this->results['table_prefix']['msg'] .= '<p>'.$this->install->lexicon['test_table_prefix_nf_desc'].'</p>';
                } else {
                    $this->results['table_prefix']['msg'] .= '<span class="ok">'.$this->install->lexicon['ok'].'</span></p>';
                    $this->results['table_prefix']['class'] = 'testPassed';
                }
            }
        }
    }


    /**
     * Checks to see if a given path is in a writable container.
     *
     * @access private
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