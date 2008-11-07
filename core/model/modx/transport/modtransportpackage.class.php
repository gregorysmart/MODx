<?php
/**
 * @package modx
 * @subpackage transport
 */

/**
 * Represents an xPDOTransport package as required for MODx Web Transport Facilities.
 *
 * @package modx
 * @subpackage transport
 */
class modTransportPackage extends xPDOObject {
    /**
     * @var string The unique identifier of a package.
     * @access public
     */
    var $identifier = null;
    /**
     * @var string The version number of a package.
     * @access public
     */
    var $version = null;
    /**
     * @var string The release number of a package.
     * @access public
     */
    var $release = null;
    /**
     * @var mixed The package to transport.
     * @access protected
     */
    var $package = null;

    function modTransportPackage(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }

    /**
     * Overrides xPDOObject::save to set a default created time if new.
     *
     * {@inheritdoc}
     */
    function save($cacheFlag= null) {
        if ($this->_new && !$this->get('created')) {
            $this->set('created', strftime('%Y-%m-%d %H:%M:%S'));
        }
        $saved= parent :: save($cacheFlag);
        return $saved;
    }

    /**
     * Overrides xPDOObject::set. Checks if signature is set, and if so,
     * parses it and sets the source if is a new package.
     *
     * {@inheritdoc}
     */
    function set($k, $v, $vType = '') {
        $set = parent :: set($k, $v, $vType);
        if ($k == 'signature') {
            $this->parseSignature();
            if ($this->_new && !$this->get('source')) {
                $this->set('source', $this->get('signature') . '.transport.zip');
            }
        }
        return $set;
    }

    /**
     * Parses the signature.
     *
     * @return boolean True if successful.
     */
    function parseSignature() {
        $parsed = false;
        $sig = $this->get('signature');
        if ($sig != NULL) {
            $parsed = explode('-',$sig);
            if (count($parsed) == 3) {
                $this->identifier = next($parsed);
                $this->version = next($parsed);
                $this->release = next($parsed);
                $parsed = true;
            }
        }
        return $parsed;
    }

    /**
     * Gets the package's transport mechanism.
     * @param integer $state The state of the package.
     * @return mixed The package.
     */
    function getTransport($state = -1) {
        if (!is_object($this->package) || !is_a($this->package, 'xPDOTransport')) {
            if ($this->xpdo->loadClass('transport.xPDOTransport', XPDO_CORE_PATH, true, true)) {
                if ($workspace = $this->getOne('Workspace')) {
                    $packageDir = $workspace->get('path') . 'packages/';
                    if ($sourceFile = $this->get('source')) {
                        $transferred= file_exists($packageDir . $sourceFile);
                        if (!$transferred) {
                            if (@ ini_get('allow_url_fopen')) {
                                if (!$transferred= $this->transferPackage($sourceFile, $packageDir)) {
                                    $this->xpdo->log(XPDO_LOG_LEVEL_ERROR, "Could not transfer package {$sourceFile} to {$packageDir}");
                                } else {
                                    $sourceFile= basename($sourceFile);
                                }
                            } else {
                                $this->xpdo->log(XPDO_LOG_LEVEL_ERROR, "Could not transfer package {$sourceFile} to {$packageDir}; allow_url_fopen is not enabled on your configuration");
                            }
                        }
                        if ($transferred) {
                            if ($state < 0) $state = $this->get('state');
                            if ($this->package = xPDOTransport :: retrieve($this->xpdo, $packageDir . $sourceFile, $packageDir, $state)) {
                                if ($state == XPDO_TRANSPORT_STATE_PACKED) {
                                    $this->set('state', XPDO_TRANSPORT_STATE_UNPACKED);
                                }
                                $this->set('source', $sourceFile);
                                $this->set('manifest', array(
                                    XPDO_TRANSPORT_MANIFEST_VERSION => $this->package->manifestVersion,
                                    XPDO_TRANSPORT_MANIFEST_ATTRIBUTES => $this->package->attributes,
                                    XPDO_TRANSPORT_MANIFEST_VEHICLES => $this->package->vehicles
                                ));
                                $this->set('attributes', $this->package->attributes);
                                $this->save();
                            }
                        }
                    } else {
                        $this->xpdo->log(XPDO_LOG_LEVEL_ERROR, "No valid source specified for the package");
                    }
                }
            }
        }
        return $this->package;
    }

    /**
     * Overrides xPDOObject::remove. Removes and uninstalls the package.
     *
     * {@inheritdoc}
     */
    function remove($force = false,$ancestors = array()) {
        $removed = false;
        if ($this->get('installed') == null || $this->get('installed') == '0000-00-00 00:00:00') {
            $uninstalled = true;
        } else {
            $uninstalled = $this->uninstall();
        }

        if ($uninstalled || $force) {
            $removed= parent::remove($ancestors);
        }

        return $removed;
    }


    /**
     * Installs the package.
     *
     * @return boolean True if successful.
     */
    function install() {
        $installed = false;
        if ($this->getTransport()) {
            $this->xpdo->log(XPDO_LOG_LEVEL_INFO,'Grabbing package workspace...');
            $this->getOne('Workspace');
            $wc = isset($this->Workspace->config) && is_array($this->Workspace->config) ? $this->Workspace->config : array();
            $at = is_array($this->get('attributes')) ? $this->get('attributes') : array();
            $attributes = array_merge($wc, $at);
            @ini_set('max_execution_time', 0);
            $this->xpdo->log(XPDO_LOG_LEVEL_INFO,'Workspace environment initiated, now installing package...');
            if ($this->package->install($attributes)) {
                $installed = true;
                $this->set('installed', strftime('%Y-%m-%d %H:%M:%S'));
                $this->set('attributes', $attributes);
                $this->save();
            }
        }
        return $installed;
    }

    /**
     * Uninstalls the package.
     *
     * @return boolean True if successful.
     */
    function uninstall() {
        $uninstalled = false;
        if ($this->getTransport()) {
            $this->getOne('Workspace');
            $wc = isset($this->Workspace->config) && is_array($this->Workspace->config) ? $this->Workspace->config : array();
            $at = is_array($this->get('attributes')) ? $this->get('attributes') : array();
            $attributes = array_merge($wc,$at);
            @ini_set('max_execution_time', 0);
            if ($this->package->uninstall($attributes)) {
                $uninstalled = true;
                $this->set('installed',NULL);
                $this->set('attributes',$attributes);
                $this->save();
            } else {
                $this->xpdo->log(XPDO_LOG_LEVEL_ERROR, "Error occurred during uninstall.");
            }
        } else {
            $this->xpdo->log(XPDO_LOG_LEVEL_ERROR, "Could not load transport package.");
        }
        return $uninstalled;
    }

    /**
     * Transfers the package from one directory to another.
     *
     * @param string $sourceFile The file to transfer.
     * @param string $targetDir The directory to transfer into.
     * @return boolean True if successful.
     */
    function transferPackage($sourceFile, $targetDir) {
        $transferred= false;
        $content= '';
        if (is_dir($targetDir) && is_writable($targetDir)) {
            $source= $this->get('service_url') . $sourceFile;
            if ($handle= @ fopen($source, 'rb')) {
                $filesize= filesize($source);
                $memory_limit= @ ini_get('memory_limit');
                if (!$memory_limit) $memory_limit= '8M';
                $byte_limit= $this->_bytes($memory_limit) * .5;
                if (strpos($source, '://') !== false || $filesize > $byte_limit) {
                    $content= @ file_get_contents($source);
                } else {
                    $content= @ fread($handle, $filesize);
                }
                @ fclose($handle);
            } else {
                $this->xpdo->log(MODX_LOG_LEVEL_ERROR, "Could not open file for reading: {$source}");
            }
            if ($content) {
                if ($cacheManager= $this->xpdo->getCacheManager()) {
                    $filename= basename($source);
                    $target= $targetDir . $filename;
                    $transferred= $cacheManager->writeFile($target, $content);
                }
            }
        } else {
            $this->xpdo->log(MODX_LOG_LEVEL_ERROR,'Target directory is either not a directory or writable: '.$targetDir);
        }
        return $transferred;
    }


    /**
     * Converts to bytes from PHP ini_get() format.
     *
     * PHP ini modifiers for byte values:
     * <ul>
     *  <li>G = gigabytes</li>
     *  <li>M = megabytes</li>
     *  <li>K = kilobytes</li>
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