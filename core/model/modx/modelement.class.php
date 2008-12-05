<?php
/**
 * Represents an element of source content managed by MODx.
 *
 * These elements are defined by some type of source content that when processed
 * will provide output or some type of logical result based on mutable
 * properties.
 *
 * @package modx
 * @abstract Implement a derivative of this class to represent an element which
 * can be processed within the MODx framework.
 */
class modElement extends modAccessibleSimpleObject {
    /**
     * The property value array for the element.
     * @var array
     */
    var $_properties= null;
    /**
     * The string representation of the element properties.
     * @var string
     */
    var $_propertyString= '';
    /**
     * The source content of the element.
     * @var string
     */
    var $_content= '';
    /**
     * The output of the element.
     * @var string
     */
    var $_output= '';
    /**
     * The boolean result of the element.
     *
     * This is typically only applicable to elements that use PHP source content.
     * @var boolean
     */
    var $_result= true;
    /**
     * The tag signature of the element instance.
     */
    var $_tag= null;
    /**
     * The character token which helps identify the element class in tag string.
     * @var string
     */
    var $_token= '';
    /**
     * @var boolean If the element is cacheable or not.
     */
    var $_cacheable= true;
    /**
     * @var boolean Indicates if the element was processed already.
     */
    var $_processed= false;
    /**
     * @var array Optional filters that can be used during processing.
     */
    var $_filters= array ();

    function modElement(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }

    function get($k, $format= null, $formatTemplate= null) {
        $value = parent :: get($k, $format, $formatTemplate);
        if ($k === 'properties' && is_a($this->xpdo, 'modX') && $this->xpdo->getParser() && empty($value)) {
            $value = !empty($this->properties) && is_string($this->properties)
                ? $this->xpdo->parser->parsePropertyString($this->properties)
                : null;
        }
        return $value;
    }

    function remove($ancestors= array ()) {
        $this->xpdo->removeCollection('modElementPropertySet', array('element' => $this->get('id'), 'element_class' => $this->_class));
        $result = parent :: remove($ancestors);
        return $result;
    }

    /**
     * Constructs a valid tag representation of the element.
     * 
     * @return string A tag representation of the element.
     */
    function getTag() {
        if (empty($this->_tag)) {
            $propTemp = array();
            if (empty($this->_propertyString) && !empty($this->_properties)) {
                while(list($key, $value) = each($this->_properties)) {
                    if (is_scalar($value)) {
                        $propTemp[] = trim($key) . '=`' . $value . '`';
                    }
                    else {
                        $propTemp[] = trim($key) . '=`' . md5(uniqid(rand(), true)) . '`';
                    }
                }
                if (!empty($propTemp)) {
                    $this->_propertyString = '?' . implode('&', $propTemp);
                }
            }
            $tag = '[[';
            $tag.= $this->_token;
            $tag.= $this->get('name');
            if (!empty($this->_propertyString)) {
                $tag.= $this->_propertyString;
            }
            $tag.= ']]';
            $this->_tag = $tag;
        }
        if (empty($this->_tag)) {
            $this->xpdo->log(XPDO_LOG_LEVEL_ERROR, 'Instance of ' . get_class($this) . ' produced an empty tag!');
        }
        return $this->_tag;
    }

    /**
     * Process the element source content to produce a result.
     *
     * @abstract Implement this to define behavior for a MODx content element.
     * @param array|string $properties A set of configuration properties for the
     * element.
     * @param string $content Optional content to use in place of any persistent
     * content associated with the element.
     * @return mixed The result of processing.
     */
    function process($properties= null, $content= null) {
        $this->xpdo->getParser();
        $this->getProperties($properties);
        $this->getTag();
        if ($this->xpdo->getDebug() === true) $this->xpdo->log(XPDO_LOG_LEVEL_DEBUG, "Processing Element: " . $this->get('name') . ($this->_tag ? "\nTag: {$this->_tag}" : "\n") . "\nProperties: " . print_r($this->_properties, true));
        if ($this->isCacheable() && isset ($this->xpdo->elementCache[$this->_tag])) {
            $this->_output = $this->xpdo->elementCache[$this->_tag];
            $this->_processed = true;
        } else {
	        $this->filterInput();
            $this->getContent(is_string($content) ? array('content' => $content) : array());
        }
        return $this->_result;
    }

    /**
     * Cache the current output of this element instance by tag signature.
     */
    function cache() {
        if ($this->isCacheable()) {
            $this->xpdo->elementCache[$this->_tag]= $this->_output;
        }
    }

    /**
     * Apply an input filter to an element.
     *
     * This is called by default in {@link modElement::process()} after the
     * element properties have been parsed.
     */
    function filterInput() {
        $filter= null;
        if (!isset ($this->_filters['input']) || !is_a($this->_filters['input'], 'modInputFilter')) {
            if (!$inputFilterClass= $this->get('input_filter')) {
                if (!isset($this->xpdo->config['input_filter']) || !$inputFilterClass= $this->xpdo->config['input_filter']) {
                    $inputFilterClass= 'filters.modInputFilter';
                }
            }
            if ($filterClass= $this->xpdo->loadClass($inputFilterClass, '', false, true)) {
                if ($filter= new $filterClass($this->xpdo)) {
                    $this->_filters['input']= $filter;
                }
            }
        }
        if (isset ($this->_filters['input']) && is_a($this->_filters['input'], 'modInputFilter')) {
            $this->_filters['input']->filter($this);
        }
    }

    /**
     * Apply an output filter to an element.
     *
     * Call this method in your {modElement::process()} implementation when it
     * is appropriate, typically once all processing has been completed, but
     * before any caching takes place.
     */
    function filterOutput() {
        $filter= null;
        if (!isset ($this->_filters['output']) || is_a($this->_filters['output'], 'modOutputFilter')) {
            if (!$outputFilterClass= $this->get('output_filter')) {
                if (!isset($this->xpdo->config['output_filter']) || !$outputFilterClass= $this->xpdo->config['output_filter']) {
                    $outputFilterClass= 'filters.modOutputFilter';
                }
            }
            if ($filterClass= $this->xpdo->loadClass($outputFilterClass, '', false, true)) {
                if ($filter= new $filterClass($this->xpdo)) {
                    $this->_filters['output']= $filter;
                }
            }
        }
        if (isset ($this->_filters['output']) && is_a($this->_filters['output'], 'modOutputFilter')) {
            $this->_filters['output']->filter($this);
        }
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
            $accessTable = $this->xpdo->getTableName('modAccessElement');
            $policyTable = $this->xpdo->getTableName('modAccessPolicy');
            $sql = "SELECT acl.target, acl.principal, acl.authority, acl.policy, p.data FROM {$accessTable} acl " .
                    "LEFT JOIN {$policyTable} p ON p.id = acl.policy " .
                    "ON acl.principal_class = 'modUserGroup' " .
                    "AND (acl.context_key = :context OR acl.context_key IS NULL OR acl.context_key = '') " .
                    "AND acl.target = :element " .
                    "GROUP BY acl.target, acl.principal, acl.authority, acl.policy";
            $bindings = array(
                ':element' => $this->get('id'),
                ':context' => $context
            );
            $query = new xPDOCriteria($this->xpdo, $sql, $bindings);
            if ($query->stmt && $query->stmt->execute()) {
                while ($row = $query->stmt->fetch(PDO_FETCH_ASSOC)) {
                    $policy['modAccessElement'][$row['target']][$row['principal']] = array(
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

    /**
     * Gets the raw, unprocessed source content for this element.
     * 
     * @param array $options An array of options implementations can use to
     * accept language, revision identifiers, or other information to alter the
     * behavior of the method.
     * @return string The raw source content for the element.
     */
    function getContent($options = array()) {
        if (!is_string($this->_content) || $this->_content === '') {
            if (isset($options['content'])) {
                $this->_content = $options['content'];
            } else {
                $this->_content = $this->get('content');
            }
        }
        return $this->_content;
    }
    
    /**
     * Set the raw source content for this element.
     * 
     * @param mixed $content The source content; implementations can decide if
     * it can only be a string, or some other source from which to retrieve it.
     * @param array $options An array of options implementations can use to
     * accept language, revision identifiers, or other information to alter the
     * behavior of the method.
     * @return boolean True indicates the content was set.
     */
    function setContent($content, $options = array()) {
        return $this->set('content', $content);
    }
    
    /**
     * Get the properties for this element instance for processing.
     * 
     * @param array|string $properties An array or string of properties to
     * apply.
     * @return array A simple array of properties ready to use for processing.
     */
    function getProperties($properties = null) {
        $this->xpdo->getParser();
        $this->_properties= $this->xpdo->parser->parseProperties($this->get('properties'));
        $set= $this->getPropertySet();
        if (!empty($set)) {
            $this->_properties= array_merge($this->_properties, $set);
        }
        if (!empty($properties)) {
            $this->_properties= array_merge($this->_properties, $this->xpdo->parser->parseProperties($properties));
        }
        return $this->_properties;
    }

    /**
     * Gets a named property set related to this element instance.
     * 
     * If a setName parameter is not provided, this function will attempt to
     * extract a setName from the element name using the @ symbol to delimit the
     * name of the property set.
     * 
     * Here is an example of an element tag using the @ modifier to specify a
     * property set name:
     *  [[ElementName@PropertySetName:FilterCommand=`FilterModifier`?
     *      &PropertyKey1=`PropertyValue1`
     *      &PropertyKey2=`PropertyValue2`
     *  ]]
     * 
     * @param string|null $setName An explicit property set name to search for.
     * @return array|null An array of properties or null if no set is found.
     */
    function getPropertySet($setName = null) {
        $propertySet= null;
        if ($setName === null) {
            $name = $this->get('name');
            $split= xPDO :: escSplit('@', $name);
            if ($split && isset($split[1])) {
                $name= $split[0];
                $setName= $split[1];
                $filters= xPDO :: escSplit(':', $setName);
                if ($filters && isset($filters[1]) && !empty($filters[1])) {
                    $setName= $filters[0];
                    $name.= ':' . $filters[1];
                }
                $this->set('name', $name);
            }
        }
        if (!empty($setName)) {
            $propertySetObj= $this->xpdo->getObjectGraph('modPropertySet', '{"Elements":{}}', array(
                'Elements.element' => $this->id,
                'Elements.element_class' => $this->_class,
                'modPropertySet.name' => $setName
            ));
            if ($propertySetObj) {
                $propertySet= $this->xpdo->parser->parseProperties($propertySetObj->get('properties'));
            }
        }
        return $propertySet;
    }

    /**
     * Set default properties for this element instance.
     * 
     * @param array|string $properties A property array or property string.
     * @param boolean $merge Indicates if properties should be merged with
     * existing ones.
     * @return boolean true if the properties are set.
     */
    function setProperties($properties, $merge = false) {
        $set = false;
        $propertyArray = array();
        if (is_string($properties)) {
            $properties = $this->xpdo->parser->parsePropertyString($properties);
        }
        if (is_array($properties)) {
            foreach ($properties as $propKey => $property) {
                if (is_array($property) && isset($property[5])) {
                    $propertyArray[$property[0]] = array(
                        'name' => $property[0],
                        'desc' => $property[1],
                        'type' => $property[2],
                        'options' => $property[3],
                        'value' => $property[4],
                    );
                } elseif (is_array($property) && isset($property['value'])) {
                    $propertyArray[$property['name']] = array(
                        'name' => $property['name'],
                        'desc' => isset($property['description']) ? $property['description'] : (isset($property['desc']) ? $property['desc'] : ''),
                        'type' => isset($property['xtype']) ? $property['xtype'] : (isset($property['type']) ? $property['type'] : 'textfield'),
                        'options' => isset($property['options']) ? $property['options'] : array(),
                        'value' => $property['value'],
                    );
                } else {
                    $propertyArray[$propKey] = array(
                        'name' => $propKey,
                        'desc' => '',
                        'type' => 'textfield',
                        'options' => array(), 
                        'value' => $property
                    );
                }
            }
            if ($merge && !empty($propertyArray)) {
                $existing = $this->get('properties');
                if (is_array($existing) && !empty($existing)) {
                    $propertyArray = array_merge($existing, $propertyArray);
                }
            }
            $set = $this->set('properties', $propertyArray);
        }
        return $set;
    }

    /**
     * Add a property set to this element, making it available for use.
     * 
     * @param string|modPropertySet $propertySet A modPropertySet object or the
     * name of a modPropertySet object to create a relationship with.
     * @return boolean True if a relationship was created or already exists.
     */
    function addPropertySet($propertySet) {
        $added= false;
        if (!empty($propertySet)) {
            if (is_string($propertySet)) {
                $propertySet = $this->xpdo->getObject('modPropertySet', array('name' => $propertySet));
            }
            if (is_object($propertySet) && is_a($propertySet, 'modPropertySet')) {
                if (!$this->isNew() && !$propertySet->isNew() && $this->xpdo->getCount('modElementPropertySet', array('element' => $this->get('id'), 'element_class' => $this->_class, 'property_set' => $propertySet->get('id')))) {
                    $added = true;
                } else {
                    $link= $this->xpdo->newObject('modElementPropertySet');
                    $link->set('element', $this->get('id'));
                    $link->set('element_class', $this->_class);
                    $link->addOne($propertySet);
                    $added = $link->save();
                }
            }
        }
        return $added;
    }

    /**
     * Remove a property set from this element, making it unavailable for use.
     * 
     * @param string|modPropertySet $propertySet A modPropertySet object or the
     * name of a modPropertySet object to dissociate from.
     * @return boolean True if a relationship was destroyed.
     */
    function removePropertySet($propertySet) {
        $removed = false;
        if (!empty($propertySet)) {
            if (is_string($propertySet)) {
                $propertySet = $this->xpdo->getObject('modPropertySet', array('name' => $propertySet));
            }
            if (is_object($propertySet) && is_a($propertySet, 'modPropertySet')) {
                $removed = $this->xpdo->removeObject('modElementPropertySet', array('element' => $this->get('id'), 'element_class' => $this->_class, 'property_set' => $propertySet->get('id')));
            }
        }
        return $removed;
    }

    /**
     * Indicates if the element is cacheable.
     * 
     * @return boolean True if the element can be stored to or retrieved from
     * the element cache.
     */
    function isCacheable() {
        return $this->_cacheable;
    }
    
    /**
     * Sets the runtime cacheability of the element.
     * 
     * @param boolean $cacheable Indicates the value to set for cacheability of
     * this element.
     */
    function setCacheable($cacheable = true) {
        $this->_cacheable = (boolean) $cacheable;
    }

    /**
     * Turns associative arrays into placeholders in the scope of this element.
     * 
     * @param array $placeholders An associative array of placeholders to set.
     * @return array An array of placeholders overwritten from the containing
     * scope you can use to restore values from, or an empty array if no
     * placeholders were overwritten.
     */
    function toPlaceholders($placeholders) {
        $restore = array();
        if (is_array($placeholders) && !empty($placeholders)) {
            $restoreKeys = array_keys($placeholders);
            foreach ($restoreKeys as $phKey) {
                if (isset($this->xpdo->placeholders[$phKey])) $restore[$phKey]= $this->xpdo->getPlaceholder($phKey);
            }
            $this->xpdo->toPlaceholders($placeholders);
        }
        return $restore;
    }
}
