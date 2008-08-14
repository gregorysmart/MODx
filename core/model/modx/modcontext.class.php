<?php
/**
 * Represents a virtual site context within a modX repository.
 *
 * @package modx
 */
class modContext extends modAccessibleObject {
    var $config= null;
    var $aliasMap= null;
    var $resourceMap= null;
    var $resourceListing= null;
    var $documentListing= null;
    var $documentMap= null;
    var $eventMap= null;
    var $pluginCache= null;
    var $_cacheFileName= '[contextKey]/context.cache.php';

    function modContext(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
        $this->documentListing= & $this->resourceListing;
    }
    
    /**
     * Prepare a context for use.
     *
     * @uses modCacheManager::generateContext() This method is responsible for
     * preparing the context for use.
     * {@internal You can override this behavior here, but you will only need to
     * override the modCacheManager::generateContext() method in most cases}}
     * @param boolean $regenerate If true, the existing cache file will be ignored
     * and regenerated.
     * @return boolean Indicates if the context was successfully prepared.
     */
    function prepare($regenerate= false) {
        $rv= false;
        if ($this->config === null && $cacheManager= $this->xpdo->getCacheManager()) {
            if (!$cacheFileName= $this->getCacheFileName()) {
                return false;
            }
            if (!file_exists($this->xpdo->cachePath . $cacheFileName) || $regenerate) {
                $cacheManager->generateContext($this->get('key'));
            }
            if (!$rv= include ($this->xpdo->cachePath . $this->getCacheFileName())) {
                $this->xpdo->_log(XPDO_LOG_LEVEL_ERROR, 'Could not load context configuration file: ' . $this->xpdo->cachePath . $this->_cacheFileName);
            }
        }
        return (boolean) $rv;
    }

    /**
     * Returns the file name representing this context in the cache.
     *
     * @return string The cache filename.
     */
    function getCacheFileName() {
        $this->_cacheFileName= str_replace('[contextKey]', $this->get('key'), $this->_cacheFileName);
        return $this->_cacheFileName;
    }
    
    /**
     * Loads the access control policies applicable to this element.
     *
     * {@inheritdoc}
     */
    function findPolicy($context = '') {
        $policy = array();
        $context = !empty($context) ? $context : $this->xpdo->context->get('key');
        if (empty($this->_policies) || !isset($this->_policies[$context])) {
            $accessTable = $this->xpdo->getTableName('modAccessContext');
            $policyTable = $this->xpdo->getTableName('modAccessPolicy');
            $sql = "SELECT acl.target, acl.principal, acl.authority, acl.policy, p.data FROM {$accessTable} acl " .
                    "LEFT JOIN {$policyTable} p ON p.id = acl.policy " .
                    "WHERE acl.principal_class = 'modUserGroup' " .
                    "AND acl.target = :context " .
                    "GROUP BY acl.target, acl.principal, acl.authority, acl.policy";
            $bindings = array(
                ':context' => $this->get('key')
            );
            $query = new xPDOCriteria($this->xpdo, $sql, $bindings);
            if ($query->stmt && $query->stmt->execute()) {
                foreach ($query->stmt->fetchAll(PDO_FETCH_ASSOC) as $row) {
                    $policy['modAccessContext'][$row['target']][$row['principal']] = array(
                        'authority' => $row['authority'],
                        'policy' => $row['data'] ? xPDO :: fromJSON($row['data'], true) : array(),
                    );
                }
            }
            $this->_policies[$context] = $policy;
        } else {
            $policy = $this->_policies[$context];
        }
        return $policy;
    }
}