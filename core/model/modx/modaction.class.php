<?php
/**
 * Represents an action to a controller or connector.
 *
 * @package modx
 */
class modAction extends modAccessibleSimpleObject {
    function modAction(& $modx) {
        $this->__construct($modx);
    }
    function __construct(& $modx) {
        parent :: __construct($modx);
    }

	/**
	 * Overrides xPDOObject::save to cache the actionMap.
	 *
	 * {@inheritdoc}
	 */
    function save($cacheFlag = null) {
		$r = parent::save($cacheFlag);
		$this->rebuildCache();
		return $r;
    }

	/**
	 * Overrides xPDOObject::save to cache the actionMap.
	 *
	 * {@inheritdoc}
	 */
    function remove($ancestors = array()) {
		$r = parent::remove($ancestors);
		$this->rebuildCache();
		return $r;
    }

	/**
	 * Rebuilds the action map cache.
	 *
	 * @access public
	 * @return boolean True if successful.
	 */
    function rebuildCache() {
    	$rebuilt = false;
    	if (is_a($this->xpdo, 'modX')) {
            $this->modx =& $this->xpdo;
        	if ($cacheManager = $this->modx->getCacheManager()) {
    			$fileName= $this->modx->cachePath.'mgr/actions.cache.php';
    			if (file_exists($fileName)) @unlink($fileName);
    			if ($rebuilt = $cacheManager->generateActionMap($fileName)) {
        			include $fileName;
                }
    		}
        }
		return $rebuilt;
    }
}
?>