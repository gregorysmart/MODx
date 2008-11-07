<?php
/**
 * Wayfinder Class
 *
 * @package wayfinder
 */
class Wayfinder {
    var $_config;
    var $_templates;
    var $_css;
    var $modx;
    var $docs = array ();
    var $parentTree = array ();
    var $hasChildren = array ();
    var $placeHolders = array (
        'rowLevel' => array (
            '[[+wf.wrapper]]',
            '[[+wf.classes]]',
            '[[+wf.classnames]]',
            '[[+wf.link]]',
            '[[+wf.title]]',
            '[[+wf.linktext]]',
            '[[+wf.id]]',
            '[[+wf.attributes]]',
            '[[+wf.docid]]',
            '[[+wf.introtext]]',
            '[[+wf.description]]',
            '[[+wf.subitemcount]]'
        ),
        'wrapperLevel' => array (
            '[[+wf.wrapper]]',
            '[[+wf.classes]]',
            '[[+wf.classnames]]'
        ),
        'tvs' => array (),

    );
    var $tvList = array ();
    var $debugInfo = array ();

    function __construct(&$modx) {
        $this->modx = $modx;
    }

    function Wayfinder(&$modx) {
        $this->__construct($modx);
    }

    /**
     * Main entry point to generate the menu
     *
     * @return string The menu HTML or relevant error message.
     */
    function run() {
        // setup here checking array
        $this->parentTree = $this->modx->getParentIds($this->modx->resource->id);
        $this->parentTree[] = $this->modx->resource->id;

        if ($this->_config['debug']) {
            $this->addDebugInfo("settings", "Settings", "Settings", "Settings used to create this menu.", $this->_config);
            $this->addDebugInfo("settings", "CSS", "CSS Settings", "Available CSS options.", $this->_css);
        }
        // load the templates
        $this->checkTemplates();

        // register any scripts
        if ($this->_config['cssTpl'] || $this->_config['jsTpl']) {
            $this->regJsCss();
        }
        // get all of the documents
        $this->docs = $this->getData();
        if (!empty ($this->docs)) {
            // sort documents by level for proper wrapper substitution
            ksort($this->docs);
            // build the menu
            return $this->buildMenu();
        } else {
            $noneReturn = $this->_config['debug'] ? '<p style="color:red">No documents found for menu.</p>' : '';
            return $noneReturn;
        }
    }

    /**
     * Constructs the menu HTML by looping through the document array
     *
     * @return string The HTML for the menu
     */
    function buildMenu() {
        // loop through all of the menu levels
        foreach ($this->docs as $level => $subDocs) {
            // loop through each document group (grouped by parent doc)
            foreach ($subDocs as $parentId => $docs) {
                // only process document group, if starting at root, hidesubmenus is off, or is in current parenttree
                if (!$this->_config['hideSubMenus'] || $this->isHere($parentId) || $parentId == 0) {
                    // build the output for the group of documents
                    $menuPart = $this->buildSubMenu($docs,$level);
                    // if at the top of the menu start the output, otherwise replace the wrapper with the submenu
                    if (($level == 1 && (!$this->_config['displayStart'] || $this->_config['id'] == 0)) || ($level == 0 && $this->_config['displayStart'])) {
                        $output = $menuPart;
                    } else {
                        $output = str_replace("[[+wf.wrapper.{$parentId}]]",$menuPart,$output);
                    }
                }
            }
        }
        return $output;
    }

    /**
     * Constructs a sub menu for the menu
     *
     * @param array $menuDocs Array of documents for the menu
     * @param int $level The heirarchy level of the sub menu to be rendered
     * @return string The submenu HTML
     */
    function buildSubMenu($menuDocs,$level) {
        $subMenuOutput = '';
        $firstItem = 1;
        $counter = 1;
        $numSubItems = count($menuDocs);
        // loop through each document to render output
        foreach ($menuDocs as $docId => $docInfo) {
            $docInfo['level'] = $level;
            $docInfo['first'] = $firstItem;
            $firstItem = 0;
            // determine if last item in group
            if ($counter == ($numSubItems) && $numSubItems > 1) {
                $docInfo['last'] = 1;
            } else {
                $docInfo['last'] = 0;
            }
            // determine if document has children
            $docInfo['hasChildren'] = in_array($docInfo['id'],$this->hasChildren) ? 1 : 0;
            $numChildren = $docInfo['hasChildren'] ? count($this->docs[$level+1][$docInfo['id']]) : 0;
            // render the row output
            $subMenuOutput .= $this->renderRow($docInfo,$numChildren);
            // update counter for last check
            $counter++;
        }

        if ($level > 0) {
            // determine which wrapper template to use
            if ($this->_templates['innerTpl'] && $level > 1) {
                $useChunk = $this->_templates['innerTpl'];
                $usedTemplate = 'innerTpl';
            } else {
                $useChunk = $this->_templates['outerTpl'];
                $usedTemplate = 'outerTpl';
            }
            // determine wrapper class
            if ($level > 1) {
                $wrapperClass = 'innercls';
            } else {
                $wrapperClass = 'outercls';
            }
            // get the class names for the wrapper
            $classNames = $this->setItemClass($wrapperClass);
            if ($classNames) $useClass = ' class="' . $classNames . '"';
            $phArray = array($subMenuOutput,$useClass,$classNames);
            // process the wrapper
            $subMenuOutput = str_replace($this->placeHolders['wrapperLevel'],$phArray,$useChunk);
            // debug
            if ($this->_config['debug']) {
                $debugParent = $docInfo['parent'];
                $debugDocInfo = array();
                $debugDocInfo['template'] = $usedTemplate;
                foreach ($this->placeHolders['wrapperLevel'] as $n => $v) {
                    if ($v !== '[[+wf.wrapper]]')
                        $debugDocInfo[$v] = $phArray[$n];
                }
                $this->addDebugInfo("wrapper","{$debugParent}","Wrapper for items with parent {$debugParent}.","These fields were used when processing the wrapper for the following documents.",$debugDocInfo);
            }
        }
        return $subMenuOutput;
    }

    /**
     * Renders a row item for the menu
     *
     * @param array $resource An array containing the document information for the row
     * @param int $numChildren The number of children that the document contains
     * @return string The HTML for the row item
     */
    function renderRow(&$resource,$numChildren) {
        $output = '';
        // determine which template to use
        if ($this->_config['displayStart'] && $resource['level'] == 0) {
            $usedTemplate = 'startItemTpl';
        } elseif ($resource['id'] == $this->modx->resource->id && $resource['isfolder'] && $this->_templates['parentRowHereTpl'] && ($resource['level'] < $this->_config['level'] || $this->_config['level'] == 0) && $numChildren) {
            $usedTemplate = 'parentRowHereTpl';
        } elseif ($resource['id'] == $this->modx->resource->id && $this->_templates['innerHereTpl'] && $resource['level'] > 1) {
            $usedTemplate = 'innerHereTpl';
        } elseif ($resource['id'] == $this->modx->resource->id && $this->_templates['hereTpl']) {
            $usedTemplate = 'hereTpl';
        } elseif ($resource['isfolder'] && $this->_templates['activeParentRowTpl'] && ($resource['level'] < $this->_config['level'] || $this->_config['level'] == 0) && $this->isHere($resource['id'])) {
            $usedTemplate = 'activeParentRowTpl';
        } elseif ($resource['isfolder'] && ($resource['template']=="0" || is_numeric(strpos($resource['link_attributes'],'rel="category"'))) && $this->_templates['categoryFoldersTpl'] && ($resource['level'] < $this->_config['level'] || $this->_config['level'] == 0)) {
            $usedTemplate = 'categoryFoldersTpl';
        } elseif ($resource['isfolder'] && $this->_templates['parentRowTpl'] && ($resource['level'] < $this->_config['level'] || $this->_config['level'] == 0) && $numChildren) {
            $usedTemplate = 'parentRowTpl';
        } elseif ($resource['level'] > 1 && $this->_templates['innerRowTpl']) {
            $usedTemplate = 'innerRowTpl';
        } else {
            $usedTemplate = 'rowTpl';
        }
        // get the template
        $useChunk = $this->_templates[$usedTemplate];
        // setup the new wrapper name and get the class names
        $useSub = $resource['hasChildren'] ? "[[+wf.wrapper.{$resource['id']}]]" : "";
        $classNames = $this->setItemClass('rowcls',$resource['id'],$resource['first'],$resource['last'],$resource['level'],$resource['isfolder'],$resource['type']);
        if ($classNames) $useClass = ' class="' . $classNames . '"';
        // setup the row id if a prefix is specified
        if ($this->_config['rowIdPrefix']) {
            $useId = ' id="' . $this->_config['rowIdPrefix'] . $resource['id'] . '"';
        } else {
            $useId = '';
        }
        // load row values into placholder array
        $phArray = array($useSub,$useClass,$classNames,$resource['link'],$resource['title'],$resource['linktext'],$useId,$resource['link_attributes'],$resource['id'],$resource['introtext'],$resource['description'],$numChildren);
        // if TVs are used add them to the placeholder array
        if (!empty($this->tvList)) {
            $usePlaceholders = array_merge($this->placeHolders['rowLevel'],$this->placeHolders['tvs']);
            foreach ($this->tvList as $tvName) {
                $phArray[] = $resource[$tvName];
            }
        } else {
            $usePlaceholders = $this->placeHolders['rowLevel'];
        }
        // debug
        if ($this->_config['debug']) {
            $debugDocInfo = array();
            $debugDocInfo['template'] = $usedTemplate;
            foreach ($usePlaceholders as $n => $v) {
                $debugDocInfo[$v] = $phArray[$n];
            }
            $this->addDebugInfo("row","{$resource['parent']}:{$resource['id']}","Doc: #{$resource['id']}","The following fields were used when processing this document.",$debugDocInfo);
            $this->addDebugInfo("rowdata","{$resource['parent']}:{$resource['id']}","Doc: #{$resource['id']}","The following fields were retrieved from the database for this document.",$resource);
        }
        // process the row
        $output .= str_replace($usePlaceholders,$phArray,$useChunk);
        // return the row
        return $output . $this->_config['nl'];
    }

    /**
     * Determine style class for current item being processed
     *
     * @param string $classType The type of class to be returned
     * @param int $docId The document ID of the item being processed
     * @param int $first Integer representing if the item is the first item (0 or 1)
     * @param int $last Integer representing if the item is the last item (0 or 1)
     * @param int $level The heirarchy level of the item being processed
     * @param int $isFolder Integer representing if the item is a container (0 or 1)
     * @param string $type Resource type of the item being processed
     * @return string The class string to use
     */
    function setItemClass($classType, $docId = 0, $first = 0, $last = 0, $level = 0, $isFolder = 0, $type = 'document') {
        $returnClass = '';
        $hasClass = 0;

        if ($classType === 'outercls' && !empty($this->_css['outer'])) {
            // set outer class if specified
            $returnClass .= $this->_css['outer'];
            $hasClass = 1;
        } elseif ($classType === 'innercls' && !empty($this->_css['inner'])) {
            // set inner class if specified
            $returnClass .= $this->_css['inner'];
            $hasClass = 1;
        } elseif ($classType === 'rowcls') {
            // set row class if specified
            if (!empty($this->_css['row'])) {
                $returnClass .= $this->_css['row'];
                $hasClass = 1;
            }
            // set first class if specified
            if ($first && !empty($this->_css['first'])) {
                $returnClass .= $hasClass ? ' ' . $this->_css['first'] : $this->_css['first'];
                $hasClass = 1;
            }
            // set last class if specified
            if ($last && !empty($this->_css['last'])) {
                $returnClass .= $hasClass ? ' ' . $this->_css['last'] : $this->_css['last'];
                $hasClass = 1;
            }
            // set level class if specified
            if (!empty($this->_css['level'])) {
                $returnClass .= $hasClass ? ' ' . $this->_css['level'] . $level : $this->_css['level'] . $level;
                $hasClass = 1;
            }
            // set parentFolder class if specified
            if ($isFolder && !empty($this->_css['parent']) && ($level < $this->_config['level'] || $this->_config['level'] == 0)) {
                $returnClass .= $hasClass ? ' ' . $this->_css['parent'] : $this->_css['parent'];
                $hasClass = 1;
            }
            // set here class if specified
            if (!empty($this->_css['here']) && $this->isHere($docId)) {
                $returnClass .= $hasClass ? ' ' . $this->_css['here'] : $this->_css['here'];
                $hasClass = 1;
            }
            // set self class if specified
            if (!empty($this->_css['self']) && $docId == $this->modx->resource->id) {
                $returnClass .= $hasClass ? ' ' . $this->_css['self'] : $this->_css['self'];
                $hasClass = 1;
            }
            // set class for weblink
            if (!empty($this->_css['weblink']) && $type == 'reference') {
                $returnClass .= $hasClass ? ' ' . $this->_css['weblink'] : $this->_css['weblink'];
                $hasClass = 1;
            }
        }
        return $returnClass;
    }

    /**
     * Determine the "you are here" point in the menu
     *
     * @param int Document ID to find
     * @return bool Returns true if the document ID was found
     */
    function isHere($did) {
        return in_array($did,$this->parentTree);
    }

    /**
     * Add the specified CSS and Javascript chunks to the page
     *
     * @return void
     */
    function regJsCss() {
        // debug
        if ($this->_config['debug']) {
            $jsCssDebug = array('js' => 'None Specified.', 'css' => 'None Specified.');
        }
        // check and load the CSS
        if ($this->_config['cssTpl']) {
            $cssChunk = $this->fetch($this->_config['cssTpl']);
            if ($cssChunk) {
                $this->modx->regClientCSS($cssChunk);
                if ($this->_config['debug']) {$jsCssDebug['css'] = "The CSS in {$this->_config['cssTpl']} was registered.";}
            } else {
                if ($this->_config['debug']) {$jsCssDebug['css'] = "The CSS in {$this->_config['cssTpl']} was not found.";}
            }
        }
        // check and load the Javascript
        if ($this->_config['jsTpl']) {
            $jsChunk = $this->fetch($this->_config['jsTpl']);
            if ($jsChunk) {
                $this->modx->regClientStartupScript($jsChunk);
                if ($this->_config['debug']) {$jsCssDebug['js'] = "The Javascript in {$this->_config['jsTpl']} was registered.";}
            } else {
                if ($this->_config['debug']) {$jsCssDebug['js'] = "The Javascript in {$this->_config['jsTpl']} was not found.";}
            }
        }
        // debug
        if ($this->_config['debug']) {$this->addDebugInfo("settings","JSCSS","JS/CSS Includes","Results of CSS & Javascript includes.",$jsCssDebug);}
    }

    /**
     * Get the required resources from the database to build the menu
     *
     * @return array The resource array of documents to be processed
     */
    function getData() {
        $ids = array();
        $ids = $this->modx->getChildIds($this->_config['id'],$this->_config['level']);

        // get all of the ids for processing
        if ($this->_config['displayStart'] && $this->_config['id'] !== 0) {
            $ids[] = $this->_config['id'];
        }
        if (!empty($ids)) {
            $c = $this->modx->newQuery('modResource');
            $c->leftJoin('modResourceGroupResource','modResourceGroupResource');
            $c->query['distinct'] = 'DISTINCT';

            // add the ignore hidden option to the where clause
            if (!$this->_config['ignoreHidden']) {
                $c->where(array('hidemenu:=' => 0));
            }

            // add the include docs to the where clause
            if ($this->_config['includeDocs']) {
                $c->where(array('modResource.id IN('.$this->_config['includeDocs'].')'));
            }

            // add the exclude docs to the where clause
            if ($this->_config['excludeDocs']) {
                $c->where(array('modResource.id NOT IN('.$this->_config['excludeDocs'].')'));
            }

            // add the limit to the query
            if ($this->_config['limit']) {
                $c->limit($this->_config['limit'], 0);
            }

            // determine sorting
            if (strtolower($this->_config['sortBy']) == 'random') {
                $c->sortby('rand()', '');
            } else {
                $c->sortby($this->_config['sortBy'],$this->_config['sortOrder']);
            }

            // get document groups for current user
            if($docgrp = $this->modx->getUserDocGroups()) $docgrp = implode(",",$docgrp);

            // build query
            if ($this->modx->isFrontend()) {
                $c->where(array('privateweb:=' => 0));
            } else {
                $c->where(array('1:=' => $_SESSION['mgrRole'], 'privatemgr:=' => 0), XPDO_SQL_OR);
                if (!empty($docgrp)) {
                    $c->where(array('document_group IN('.$docgrp.')'));
                }
            }
            $c->where(array('modResource.id IN('.implode(',', $ids).')'));
            $c->where(array('modResource.published:=' => 1));
            $c->where(array('modResource.deleted:=' => 0));
            $c->groupby('modResource.id');

            $result = $this->modx->getCollection('modResource', $c);

            $resourceArray = array();
            $level = 1;
            $prevParent = -1;
            // setup start level for determining each items level
            if ($this->_config['id'] == 0) {
                $startLevel = 0;
            } else {
                $startLevel = count($this->modx->getParentIds($this->_config['id']));
            }
            $resultIds = array();

            foreach ($result as $doc)  {
                $tempDocInfo = $doc->toArray();
                $resultIds[] = $tempDocInfo['id'];
                $tempDocInfo['content'] = $tempDocInfo['type'] == 'reference' ? $tempDocInfo['content'] : '';
                // create the link
                $linkScheme = $this->_config['fullLink'] ? 'full' : '';
                if ($this->_config['useWeblinkUrl'] !== 'false' && $tempDocInfo['type'] == 'reference') {
                    if (is_numeric($tempDocInfo['content'])) {
                        $tempDocInfo['link'] = $this->modx->makeUrl(intval($tempDocInfo['content']),'','',$linkScheme);
                    } else {
                        $tempDocInfo['link'] = $tempDocInfo['content'];
                    }
                } elseif ($tempDocInfo['id'] == $this->modx->config['site_start']) {
                    $tempDocInfo['link'] = $this->modx->config['site_url'];
                } else {
                    $tempDocInfo['link'] = $this->modx->makeUrl($tempDocInfo['id'],'','',$linkScheme);
                }
                // determine the level, if parent has changed
                if ($prevParent !== $tempDocInfo['parent']) {
                    $level = count($this->modx->getParentIds($tempDocInfo['id'])) - $startLevel;
                }
                // add parent to hasChildren array for later processing
                if (($level > 1 || $this->_config['displayStart']) && !in_array($tempDocInfo['parent'],$this->hasChildren)) {
                    $this->hasChildren[] = $tempDocInfo['parent'];
                }
                // set the level
                $tempDocInfo['level'] = $level;
                $prevParent = $tempDocInfo['parent'];
                // determine other output options
                $useTextField = (empty($tempDocInfo[$this->_config['textOfLinks']])) ? 'pagetitle' : $this->_config['textOfLinks'];
                $tempDocInfo['linktext'] = $tempDocInfo[$useTextField];
                $tempDocInfo['title'] = $tempDocInfo[$this->_config['titleOfLinks']];
                // if TVs were specified keep array flat otherwise array becomes level->parent->doc
                if (!empty($this->tvList)) {
                    $tempResults[] = $tempDocInfo;
                } else {
                    $resourceArray[$tempDocInfo['level']][$tempDocInfo['parent']][] = $tempDocInfo;
                }
            }
            // process the tvs
            if (!empty($this->tvList) && !empty($resultIds)) {
                $tvValues = array();
                // loop through all tvs and get their values for each document
                foreach ($this->tvList as $tvName) {
                    $tvValues = array_merge_recursive($this->appendTV($tvName,$resultIds),$tvValues);
                }
                // loop through the document array and add the tvar values to each document
                foreach ($tempResults as $tempDocInfo) {
                    if (array_key_exists("#{$tempDocInfo['id']}",$tvValues)) {
                        foreach ($tvValues["#{$tempDocInfo['id']}"] as $tvName => $tvValue) {
                            $tempDocInfo[$tvName] = $tvValue;
                        }
                    }
                    $resourceArray[$tempDocInfo['level']][$tempDocInfo['parent']][] = $tempDocInfo;
                }
            }
        }
        return $resourceArray;
    }

    /**
     * Append a TV to the resource array
     *
     * @param string $tvname Name of the Template Variable to append
     * @param array $docIds An array of document IDs to append the TV to
     * @return array A resource array with the TV information
     */
    function appendTV($tvname,$docIds){
        $resourceArray = array();
        foreach ($docIds as $docId) {
            $templateVars = $this->modx->getTemplateVarOutput(array($tvname), $docId);
            foreach ($templateVars as $key => $value) {
                $resourceArray["#{$docId}"]["{$key}"] = $value;
            }
        }
        return $resourceArray;
    }

    /**
     * Get a list of all available TVs
     *
     * @return array An array of all available TV names
     */
    function getTVList() {
        $names = array();
        $templateVars = $this->modx->getCollection('modTemplateVar');
        foreach ($templateVars as $templateVar) {
            $names[] = $templateVar->name;
        }
        return $names;
    }

    /**
     * Check that templates are valid
     *
     * @return void
     */
    function checkTemplates() {
        $nonWayfinderFields = array();

        foreach ($this->_templates as $n => $v) {
            $templateCheck = $this->fetch($v);
            if (empty($v) || !$templateCheck) {
                if ($n === 'outerTpl') {
                    $this->_templates[$n] = '<ul[[+wf.classes]]>[[+wf.wrapper]]</ul>';
                } elseif ($n === 'rowTpl') {
                    $this->_templates[$n] = '<li[[+wf.id]][[+wf.classes]]><a href="[[+wf.link]]" title="[[+wf.title]]" [[+wf.attributes]]>[[+wf.linktext]]</a>[[+wf.wrapper]]</li>';
                } elseif ($n === 'startItemTpl') {
                    $this->_templates[$n] = '<h2[[+wf.id]][[+wf.classes]]>[[+wf.linktext]]</h2>[[+wf.wrapper]]';
                } else {
                    $this->_templates[$n] = false;
                }
                if ($this->_config['debug']) { $this->addDebugInfo('template',$n,$n,"No template found, using default.",array($n => $this->_templates[$n])); }
            } else {
                $this->_templates[$n] = $templateCheck;
                $check = $this->findTemplateVars($templateCheck);
                if (is_array($check)) {
                    $nonWayfinderFields = array_merge($check, $nonWayfinderFields);
                }
                if ($this->_config['debug']) { $this->addDebugInfo('template',$n,$n,"Template Found.",array($n => $this->_templates[$n])); }
            }
        }

        if (!empty($nonWayfinderFields)) {
            $nonWayfinderFields = array_unique($nonWayfinderFields);
            $allTvars = $this->getTVList();

            foreach ($nonWayfinderFields as $field) {
                if (in_array($field, $allTvars)) {
                    $this->placeHolders['tvs'][] = "[[+{$field}]]";
                    $this->tvList[] = $field;
                }
            }
            if ($this->_config['debug']) { $this->addDebugInfo('tvars','tvs','Template Variables',"The following template variables were found in your templates.",$this->tvList); }
        }
    }

    /**
     * Fetch a template from the database or filesystem
     *
     * @param string $tpl Template to be fetched
     * @return string|bool Template HTML or false if no template was found
     */
    function fetch($tpl){
        $template = "";
        if ($this->modx->getChunk($tpl) != "") {
            $template = $this->modx->getChunk($tpl);
        } else if(substr($tpl, 0, 6) == "@FILE:") {
            $template = $this->get_file_contents(substr($tpl, 6));
        } else if(substr($tpl, 0, 6) == "@CODE:") {
            $template = substr($tpl, 6);
        } else {
            $template = false;
        }
        return $template;
    }

    /**
     * Substitute function for get_file_contents()
     *
     * @param string $filename Name of file to be fetched
     * @return string The file contents
     */
    function get_file_contents($filename) {
        if (!function_exists('file_get_contents')) {
            $fhandle = fopen($filename, "r");
            $fcontents = fread($fhandle, filesize($filename));
            fclose($fhandle);
        } else  {
            $fcontents = file_get_contents($filename);
        }
        return $fcontents;
    }

    /**
     * Find all TV names in the template
     *
     * @param string $tpl The template code to be processed
     * @return array|bool An array containing the TV names or false if no names were found
     */
    function findTemplateVars($tpl) {
        preg_match_all('~\[\[\*(.*?)\]\]~', $tpl, $matches);
        //preg_match_all('~\[\[\+(.*?)\]\]~', $tpl, $matches);
        $cnt = count($matches[1]);

        $tvnames = array ();
        for ($i = 0; $i < $cnt; $i++) {
            if (strpos($matches[1][$i], "wf.") === false) {
                $tvnames[] = $matches[1][$i];
            }
        }

        if (count($tvnames) >= 1) {
            return array_unique($tvnames);
        } else {
            return false;
        }
    }

    /**
     * Add debug information to the debug array
     *
     * @param string $group Group to attach the message to
     * @param string $groupkey Group key to attach the message to
     * @param string $header Title for the debug message
     * @param string $message The debug message
     * @param array $info An array of information to be added to the message as $key=>$value pairs
     * @return void
     */
    function addDebugInfo($group,$groupkey,$header,$message,$info) {
        $infoString = '<table border="1" cellpadding="3px">';
        $numInfo = count($info);
        $count = 0;

        foreach ($info as $key => $value) {
            $key = $this->modxPrep($key);
            if ($value === true || $value === false) {
                $value = $value ? 'true' : 'false';
            } else {
                $value = $this->modxPrep($value);
            }
            if ($count == 2) { $infoString .= '</tr>'; $count = 0; }
            if ($count == 0) { $infoString .= '<tr>'; }
            $value = empty($value) ? '&nbsp;' : $value;
            $infoString .= "<td><strong>{$key}</strong></td><td>{$value}</td>";
            $count++;
        }
        $infoString .= '</tr></table>';

        $this->debugInfo[$group][$groupkey] = array(
            'header' => $this->modxPrep($header),
            'message' => $this->modxPrep($message),
            'info' => $infoString,
        );
    }

    /**
     * Render the debug array for display
     *
     * @return string HTML containing the rendered debug information
     */
    function renderDebugOutput() {
        $output = '<table border="1" cellpadding="3px" width="100%">';
        foreach ($this->debugInfo as $group => $item) {
            switch ($group) {
                case 'template':
                    $output .= "<tr><th style=\"background:#C3D9FF;font-size:200%;\">Template Processing</th></tr>";
                    foreach ($item as $parentId => $info) {
                        $output .= "
                            <tr style=\"background:#336699;color:#fff;\"><th>{$info['header']} - <span style=\"font-weight:normal;\">{$info['message']}</span></th></tr>
                            <tr><td>{$info['info']}</td></tr>";
                    }
                    break;
                case 'wrapper':
                    $output .= "<tr><th style=\"background:#C3D9FF;font-size:200%;\">Document Processing</th></tr>";

                    foreach ($item as $parentId => $info) {
                        $output .= "<tr><table border=\"1\" cellpadding=\"3px\" style=\"margin-bottom: 10px;\" width=\"100%\">
                                    <tr style=\"background:#336699;color:#fff;\"><th>{$info['header']} - <span style=\"font-weight:normal;\">{$info['message']}</span></th></tr>
                                    <tr><td>{$info['info']}</td></tr>
                                    <tr style=\"background:#336699;color:#fff;\"><th>Documents included in this wrapper:</th></tr>";

                        foreach ($this->debugInfo['row'] as $key => $value) {
                            $keyParts = explode(':',$key);
                            if ($parentId == $keyParts[0]) {
                                $output .= "<tr style=\"background:#eee;\"><th>{$value['header']}</th></tr>
                                    <tr><td><div style=\"float:left;margin-right:1%;\">{$value['message']}<br />{$value['info']}</div><div style=\"float:left;\">{$this->debugInfo['rowdata'][$key]['message']}<br />{$this->debugInfo['rowdata'][$key]['info']}</div></td></tr>";
                            }
                        }

                        $output .= '</table></tr>';
                    }

                    break;
                case 'settings':
                    $output .= "<tr><th style=\"background:#C3D9FF;font-size:200%;\">Settings</th></tr>";
                    foreach ($item as $parentId => $info) {
                        $output .= "
                            <tr style=\"background:#336699;color:#fff;\"><th>{$info['header']} - <span style=\"font-weight:normal;\">{$info['message']}</span></th></tr>
                            <tr><td>{$info['info']}</td></tr>";
                    }
                    break;
                default:

                    break;
            }
        }
        $output .= '</table>';
        return $output;
    }

    /**
     * Preprocess values for rendering in the debug information
     *
     * @param string $value The value to be processed
     * @return string The processed value
     */
    function modxPrep($value) {
        $value = (strpos($value,"<") !== false) ? htmlentities($value) : $value;
        $value = str_replace("[","&#091;",$value);
        $value = str_replace("]","&#093;",$value);
        $value = str_replace("{","&#123;",$value);
        $value = str_replace("}","&#125;",$value);
        return $value;
    }
}
?>