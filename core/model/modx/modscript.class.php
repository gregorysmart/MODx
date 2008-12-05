<?php
/**
 * An element representing executable PHP script content.
 *
 * {@inheritdoc}
 *
 * @abstract Implement a derivative class that defines a table for storage.
 * @package modx
 */
class modScript extends modElement {
    var $_scriptName= null;
    var $_scriptFileName= null;

    function modScript(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }

    function set($k, $v= null, $vType= '') {
        if (in_array($k,array('snippet','plugincode'))) {
            $v= trim($v);
            if (strncmp($v, '<?', 2) == 0) {
                $v= substr($v, 2);
                if (strncmp($v, 'php', 3) == 0) $v= substr($v, 3);
            }
            if (substr($v, -2, 2) == '?>') $v= substr($v, 0, -2);
            $v= trim($v, " \n\r\0\x0B");
        }
        $set= parent :: set($k, $v, $vType);
        return $set;
    }

    function process($properties= null, $content= null) {
        parent :: process($properties, $content);
        if (!$this->_processed) {
            $scriptName= $this->getScriptName();
            if (!$this->_result= function_exists($scriptName)) {
                if (!file_exists($this->getScriptFileName())) {
                    if ($cacheManager= $this->xpdo->getCacheManager()) {
                        $cacheManager->generateScriptFile($this);
                    }
                }
                if (file_exists($this->getScriptFileName())) {
                    $this->_result= include ($this->_scriptFileName);
                }
            }
            if ($this->_result) {
                $this->xpdo->event->params= $this->_properties; /* store params inside event object */
                ob_start();
                $this->_output= $scriptName($this->_properties);
                $this->_output= ob_get_contents() . $this->_output;
                ob_end_clean();
                if ($this->_output && is_string($this->_output)) {
                    /* collect element tags in the evaluated content and process them */
                    $maxIterations= isset ($this->xpdo->config['parser_max_iterations']) ? intval($this->xpdo->config['parser_max_iterations']) : 10;
                    $this->xpdo->parser->processElementTags($this->_tag, $this->_output, false, false, '[[', ']]', array(), $maxIterations);
                }
                $this->filterOutput();
                unset ($this->xpdo->event->params);
                $this->cache();
            }
        }
        $this->_processed= true;

        /* finally, return the processed element content */
        return $this->_output;
    }

    /**
     * Get the name of the script source file, written to the cache file system
     *
     * @return string The filename containing the function generated from the
     * script element.
     */
    function getScriptFileName() {
        if ($this->_scriptFileName === null) {
            $scriptPath= str_replace('_', '/', $this->getScriptName());
            $this->_scriptFileName= $this->xpdo->cachePath . $scriptPath . '.cache.php';
        }
        return $this->_scriptFileName;
    }

    /**
     * Get the name of the function the script has been given.
     *
     * @return string The function name representing this script element.
     */
    function getScriptName() {
        if ($this->_scriptName === null) {
            $className= $this->_class;
            $this->_scriptName= $this->xpdo->context->get('key') . '_elements_' . $className . '_' . $this->get('id');
        }
        return $this->_scriptName;
    }
}
