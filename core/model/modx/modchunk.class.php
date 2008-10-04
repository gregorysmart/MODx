<?php
/**
 * Represents a chunk of static HTML content.
 *
 * @package modx
 */
class modChunk extends modElement {
    function modChunk(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
        $this->_token = '$';
    }

	/**
	 * Overrides modElement::process to initialize the Chunk into the element cache,
	 * as well as set placeholders and filter the output.
	 *
	 * {@inheritdoc}
	 */
    function process($properties= null, $content= null) {
        parent :: process($properties, $content);
        if ($this->_cacheable && isset ($this->xpdo->elementCache[$this->_tag])) {
            $this->_output= $this->xpdo->elementCache[$this->_tag];
        } else {
            // turn the processed properties into placeholders
            $this->xpdo->toPlaceholder($this->get('name'), $this->_properties);

            // get chunk content
            if (!is_string($this->_content) || empty($this->_content))
                $this->_content= $this->get('snippet');
            if (is_string($this->_content) && !empty ($this->_content)) {
                // collect element tags in the content and process them
                $maxIterations= isset ($this->xpdo->config['parser_max_iterations']) ? intval($this->xpdo->config['parser_max_iterations']) : 10;
                $this->xpdo->parser->processElementTags($this->_tag, $this->_content, false, false, '[[', ']]', array(), $maxIterations);
            }

            $this->filterOutput();

            // copy the instance content source to the output buffer
            $this->_output= $this->_content;

            $this->cache();
            
            // remove the placeholders set from the properties of this element
            $this->xpdo->unsetPlaceholders($this->get('name') . '.');
        }
        $this->_processed= true;

        // finally, return the processed element content
        return $this->_output;
    }
}
?>