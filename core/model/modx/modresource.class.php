<?php
/**
 * Represents a web resource managed by the modX framework.
 *
 * @package modx
 */
class modResource extends modAccessibleSimpleObject {
    /**
     * Represents the cacheable content for a resource.
     * @var string
     */
    var $_content= '';
    /**
     * Represents the output the resource produces.
     * @var string
     */
    var $_output= '';
    /**
     * The context the resource is requested from.
     *
     * Note that this is different than the context_key field that describes a
     * primary context for the resource.
     * @var string
     */
    var $_contextKey= null;
    /**
     * Indicates if the resource has already been processed.
     * @var boolean
     */
    var $_processed= false;
    /**
     * The cache filename for the resource in the context.
     * @var string
     */
    var $_cacheFileName= null;
    /**
     * Indicates if the site cache should be refreshed when saving changes.
     * @var boolean
     */
    var $_refreshCache= true;

    function modResource(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
        $this->_contextKey= isset ($this->xpdo->context) ? $this->xpdo->context->get('key') : 'web';
        $this->_cacheFileName= "[contextKey]/resources/[id].cache.php";
    }

    /**
     * Process a resource, transforming source content to output.
     *
     * @return string The raw, cacheable content of a resource.
     */
    function process() {
        if ($this->_processed && $this->_content && $this->get('cacheable')) {
            return $this->_content;
        }
        $this->_content= '';
        $this->_output= '';
        $this->xpdo->getParser();
        if ($baseElement= $this->getOne('modTemplate')) {
            if ($baseElement->process()) {
                $this->_content= $baseElement->_output;
                $this->_processed= true;
            }
        } else {
            $this->_content= '[[*content]]';
            $maxIterations= isset ($this->xpdo->config['parser_max_iterations']) ? intval($this->xpdo->config['parser_max_iterations']) : 10;
            $this->xpdo->parser->processElementTags('', $this->_content, false, false, '[[', ']]', array(), $maxIterations);
            $this->_processed= true;
        }
        if($this->get('hasmetatags') || $this->get('haskeywords')) {
            $this->_content = $this->xpdo->mergeDocumentMETATags($this->_content);
        }
        return $this->_content;
    }

    /**
     * Returns the cache filename for this instance in the current context.
     *
     * @return string The cache filename.
     */
    function getCacheFileName() {
        if ($this->get('id') && strpos($this->_cacheFileName, '[') !== false) {
            $this->_cacheFileName= str_replace('[contextKey]', $this->_contextKey, $this->_cacheFileName);
            $this->_cacheFileName= str_replace('[id]', $this->get('id'), $this->_cacheFileName);
        }
        return $this->_cacheFileName;
    }

    /**
     * Gets a collection of objects related by aggregate or composite relations.
     *
     * {@inheritdoc}
     *
     * Includes special handling for related objects with alias {@link
     * modTemplateVar}, respecting framework security unless specific criteria
     * are provided.
     *
     * @todo Refactor to use the new ABAC security model.
     */
    function getMany($class, $criteria= null, $cacheFlag= false) {
        $collection= array ();
        if ($class === 'modTemplateVar' && ($criteria === null || strtolower($criteria) === 'all')) {
            $c = $this->xpdo->newQuery('modTemplateVar');
            $c->select('
                DISTINCT modTemplateVar.*,
                IF(ISNULL(tvc.value),modTemplateVar.default_text,tvc.value) AS value
            ');
            $c->innerJoin('modTemplateVarTemplate','tvtpl',array(
                '`tvtpl`.`tmplvarid` = `modTemplateVar`.`id`',
                '`tvtpl`.templateid' => $this->template,
            ));
            $c->leftJoin('modTemplateVarResource','tvc',array(
                '`tvc`.`tmplvarid` = `modTemplateVar`.`id`',
                '`tvc`.contentid' => $this->id,
            ));
            $c->sortby('`tvtpl`.`rank`,`modTemplateVar`.`rank`');
                        
            $collection = $this->xpdo->getCollection('modTemplateVar', $c);
        } else {
            $collection= parent :: getMany($class, $criteria);
        }
        return $collection;
    }

    /**
     * Set a field value by the field key or name.
     *
     * {@inheritdoc}
     *
     * Additional logic added for the following fields:
     * 	-alias: Applies {@link modResource::cleanAlias()}
     *  -contentType: Calls {@link modResource::addOne()} to sync contentType
     *  -content_type: Sets the contentType field appropriately
     */
    function set($k, $v= null, $vType= '') {
        $rt= false;
        switch ($k) {
            case 'alias' :
                $v= $this->cleanAlias($v);
                break;
            case 'contentType' :
                if ($v !== $this->get('contentType')) {
                    if ($contentType= $this->xpdo->getObject('modContentType', array ('mime_type' => $v))) {
                        if ($contentType->get('mime_type') != $this->get('contentType')) {
                            $this->addOne($contentType, 'ContentType');
                        }
                    }
                }
                break;
            case 'content_type' :
                if ($v !== $this->get('content_type')) {
                    if ($contentType= $this->xpdo->getObject('modContentType', $v)) {
                        if ($contentType->get('mime_type') != $this->get('contentType')) {
                            $this->_fields['contentType']= $contentType->get('mime_type');
                            $this->_dirty['contentType']= 'contentType';
                        }
                    }
                }
                break;
        }
        $rt= parent :: set($k, $v);
        return $rt;
    }

    /**
     * Adds an object related to this modResource by a foreign key relationship.
     *
     * {@inheritdoc}
     *
     * Adds legacy support for keeping the existing contentType field in sync
     * when a modContentType is set using this function.
     */
    function addOne(& $obj, $alias= '') {
        $added= parent :: addOne($obj, $alias);
        if (is_a($obj, 'modContentType') && $alias= 'ContentType') {
            $this->_fields['contentType']= $obj->get('mime_type');
            $this->_dirty['contentType']= 'contentType';
        }
        return $added;
    }

    /**
     * Sanitizes a string to form a valid URL representation.
     *
     * @todo This needs a full code and concept review, as well as
     * regression testing with current 0.9.6.x branch.
     *
     * @param string $alias A string to sanitize.
     * @return string The sanitized string.
     */
    function cleanAlias($alias) {
        if (!isset ($this->xpdo->config['modx_charset']) || strtoupper($this->xpdo->config['modx_charset']) == 'UTF-8') {
            $alias= utf8_decode($alias);
        }
        $alias= strtr($alias, array (chr(196) => 'Ae', chr(214) => 'Oe', chr(220) => 'Ue', chr(228) => 'ae', chr(246) => 'oe', chr(252) => 'ue', chr(223) => 'ss'));

        $alias= strip_tags($alias);
        //$alias = strtolower($alias);
        $alias= preg_replace('/&.+?;/', '', $alias); // kill entities
        $alias= preg_replace('/[^\.%A-Za-z0-9 _-]/', '', $alias);
        $alias= preg_replace('/\s+/', '-', $alias);
        $alias= preg_replace('|-+|', '-', $alias);
        $alias= trim($alias);
        return $alias;
    }

    /**
     * Persist new or changed modResource instances to the database container.
     *
     * {@inheritdoc}
     *
     * If the modResource is new, the createdon and createdby fields will be set
     * using the current time and user authenticated in the context.
     */
    function save($cacheFlag= null) {
        if ($this->_new) {
            if (!$this->get('createdon')) $this->set('createdon', time());
            if (!$this->get('createdby') && is_a($this->xpdo, 'modX')) $this->set('createdby', $this->xpdo->getLoginUserID());
        }
        $rt= parent :: save($cacheFlag);
        return $rt;
    }

	/**
	 * Check to see if a user has access to a modResource instance.
     *
	 * This assumes that the static 'Public' modResourceGroup exists and solves
	 * the 'allow all except deny' issue, turning the system into 'deny all
	 * except allow or administrator'.
     *
     * @deprecated Dec 1, 2007 See checkPolicy() and findPolicy().
     * @param boolean $ar_docgroups
     * @return boolean true if the user has permission to access the resource.
     */
	function hasAccess($ar_docgroups = false) {
		global $e;

		$this->dgds = $this->xpdo->getCollection('modResourceGroupResource',array('document' => $this->id));
		$has_access = false;

		if ($_SESSION['mgrRole'] == 1) { // if administrator, always allow in
			$has_access = true;
		} elseif (is_array($ar_docgroups)) { // if user is in manager
			foreach ($this->dgds as $dgd) { // now loop through doc groups
				$dgd->group = $dgd->getOne('modResourceGroup');
				// if docgroup is in user's userdocgroups, then allow
				// or if the document is in the Public docgroup, then allow
				if (array_search($dgd->document_group,$ar_docgroups) || $dgd->group->name == 'Public')
					$has_access = true;
			}
		}
		if (!$has_access) {
			$e->setError(3);
			$e->dumpError();
			return false;
		}
		return true;
	}

    /**
     * Resolve isfolder for the resource based on if it has children.
     */
    function checkChildren() {
        $kids = $this->getMany('Children');
        $this->set('isfolder',count($kids) > 0);
        $this->save();
    }

    /**
     * Loads the access control policies applicable to this resource.
     *
     * {@inheritdoc}
     */
    function findPolicy($context = '') {
        $policy = array();
        $context = !empty($context) ? $context : $this->xpdo->context->get('key');
        if (empty($this->_policies) || !isset($this->_policies[$context])) {
            $accessTable = $this->xpdo->getTableName('modAccessResourceGroup');
            $policyTable = $this->xpdo->getTableName('modAccessPolicy');
            $resourceGroupTable = $this->xpdo->getTableName('modResourceGroupResource');
            $sql = "SELECT acl.target, acl.principal, acl.authority, acl.policy, p.data FROM {$accessTable} acl " .
                    "LEFT JOIN {$policyTable} p ON p.id = acl.policy " .
                    "JOIN {$resourceGroupTable} rg ON acl.principal_class = 'modUserGroup' " .
                    "AND (acl.context_key = :context OR acl.context_key IS NULL OR acl.context_key = '') " .
                    "AND rg.document = :resource " .
                    "AND rg.document_group = acl.target " .
                    "GROUP BY acl.target, acl.principal, acl.authority, acl.policy";
            $bindings = array(
                ':resource' => $this->get('id'),
                ':context' => $context
            );
            $query = new xPDOCriteria($this->xpdo, $sql, $bindings);
            if ($query->stmt && $query->stmt->execute()) {
                foreach ($query->stmt->fetchAll(PDO_FETCH_ASSOC) as $row) {
                    $policy['modAccessResourceGroup'][$row['target']][$row['principal']] = array(
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
?>