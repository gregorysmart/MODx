<?php
/**
 * @package releaseme
 */
class ReleaseMe {
    var $modx;
    var $config = array();

    function ReleaseMe(&$modx) {
        $this->__construct($modx);
    }
    function __construct(&$modx) {
        $this->modx = $modx;
    }

    function initialize($ctx = 'mgr') {
        switch ($ctx) {
            case 'mgr':
                $this->config['base_path'] = $this->modx->config['context_path'];
                $this->config['base_url'] = $this->modx->config['context_url'];
                break;
            default:
                $this->config['base_path'] = RM_BASE_PATH;
                $this->config['base_url'] = $this->modx->config['site_url'].'assets/releaseme/';
                break;
        }
        $this->config['core_path'] = $this->config['base_path'].'core/';
        $this->config['processors_url'] = $this->config['base_url'].'core/processors/';
        $this->config['js_url'] = $this->config['base_url'].'assets/js/';
        $this->config['connector_url'] = $this->config['processors_url'].'connector.php';

        // add the model into MODx
        $this->modx->addPackage('releaseme',$this->config['core_path'].'model/');

        // now, load the 'default' lang foci, which is default.inc.php.
        // note how the colon separates the namespace from the foci name.
        //$this->modx->lexicon->load('releaseme:default');

        // TURN OFF AFTER BUILD
        // manually put in lexicon strings
        $_lang = array();
        include $this->config['base_path'].'_build/lexicon/en/default.inc.php';
        $this->modx->lexicon->_lexicon = array_merge($this->modx->lexicon->_lexicon,$_lang);
        unset($_lang);
    }
}