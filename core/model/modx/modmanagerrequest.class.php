<?php
/**
 * modManagerRequest
 *
 * @package modx
 */

/** Make sure the parent class is included. */
@require_once MODX_CORE_PATH . 'model/modx/modrequest.class.php';

/**
 * Encapsulates the interaction of MODx manager with an HTTP request.
 *
 * {@inheritdoc}
 *
 * @package modx
 */
class modManagerRequest extends modRequest {
	/**
	 * @var string The action to load.
	 * @access public
	 */
    var $action= null;
    /**
     * @var modError The error handler for the request.
     * @access public
     */
    var $error= null;
    var $actionVar = 'a';
    var $defaultAction = 0;

    function modManagerRequest(& $modx) {
        $this->__construct($modx);
    }
    function __construct(& $modx) {
        parent :: __construct($modx);
		if ($this->modx->actionMap === null || !is_array($this->modx->actionMap)) {
			$this->loadActionMap();
		}
    }

    /**
     * Initializes the manager context.
     *
     * @access public
     * @return boolean True if successful.
     */
    function initialize() {
        define('MODX_INCLUDES_PATH',$this->modx->config['manager_path'].'includes/');

        // load smarty template engine
        $this->modx->getService('smarty', 'smarty.modSmarty', '', array(
            'template_dir' => $this->modx->config['manager_path'] . 'templates/' . $this->modx->config['manager_theme'] . '/',
        ));
        // load context-specific cache dir
        $this->modx->smarty->setCachePath($this->modx->context->get('key').'/smarty/');

        $this->modx->smarty->assign('_config',$this->modx->config);
        $this->modx->smarty->assign_by_ref('modx',$this->modx);

        // send anti caching headers
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        // send the charset header
        header('Content-Type: text/html; charset='.$this->modx->config['modx_charset']);


        // include version info
        if ($this->modx->version === null) $this->modx->getVersionData();

        // andrazk 20070416 - if installer is running, destroy active sessions
        $installGoingOn= 0;
        if (file_exists(MODX_CORE_PATH . 'cache/installProc.inc.php')) {
            include_once (MODX_CORE_PATH . 'cache/installProc.inc.php');
            if (isset ($installStartTime)) {
                if ((time() - $installStartTime) > 5 * 60) { // if install flag older than 5 minutes, discard
                    unset ($installStartTime);
                    @ chmod(MODX_CORE_PATH . 'cache/installProc.inc.php', 0755);
                    unlink(MODX_CORE_PATH . 'cache/installProc.inc.php');
                } else {
                    if (strtoupper($_SERVER['REQUEST_METHOD']) != 'POST') {
                        if (isset ($_COOKIE[session_name()])) {
                            session_unset();
                            @ session_destroy();
                            setcookie(session_name(), '', 0);
                        }
                        $installGoingOn= 1;
                    }
                }
            }
        }

        // andrazk 20070416 - if session started before install and was not destroyed yet
        if (isset ($GLOBALS['lastInstallTime'])) {
            if (isset ($_SESSION['mgrValidated'])) {
                if (isset ($_SESSION['modx.session.created.time'])) {
                    if ($_SESSION['modx.session.created.time'] < $GLOBALS['lastInstallTime']) {
                        if (strtoupper($_SERVER['REQUEST_METHOD']) != 'POST') {
                            if (isset ($_COOKIE[session_name()])) {
                                session_unset();
                                @ session_destroy();
                                setcookie(session_name(), '', 0);
                            }
                            header('HTTP/1.0 307 Redirect');
                            header('Location: ' . MODX_MANAGER_URL . 'index.php?installGoingOn=2');
                        }
                    }
                }
            }
        }

        if (!isset ($_SESSION['mgrValidated'])) {
            $modx = & $this->modx;
            include_once $this->modx->config['manager_path'] . 'controllers/security/login.php';
            exit();
        } else {
            // log user action
            if (getenv('HTTP_CLIENT_IP')) {
                $ip= getenv('HTTP_CLIENT_IP');
            } else {
                if (getenv('HTTP_X_FORWARDED_FOR')) {
                    $ip= getenv('HTTP_X_FORWARDED_FOR');
                } else {
                    if (getenv('REMOTE_ADDR')) {
                        $ip= getenv('REMOTE_ADDR');
                    } else {
                        $ip= 'UNKNOWN';
                    }
                }
            }
            $_SESSION['ip']= $ip;
            $itemid= isset ($_REQUEST[$this->modx->config['request_param_id']]) ? $_REQUEST[$this->modx->config['request_param_id']] : '';
            $lasthittime= time();
            $a= isset ($_REQUEST['a']) ? $_REQUEST['a'] : '';
            if ($a != 1) {
                if (!intval($itemid))
                    $itemid= 'NULL';
                $activeUserTbl= $this->modx->getTableName('modActiveUser');
                $sql= "REPLACE INTO {$activeUserTbl} (internalKey, username, lasthit, action, id, ip) values(" . $this->modx->getLoginUserID('mgr') . ", '{$_SESSION['mgrShortname']}', '{$lasthittime}', '{$a}', {$itemid}, '{$ip}')";
                if (!$rs= $this->modx->exec($sql)) {
                    $this->modx->log(XPDO_LOG_LEVEL_ERROR, 'Error logging active user information! SQL: ' . $sql . "\n" . print_r($this->modx->errorInfo(), 1));
                }
            }
        }

        if (!isset($this->modx->config['manager_language'])) {
            $this->modx->config['manager_language'] = $this->modx->cultureKey;
        }

        return true;
    }

    /**
     * The primary MODx manager request handler (a.k.a. controller).
     *
     * @access public
     * @return boolean True if a request is handled without interruption.
     */
    function handleRequest() {
        // Load error handling class
        $this->loadErrorHandler('modArrayError');

        // save page to manager object
        $this->action = isset($_REQUEST[$this->actionVar]) ? $_REQUEST[$this->actionVar] : $this->defaultAction;

        // invoke OnManagerPageInit event
        $this->modx->invokeEvent('OnManagerPageInit',array('action' => $this->action));
        $this->prepareResponse();
    }

	/**
	 * Loads the actionMap, and generates a cache file if necessary.
	 *
	 * @access public
	 * @return boolean True if successful.
	 */
	function loadActionMap() {
		$loaded = false;
		$fileName= $this->modx->cachePath . 'mgr/actions.cache.php';
		if (file_exists($fileName)) {
			include_once $fileName;
			$loaded = true;
		} else {
			if ($cacheManager = $this->modx->getCacheManager()) {
				$cacheManager->generateActionMap($fileName);
				@include_once $fileName;
				$loaded = true;
			}
		}
		return $loaded;
	}

    /**
     * Prepares the MODx response to a mgr request that is being handled.
     *
     * @todo Redo the error message when a modAction is not found.
	 * @access public
     * @return boolean True if the response is properly prepared.
     */
    function prepareResponse() {
        $modx= & $this->modx;
		$error= & $this->modx->error;
		if ($this->modx->actionMap === null || !is_array($this->modx->actionMap)) {
			$this->loadActionMap();
		}

        $_lang = $this->modx->lexicon->fetch();

        if ($this->action === null || $this->action == '') {
            // this looks to be a top-level frameset request, so let's serve up ext
            $this->modx->smarty->assign('_lang',$_lang);
            include_once $this->modx->config['manager_path'] . 'controllers/header.php';
        } else {
			if (isset($this->modx->actionMap[$this->action])) {
				$action = $this->modx->actionMap[$this->action];

                $topics = explode(',',$action['lang_topics']);
                foreach ($topics as $topic) { $this->modx->lexicon->load($topic); }
                $this->modx->smarty->assign('_lang_topics',$action['lang_topics']);
                $this->modx->smarty->assign('_lang',$this->modx->lexicon->fetch());
                $this->modx->smarty->assign('_ctx',$action['context']);


                if ($action['haslayout']) {
                    include_once $this->modx->config['manager_path'].'controllers/frame-header.php';
                }

                // find context path
                if (!isset($action['context']) || $action['context'] == 'mgr') {
                    $f = $action['context_path'].'controllers/'.$action['controller'];

                } else { // if a custom 3rd party path
                     $this->modx->smarty->setTemplatePath($action['context_path'].'core/templates/');
                     $f = $action['context_path'].'core/controllers/'.$action['controller'];
                }

                // set context url and path
                $this->modx->config['context_url'] = $action['context_url'];
                $this->modx->config['context_path'] = $action['context_path'];

				// if action is a directory, load base index.php
				if (substr($f,strlen($f)-1,1) == '/') {
					$f .= 'index';
				}
				// append .php
				if (file_exists($f.'.php')) {
					$f = $f.'.php';
					include_once $f;
				// for actions that don't have trailing / but reference index
				} elseif (file_exists($f.'/index.php')) {
					$f = $f.'/index.php';
					include_once $f;
				}
				if ($action['haslayout']) {
					// reset path to core modx path for header/footer
                    $this->modx->smarty->setTemplatePath($modx->config['manager_path'] . 'templates/' . $this->modx->config['manager_theme'] . '/');
                    include_once $this->modx->config['manager_path'].'controllers/footer.php';
				}
			} else {
				// no action found. redo this eventually.
				die('No action with ID '.$this->action.' found.');
				echo '
					<div class="subTitle">
						<span class="right"><img src="media/'.$modx->config['manager_theme'].'/images/_tx_.gif" width="1" height="5" /><br />
						'.$modx->lexicon('functionnotimpl').'</span>
					</div>
					<div class="sectionHeader"><img src="media/'.$modx->config['manager_theme'].'/images/misc/dot.gif" alt="." />&nbsp;
						'.$modx->lexicon('functionnotimpl').'</div><div class="sectionBody">
						'.$modx->lexicon('functionnotimpl_message').'
					</div>
				';
			}
        }
        exit();
    }
}