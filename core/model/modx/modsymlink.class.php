<?php
/**
 * A modResource derivative the represents a symbolic link.
 *
 * {@inheritdoc}
 *
 * @package modx
 */
class modSymLink extends modResource {
    function modSymLink(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
        $this->set('type', 'reference');
        $this->set('class_key', 'modSymLink');
    }

    /**
     * Process the modSymLink and forward to the specified resource.
     */
    function process() {
        $this->_content= $this->get('content');
        if (empty ($this->_content)) {
            $this->xpdo->sendErrorPage();
        }
        if (is_numeric($this->_content)) {
            $this->_output= intval($this->_content);
        } else {
            $parser= $this->xpdo->getParser();
            $maxIterations= isset ($this->xpdo->config['parser_max_iterations']) ? intval($this->xpdo->config['parser_max_iterations']) : 10;
            $this->xpdo->parser->processElementTags($this->_tag, $this->_content, true, true, '[[', ']]', array(), $maxIterations);
        }
        if (is_numeric($this->_content)) {
            $this->_output= intval($this->_content);
        } else {
            $this->_output= $this->_content;
        }
        $this->xpdo->sendForward($this->_output);
    }
}