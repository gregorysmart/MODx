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

    function getTag() {
        if (empty($this->_tag)) {
            $propTemp = array();
            if (empty($this->_propertyString) && !empty($this->_properties)) {
                while(list($key, $value) = each($this->_properties)) {
                    if (is_object($value) || is_array($value)) {
                        $propTemp[] = trim($key) . '=`' . md5(uniqid(rand(), true)) . '`';
                    }
                    elseif (is_scalar($value)) {
                        $propTemp[] = trim($key) . '=`' . $value . '`';
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
        if ($properties !== null && !empty ($properties)) {
            if (is_string($properties)) {
                $this->_properties= array_merge($this->xpdo->parser->parseProperties($this->get('properties')), $this->xpdo->parser->parseProperties($properties));
            }
            elseif (is_array($properties)) {
                $this->_properties= array_merge($this->xpdo->parser->parseProperties($this->get('properties')), $properties);
            }
        } else {
            $this->_properties= $this->xpdo->parser->parseProperties($this->get('properties'));
        }
        $this->getTag();
        $this->filterInput();
        if (is_string($content) && !empty($content)) {
            $this->_content= $content;
        }
        if ($this->xpdo->getDebug() === true) $this->xpdo->log(XPDO_LOG_LEVEL_DEBUG, "Processing Element: " . $this->get('name') . ($this->_tag ? "\nTag: {$this->_tag}" : "\n") . "\nProperties: " . print_r($this->_properties, true));
        return $this->_result;
    }

    /**
     * Cache the current output of this element instance by tag signature.
     */
    function cache() {
        if ($this->_cacheable) {
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
                foreach ($query->stmt->fetchAll(PDO_FETCH_ASSOC) as $row) {
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
}
?>