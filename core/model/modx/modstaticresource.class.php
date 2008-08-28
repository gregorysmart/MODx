<?php
/**
 * A derivative of modResource that stores content on the filesystem.
 *
 * {@inheritdoc}
 *
 * @package modx
 */
class modStaticResource extends modResource {
    /**
     * @var string Path of the file containing the source content, relative to
     * the {@link modStaticResource::$_sourcePath}.
     */
    var $_sourceFile= '';
    /**
     * @var integer Size of the source file content in bytes.
     */
    var $_sourceFileSize= 0;
    /**
     * @var string An absolute base filesystem path where the source file
     * exists.
     */
    var $_sourcePath= '';

    function modStaticResource(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
        $this->_fields['class_key']= 'modStaticResource';
    }

    /**
     * {@inheritdoc}
     *
     * Special handling to allow getter for content field to retrieve content
     * from a physical file.  MODx element tags can be used in the content to
     * specify a dynamic file path and are processed prior to looking up the
     * content.
     */
    function get($k, $format= '', $formatTemplate= '') {
        $return= false;
        switch ($k) {
            case 'content' :
                if (!file_exists($this->_fields['content'])) {
                    if (empty($this->_sourcePath) && isset ($this->xpdo->config['resource_static_path'])) {
                        $this->_sourcePath= $this->xpdo->config['resource_static_path'];
                    }
                    if (empty ($this->_sourceFile)) {
                        $this->_sourceFile= $this->_sourcePath . $this->_fields['content'];
                    }
                } else {
                    $this->_sourceFile= $this->_fields['content'];
                }
                if (!empty ($this->_sourceFile)) {
                    if ($this->xpdo->getParser() && $this->xpdo->parser->collectElementTags($this->_sourceFile, array())) {
                        $this->xpdo->parser->processElementTags('', $this->_sourceFile);
                    }

                    if (file_exists($this->_sourceFile)) {
                        $return= $this->getFileContent($this->_sourceFile);
                        if ($return === false) {
                            $this->xpdo->log(MODX_LOG_LEVEL_ERROR, "No content could be retrieved from source file: {$this->_sourceFile}");
                        }
                    } else {
                        $this->xpdo->log(MODX_LOG_LEVEL_ERROR, "Could not locate source file: {$this->_sourceFile}");
                    }
                } else {
                    $this->xpdo->log(MODX_LOG_LEVEL_ERROR, "No source file specified.");
                }
                break;
            default :
                $return= parent :: get($k, $format, $formatTemplate);
                break;
        }
        return $return;
    }

    /**
     * Retrieve the resource content stored in a physical file.
     *
     * @param string $file A path to the file representing the resource content.
     * @return string The content of the file, of false if it could not be
     * retrieved.
     */
    function getFileContent($file) {
        $content= false;
        if ($handle= @ fopen($file, 'rb')) {
            $filesize= filesize($file);
            $memory_limit= @ ini_get('memory_limit');
            if (!$memory_limit) $memory_limit= '8M';
            $byte_limit= $this->_bytes($memory_limit) * .5;
            if (strpos($file, '://') !== false || $filesize > $byte_limit) {
                $content= '';
                while (!feof($file)) {
                    $content .= @ fread($handle, 8192);
                }
            } else {
                $content= @ fread($handle, $filesize);
            }
            @ fclose($handle);
        } else {
            $this->xpdo->log(MODX_LOG_LEVEL_ERROR, "Could not open file for reading: {$file}");
        }
        return $content;
    }

    /**
     * Converts to bytes from PHP ini_get() format.
     *
     * PHP ini modifiers for byte values:
     * <ul>
     * 	<li>G = gigabytes</li>
     * 	<li>M = megabytes</li>
     * 	<li>K = kilobytes</li>
     * </ul>
     *
     * @access private
     * @param string $value Number of bytes represented in PHP ini value format.
     * @return integer The value converted to bytes.
     */
    function _bytes($value) {
        $value = trim($value);
        $modifier = strtolower($value{strlen($value)-1});
        switch($modifier) {
            case 'g':
                $value *= 1024;
            case 'm':
                $value *= 1024;
            case 'k':
                $value *= 1024;
        }
        return $value;
    }
}
?>