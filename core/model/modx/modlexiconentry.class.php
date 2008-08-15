<?php
/**
 * @package modx
 * @subpackage mysql
 */
class modLexiconEntry extends xPDOSimpleObject {
    function modLexiconEntry(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }

    /**
     * Clears the cache for the entry
     */
    function clearCache() {
    	if ($this->xpdo && $this->xpdo->lexicon) {
    		return $this->xpdo->lexicon->clearCache($this->get('namespace').'/'.$this->get('focus').'.cache.php');
        }
        return false;
    }

    /**
     * Persist new or changed modLexiconEntry to the database container.
     *
     * {@inheritdoc}
     */
    function save($cacheFlag= null) {
        if ($this->_new) {
            if (!$this->get('createdon')) $this->set('createdon', strftime('%Y-%m-%d %H:%M:%S'));
        }
        $rt= parent :: save($cacheFlag);
        return $rt;
    }
}
?>