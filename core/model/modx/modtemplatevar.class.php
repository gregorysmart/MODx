<?php
/**
 * Represents a template variable element.
 *
 * @todo Refactor this to allow user-defined and configured input and output
 * widgets.
 * @package modx
 */
class modTemplateVar extends modElement {
    /**
     * @var array Supported bindings for MODx
     * @access public
     */
    public $bindings= array (
        'FILE',
        'CHUNK',
        'DOCUMENT',
        'SELECT',
        'EVAL',
        'INHERIT',
        'DIRECTORY'
    );
    /**
     * @var integer Indicates a value is loaded for a specified resource.
     * @access public
     */
    public $resourceId= 0;

    /**
     * Creates a modTemplateVar instance, and sets the token of the class to *
     *
     * {@inheritdoc}
     */
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
        $this->_token = '*';
    }

    /**
     * Overrides modElement::save to add custom error logging.
     *
     * {@inheritdoc}
     */
    public function save($cacheFlag = null) {
        $isNew = $this->isNew();
        $success = parent::save($cacheFlag);

        if (!$success && !empty($this->xpdo->lexicon)) {
            $msg = $isNew ? $this->xpdo->lexicon('tv_err_create') : $this->xpdo->lexicon('tv_err_save');
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,$msg.$this->toArray());
        }
        return $success;
    }

    /**
     * Overrides modElement::remove to add custom error logging.
     *
     * {@inheritdoc}
     */
    public function remove(array $ancestors= array ()) {
        $success = parent :: remove($ancestors);

        if (!$success && !empty($this->xpdo->lexicon)) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,$this->xpdo->lexicon('tv_err_remove').$this->toArray());
        }

        return $success;
    }

    /**
     * Process the template variable and return the output.
     *
     * {@inheritdoc}
     */
    public function process($properties= null, $content= null) {
        parent :: process($properties, $content);
        if (!$this->_processed) {
            $this->_content= $this->renderOutput($this->xpdo->resourceIdentifier);

            /* copy the content source to the output buffer */
            $this->_output= $this->_content;

            if (is_string($this->_output) && !empty ($this->_output)) {
                /* turn the processed properties into placeholders */
                $restore = $this->toPlaceholders($this->_properties);

                /* collect element tags in the content and process them */
                $maxIterations= intval($this->xpdo->getOption('parser_max_iterations',null,10));
                $this->xpdo->parser->processElementTags($this->_tag, $this->_output, false, false, '[[', ']]', array(), $maxIterations);

                /* remove the placeholders set from the properties of this element and restore global values */
                $this->xpdo->unsetPlaceholders(array_keys($this->_properties));
                if ($restore) $this->xpdo->toPlaceholders($restore);
            }

            /* apply output filtering */
            $this->filterOutput();

            /* cache the content */
            $this->cache();

            $this->_processed= true;
        }
        /* finally, return the processed element content */
        return $this->_output;
    }

    /**
     * Get the source content of this template variable.
     *
     * {@inheritdoc}
     */
    public function getContent(array $options = array()) {
        if (!is_string($this->_content) || $this->_content === '') {
            if (isset($options['content'])) {
                $this->_content = $options['content'];
            } else {
                $this->_content = $this->get('default_text');
            }
        }
        return $this->_content;
    }

    /**
     * Set the source content of this template variable.
     *
     * {@inheritdoc}
     */
    public function setContent($content, array $options = array()) {
        return $this->set('default_text', $content);
    }

    /**
     * Get the value of a template variable for a resource.
     *
     * @access public
     * @param integer $resourceId The id of the resource; 0 defaults to the
     * current resource.
     * @return mixed The raw value of the template variable in context of the
     * specified (or current) resource.
     */
    public function getValue($resourceId= 0) {
        $value= null;
        $resourceId = intval($resourceId);
        if ($resourceId) {
            if ($resourceId === $this->xpdo->resourceIdentifier && isset ($this->xpdo->documentObject[$this->get('name')]) && is_array($this->xpdo->documentObject[$this->get('name')])) {
                $value= $this->xpdo->documentObject[$this->get('name')][1];
            } elseif ($resourceId === $this->get('resourceId') && array_key_exists('value', $this->_fields)) {
                $value= $this->get('value');
            } else {
                $resource = $this->xpdo->getObject('modTemplateVarResource',array(
                    'tmplvarid' => $this->get('id'),
                    'contentid' => $resourceId,
                ),true);
                if ($resource && $resource instanceof modTemplateVarResource) {
                    $value= $resource->get('value');
                    $this->set('resourceId', $resourceId);
                }
            }
        }
        if ($value === null) {
            $value= $this->get('default_text');
        }
        return $value;
    }

    /**
     * Set the value of a template variable for a resource.
     *
     * @access public
     * @param integer $resourceId The id of the resource; 0 defaults to the
     * current resource.
     * @param mixed $value The value to give the template variable for the
     * specified document.
     */
    public function setValue($resourceId= 0, $value= null) {
        $oldValue= '';
        if (intval($resourceId)) {
            $templateVarResource = $this->xpdo->getObject('modTemplateVarResource',array(
                'tmplvarid' => $this->get('id'),
                'contentid' => $resourceId,
            ),true);

            if (!$templateVarResource) {
                $templateVarResource= $this->xpdo->newObject('modTemplateVarResource');
            }

            if ($value !== $this->get('default_text')) {
                if (!$templateVarResource->isNew()) {
                    $templateVarResource->set('value', $value);
                } else {
                    $templateVarResource->set('contentid', $resourceId);
                    $templateVarResource->set('value', $value);
                }
                $this->addMany($templateVarResource);
            } elseif (!$templateVarResource->isNew()
                  && ($value === null || $value === $this->get('default_text'))) {
                $templateVarResource->remove();
            }
        }
    }

    /**
     * Returns the processed output of a template variable.
     *
     * @access public
     * @param integer $resourceId The id of the resource; 0 defaults to the
     * current resource.
     * @return mixed The processed output of the template variable.
     */
    public function renderOutput($resourceId= 0) {
        $value= $this->getValue($resourceId);

        /* process any TV commands in value */
        $value= $this->processBindings($value, $resourceId);

        $param= array ();
        if ($paramstring= $this->get('display_params')) {
            $cp= explode("&", $paramstring);
            foreach ($cp as $p => $v) {
                $v= trim($v);
                $ar= explode("=", $v);
                if (is_array($ar) && count($ar) == 2) {
                    $params[$ar[0]]= $this->decodeParamValue($ar[1]);
                }
            }
        }

        $name= $this->get('name');

        $id= "tv$name";
        $format= $this->get('display');
        $tvtype= $this->get('type');

        $outputRenderPath = $this->xpdo->getOption('processors_path').'element/tv/renders/'.$this->xpdo->context->get('key').'/output/';
        if (!file_exists($outputRenderPath) || !is_dir($outputRenderPath)) {
            $outputRenderPath = $this->xpdo->getOption('processors_path').'element/tv/renders/web/output/';
        }

        $outputRenderFile = $outputRenderPath.$this->get('display').'.php';

        if (!file_exists($outputRenderFile)) {
            $o = include $outputRenderPath.'default.php';
        } else {
            $o = include $outputRenderFile;
        }
        return $o;
    }

    /**
     * Renders input forms for the template variable.
     *
     * @access public
     * @param integer $resourceId The id of the resource; 0 defaults to the
     * current resource.
     * @param string $style Extra style parameters.
     * @return mixed The rendered input for the template variable.
     */
    public function renderInput($resourceId= 0, $style= '') {
        if (!isset($this->smarty)) {
            $this->xpdo->getService('smarty', 'smarty.modSmarty', '', array(
                'template_dir' => $this->xpdo->getOption('manager_path') . 'templates/' . $this->xpdo->getOption('manager_theme',null,'default') . '/',
            ));
        }
        $field_html= '';
        $this->xpdo->smarty->assign('style',$style);
        $value = $this->get('value');

        /* process any TV commands in value */
        $value= $this->processBindings($value, $resourceId);

        if (!$value || $value == '') {
            $this->set('processedValue',$this->getValue($resourceId));
        } else {
            $this->set('processedValue',$value);
        }

        $this->xpdo->smarty->assign('tv',$this);


        $param= array ();
        if ($paramstring= $this->get('display_params')) {
            $cp= explode("&", $paramstring);
            foreach ($cp as $p => $v) {
                $v= trim($v);
                $ar= explode("=", $v);
                if (is_array($ar) && count($ar) == 2) {
                    $params[$ar[0]]= $this->decodeParamValue($ar[1]);
                }
            }
        }

        /* find the correct renderer for the TV, if not one, render a textbox */
        $inputRenderPath = $this->xpdo->getOption('processors_path').'element/tv/renders/'.$this->xpdo->context->get('key').'/input/';
        if (!file_exists($inputRenderPath) || !is_dir($inputRenderPath)) {
            $outputRenderPath = $this->xpdo->getOption('processors_path').'element/tv/renders/web/input/';
        }

        $inputRenderFile = $inputRenderPath.$this->get('type').'.php';
        if (!file_exists($inputRenderFile)) {
            $field_html .= include $inputRenderPath.'textbox.php';
        } else {
            $field_html .= include $inputRenderFile;
        }

        return $field_html;
    }

    /**
     * Decodes special function-based chars from a parameter value.
     *
     * @access public
     * @param string $s The string to decode.
     * @return string The decoded string.
     */
    public function decodeParamValue($s) {
        $s= str_replace("%3D", '=', $s);
        $s= str_replace("%26", '&', $s);
        return $s;
    }

    /**
     * Returns an string if a delimiter is present. Returns array if is a recordset is present.
     *
     * @access public
     * @param mixed $src Source object, either a recordset, PDOStatement, array or string.
     * @param string $delim Delimiter for string parsing.
     * @param string $type Type to return, either 'string' or 'array'.
     *
     * @return string|array If delimiter present, returns string, otherwise array.
     */
    public function parseInput($src, $delim= "||", $type= "string") {
        if (is_resource($src)) {
            /* must be a recordset */
            $rows= array ();
            while ($cols= mysql_fetch_row($src))
                $rows[]= ($type == "array") ? $cols : implode(" ", $cols);
            return ($type == "array") ? $rows : implode($delim, $rows);
        } elseif (is_object($src)) {
            $rs= $src->fetchAll(PDO::FETCH_ASSOC);
            if ($type != "array") {
                foreach ($rs as $row) {
                    $rows[]= implode(" ", $row);
                }
            } else {
                $rows= $rs;
            }
            return ($type == "array" ? $rows : implode($delim, $rows));
        } elseif (is_array($src) && $type == "array") {
            return ($type == "array" ? $src : implode($delim, $src));
        } else {
            /* must be a text */
            if ($type == "array")
                return explode($delim, $src);
            else
                return $src;
        }
    }

    /**
     * Parses input options sent through postback.
     *
     * @access public
     * @param mixed $v The options to parse, either a recordset, PDOStatement, array or string.
     * @return mixed The parsed options.
     */
    public function parseInputOptions($v) {
        $a = array();
        if(is_array($v)) return $v;
        else if(is_resource($v)) {
            while ($cols = mysql_fetch_row($v)) $a[] = $cols;
        } else if (is_object($v)) {
            $a = $v->fetchAll(PDO::FETCH_ASSOC);
        }
        else $a = explode("||", $v);
        return $a;
    }

    /**
     * Process bindings assigned to a template variable.
     *
     * @access public
     * @param string $value The value specified from the binding.
     * @param integer $resourceId The resource in which the TV is assigned.
     * @return string The processed value.
     */
    public function processBindings($value= '', $resourceId= 0) {
        $modx =& $this->xpdo;
        $nvalue= trim($value);
        if (substr($nvalue,0,1)!='@') return $value;
        else {
            list($cmd,$param) = $this->parseBinding($nvalue);
            $cmd = trim($cmd);
            switch ($cmd) {
                case 'FILE':
                    $output = $this->processFileBinding($param);
                    break;

                case 'CHUNK':       /* retrieve a chunk and process it's content */
                    $output = $this->xpdo->getChunk($param);
                    break;

                case 'RESOURCE':
                case 'DOCUMENT':    /* retrieve a document and process it's content */
                    $rs = $this->xpdo->getDocument($param);
                    if (is_array($rs)) $output = $rs['content'];
                    else $output = 'Unable to locate resource '.$param;
                    break;

                case 'SELECT': /* selects a record from the cms database */
                    $dbtags = array();
                    $dbtags['DBASE'] = $this->xpdo->db->config['dbase'];
                    $dbtags['PREFIX'] = $this->xpdo->db->config['table_prefix'];
                    foreach($dbtags as $key => $pValue) {
                        $param = str_replace('[[+'.$key.']]', $pValue, $param);
                    }
                    $stmt = $this->xpdo->query('SELECT '.$param);
                    if ($stmt && $stmt instanceof PDOStatement) {
                        $data = '';
                        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                            $col = '';
                            if (isset($row[1])) {
                                $col = $row[0].'=='.$row[1];
                            } else {
                                $col = $row[0];
                            }
                            $data .= (!empty($data) ? '||' : '').$col;
                        }
                        $stmt->closeCursor();
                    }
                    $output = $data;
                    break;

                case 'EVAL':        /* evaluates text as php codes return the results */
                    $output = eval($param);
                    break;

                case 'INHERIT':
                    $output = $param; /* Default to param value if no content from parents */
                    $resource = null;
                    if ($resourceId && (!($this->xpdo->resource instanceof modResource) || $this->xpdo->resource->get('id') != $resourceId)) {
                        $resource = $this->xpdo->getObject('modResource',$resourceId);
                    } else if ($this->xpdo->resource instanceof modResource) {
                        $resource =& $this->xpdo->resource;
                    }
                    if (!$resource) break;
                    $doc = array('id' => $resource->get('id'), 'parent' => $resource->get('parent'));

                    while($doc['parent'] != 0) {
                        $parent_id = $doc['parent'];
                        if(!$doc = $this->xpdo->getDocument($parent_id, 'id,parent')) {
                            /* Get unpublished document */
                            $doc = $this->xpdo->getDocument($parent_id, 'id,parent',0);
                        }
                        if ($doc) {
                            $tv = $this->xpdo->getTemplateVar($this->get('name'), '*', $doc['id']);
                            if(isset($tv['value']) && $tv['value'] && substr($tv['value'],0,1) != '@') {
                                $output = $tv['value'];
                                break 2;
                            }
                        } else {
                            break;
                        }
                    }
                    break;

                case 'DIRECTORY':
                    $path = $this->xpdo->getOption('base_path').$param;
                    if (substr($path,-1,1) != '/') { $path .= '/'; }
                    if (!is_dir($path)) { break; }

                    $files = array();
                    $invalid = array('.','..','.svn','.DS_Store');
                    foreach (new DirectoryIterator($path) as $file) {
                        if (!$file->isReadable()) continue;
                        $basename = $file->getFilename();
                        if(!in_array($basename,$invalid)) {
                            $files[] = "{$basename}=={$param}{$basename}";
                        }
                    }
                    asort($files);
                    $output = implode('||',$files);
                    break;

                default:
                    $output = $value;
                    break;

            }
            /* support for nested bindings */
            return is_string($output) && ($output!=$value) ? $this->processBindings($output) : $output;
        }
    }

    /**
     * Parses bindings to an appropriate format.
     *
     * @access public
     * @param string $binding_string The binding to parse.
     * @return array The parsed binding, now in array format.
     */
    public function parseBinding($binding_string) {
        $match= array ();
        $binding_string= trim($binding_string);
        $regexp= '/@(' . implode('|', $this->bindings) . ')\s*(.*)/is'; /* Split binding on whitespace */
        if (preg_match($regexp, $binding_string, $match)) {
            /* We can't return the match array directly because the first element is the whole string */
            $binding_array= array (
                strtoupper($match[1]),
                trim($match[2])
            ); /* Make command uppercase */
            return $binding_array;
        }
    }

    /**
     * Special parsing for file bindings.
     *
     * @access public
     * @param string $file The absolute location of the file in the binding.
     * @return string The file buffer from the read file.
     */
    public function processFileBinding($file) {
        if (file_exists($file) && @ $handle= fopen($file,'r')) {
            $buffer= "";
            while (!feof($handle)) {
                $buffer .= fgets($handle, 4096);
            }
            fclose($handle);
        } else {
            $buffer= " Could not retrieve document '$file'.";
        }
        return $buffer;
    }

    /**
     * Loads the access control policies applicable to this template variable.
     *
     * {@inheritdoc}
     */
    public function findPolicy($context = '') {
        $policy = array();
        $context = !empty($context) ? $context : $this->xpdo->context->get('key');
        if (empty($this->_policies) || !isset($this->_policies[$context])) {
            $accessTable = $this->xpdo->getTableName('modAccessResourceGroup');
            $policyTable = $this->xpdo->getTableName('modAccessPolicy');
            $resourceGroupTable = $this->xpdo->getTableName('modTemplateVarResourceGroup');
            $sql = "SELECT acl.target, acl.principal, acl.authority, acl.policy, p.data FROM {$accessTable} acl " .
                    "LEFT JOIN {$policyTable} p ON p.id = acl.policy " .
                    "JOIN {$resourceGroupTable} rg ON acl.principal_class = 'modUserGroup' " .
                    "AND (acl.context_key = :context OR acl.context_key IS NULL OR acl.context_key = '') " .
                    "AND rg.tmplvarid = :element " .
                    "AND rg.documentgroup = acl.target " .
                    "GROUP BY acl.target, acl.principal, acl.authority, acl.policy";
            $bindings = array(
                ':element' => $this->get('id'),
                ':context' => $context
            );
            $query = new xPDOCriteria($this->xpdo, $sql, $bindings);
            if ($query->stmt && $query->stmt->execute()) {
                while ($row = $query->stmt->fetch(PDO::FETCH_ASSOC)) {
                    $policy['modAccessResourceGroup'][$row['target']][] = array(
                        'principal' => $row['principal'],
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