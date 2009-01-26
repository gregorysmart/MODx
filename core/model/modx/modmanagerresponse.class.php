<?php
require_once(MODX_CORE_PATH . 'model/modx/modresponse.class.php');

/**
 * Encapsulates an HTTP response from the MODx manager.
 *
 * {@inheritdoc}
 *
 * @package modx
 */
class modManagerResponse extends modResponse {
    function modManagerResponse(& $modx) {
        $this->__construct($modx);
    }
    function __construct(& $modx) {
        parent :: __construct($modx);
    }

    function outputContent($options = array()) {
        $modx= & $this->modx;
        $error= & $this->modx->error;

        $action = '';
        if (!isset($this->modx->request) || !isset($this->modx->request->action)) {
            $this->body = $this->modx->error->failure($modx->lexicon('action_err_ns'));
        } else {
            $action =& $this->modx->request->action;
        }

        $this->modx->lexicon->load('dashboard','topmenu','file');
        if ($action == 0) $action = 1;

        if (isset($this->modx->actionMap[$action])) {
            $act = $this->modx->actionMap[$action];

            /* assign custom action topics to smarty, so can load custom topics for each page */
            $this->modx->lexicon->load('action');
            $topics = explode(',',$act['lang_topics']);
            foreach ($topics as $topic) { $this->modx->lexicon->load($topic); }
            $this->modx->smarty->assign('_lang_topics',$act['lang_topics']);
            $this->modx->smarty->assign('_lang',$this->modx->lexicon->fetch());
            $this->modx->smarty->assign('_ctx',$act['context']);

            $this->body = '';
            if ($act['haslayout']) {
                $this->body .= include $this->modx->config['manager_path'] . 'controllers/header.php';
            }

            /* find context path */
            if (!isset($act['context']) || $act['context'] == 'mgr') {
                $f = $act['context_path'].'controllers/'.$act['controller'];

            } else { /* if a custom 3rd party path */
                 $this->modx->smarty->setTemplatePath($act['context_path'].'core/templates/');
                 $f = $act['context_path'].'core/controllers/'.$act['controller'];
            }

            /* set context url and path */
            $this->modx->config['context_url'] = $act['context_url'];
            $this->modx->config['context_path'] = $act['context_path'];

            /* if action is a directory, load base index.php */
            if (substr($f,strlen($f)-1,1) == '/') {
                $f .= 'index';
            }
            /* append .php */
            if (file_exists($f.'.php')) {
                $f = $f.'.php';
                $this->body .= include $f;
            /* for actions that don't have trailing / but reference index */
            } elseif (file_exists($f.'/index.php')) {
                $f = $f.'/index.php';
                $this->body .= include $f;
            }
            if ($act['haslayout']) {
                /* reset path to core modx path for header/footer */
                $this->modx->smarty->setTemplatePath($modx->config['manager_path'] . 'templates/' . $this->modx->config['manager_theme'] . '/');
                $this->body .= include_once $this->modx->config['manager_path'].'controllers/footer.php';
            }
        } else {
            $this->body = $this->modx->error->failure('No action with ID '.$action.' found.');
        }
        if (empty($this->body)) {
            $this->body = $this->modx->error->failure($modx->lexicon('action_err_ns'));
        }
        if (is_array($this->body)) {
            $this->modx->smarty->assign('_e', $this->body);
            $this->modx->smarty->display('error.tpl');
        }

        echo $this->body;
        exit();
    }
}