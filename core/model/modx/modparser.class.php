<?php
/*
 * MODx Revolution
 * 
 * Copyright 2006, 2007, 2008 by the MODx Team.
 * All rights reserved.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 */

/**
 * Represents the MODx parser responsible for processing MODx tags.
 *
 * This class encapsulates all of the functions for collecting and evaluating
 * element tags embedded in text content.
 *
 * @package modx
 */
class modParser {
    var $modx= null;

    function modParser(&$modx) {
        $this->__construct($modx);
    }
    function __construct(&$modx) {
        $this->modx= & $modx;
    }

    /**
     * Collects element tags in a string and injects them into an array.
     *
     * @param string $origContent The content to collect tags from.
     * @param array &$matches An array in which the collected tags will be
     * stored (by reference)
     * @param string $prefix The characters that define the start of a tag
     * (default= "[[").
     * @param string $suffix The characters that define the end of a tag
     * (default= "]]").
     * @return integer The number of tags collected from the content.
     */
    function collectElementTags($origContent, & $matches, $prefix= '[[', $suffix= ']]') {
        $matchCount= 0;
        if (!empty ($origContent) && is_string($origContent) && strpos($origContent, $prefix) !== false) {
            $openCount= 0;
            $offset= 0;
            $openPos= 0;
            $closePos= 0;
            if (($startPos= strpos($origContent, $prefix)) === false) {
                return $matchCount;
            }
            $offset= $startPos +strlen($prefix);
            if (($stopPos= strrpos($origContent, $suffix)) === false) {
                return $matchCount;
            }
            $stopPos= $stopPos + strlen($suffix);
            $length= $stopPos - $startPos;
            $content= $origContent;
            while ($length > 0) {
                $openCount= 0;
                $content= substr($content, $startPos);
                $openPos= 0;
                $offset= strlen($prefix);
                if (($closePos= strpos($content, $suffix, $offset)) === false) {
                    break;
                }
                $nextOpenPos= strpos($content, $prefix, $offset);
                while ($nextOpenPos !== false && $nextOpenPos < $closePos) {
                    $openCount++;
                    $offset= $nextOpenPos + strlen($prefix);
                    $nextOpenPos= strpos($content, $prefix, $offset);
                }
                $nextClosePos= strpos($content, $suffix, $closePos + strlen($suffix));
                while ($openCount > 0 && $nextClosePos !== false) {
                    $openCount--;
                    $closePos= $nextClosePos;
                    $nextOpenPos= strpos($content, $prefix, $offset);
                    while ($nextOpenPos !== false && $nextOpenPos < $closePos) {
                        $openCount++;
                        $offset= $nextOpenPos + strlen($prefix);
                        $nextOpenPos= strpos($content, $prefix, $offset);
                    }
                    $nextClosePos= strpos($content, $suffix, $closePos + strlen($suffix));
                }
                $closePos= $closePos +strlen($suffix);

                $outerTagLength= $closePos - $openPos;
                $innerTagLength= ($closePos -strlen($suffix)) - ($openPos +strlen($prefix));

                $matches[$matchCount][0]= substr($content, $openPos, $outerTagLength);
                $matches[$matchCount][1]= substr($content, ($openPos +strlen($prefix)), $innerTagLength);
                $matchCount++;

                if ($nextOpenPos === false) {
                    $nextOpenPos= strpos($content, $prefix, $closePos);
                }
                if ($nextOpenPos !== false) {
                    $startPos= $nextOpenPos;
                    $length= $length - $nextOpenPos;
                } else {
                    $length= 0;
                }
            }
        }
        if ($this->modx->getDebug() === true && !empty($matches)) {
            $this->modx->_log(MODX_LOG_LEVEL_DEBUG, "modParser::collectElementTags \$matches = " . print_r($matches, 1) . "\n");
//            $this->modx->cacheManager->writeFile(MODX_CORE_PATH . 'logs/parser.log', print_r($matches, 1) . "\n", 'a');
        }
        return $matchCount;
    }

    /**
     * Collects and processes any set of tags as defined by a prefix and suffix.
     *
     * @param string $parentTag The tag representing the element processing this
     * tag.  Pass an empty string to allow parsing without this recursion check.
     * @param string &$content The content to process and act on (by reference).
     * @param boolean $processUncacheable Determines if noncacheable tags are to
     * be processed (default= false).
     * @param boolean $removeUnprocessed Determines if unprocessed tags should
     * be left in place in the content, or stripped out (default= false).
     * @param string $prefix The characters that define the start of a tag
     * (default= "[[").
     * @param string $suffix The characters that define the end of a tag
     * (default= "]]").
     * @param array $tokens Indicates that the parser should only process tags
     * with the tokens included in this array.
     * @param integer $depth The maximum iterations to recursively process tags
     * returned by prior passes, 0 by default.
     */
    function processElementTags($parentTag, & $content, $processUncacheable= false, $removeUnprocessed= false, $prefix= "[[", $suffix= "]]", $tokens= array (), $depth= 0) {
        $depth = intval($depth);
        $depth = $depth > 0 ? $depth - 1 : 0;
        $processed= 0;
        $tags= array ();
        // invoke OnParseDocument event
        $this->modx->documentOutput = $content;      // store source code so plugins can
        $this->modx->invokeEvent('OnParseDocument');    // work on it via $modx->documentOutput
        $content = $this->modx->documentOutput;
        if ($collected= $this->collectElementTags($content, $tags, $prefix, $suffix, $tokens)) {
            $tagMap= array ();
            foreach ($tags as $tag) {
                $token= substr($tag[1], 0, 1);
                if (!$processUncacheable && $token === '!') {
                    if ($removeUnprocessed) {
                        $tagMap[$tag[0]]= '';
                    }
                    $collected--;
                    continue;
                }
                elseif (!empty ($tokens) && !in_array($token, $tokens)) {
                    $collected--;
                    continue;
                }
                if ($tag[0] === $parentTag) {
                    $tagMap[$tag[0]]= '';
                    $processed++;
                    continue;
                }
                $tagOutput= $this->processTag($tag);
                if (($tagOutput === null || $tagOutput === false) && $removeUnprocessed) {
                    $tagMap[$tag[0]]= '';
                    $processed++;
                }
                elseif ($tagOutput !== null && $tagOutput !== false) {
                    $tagMap[$tag[0]]= $tagOutput;
                    $processed++;
                }
            }
            $this->mergeTagOutput($tagMap, $content);
            if ($depth > 0) {
                $processed+= $this->processElementTags($parentTag, $content, $processUncacheable, $removeUnprocessed, $prefix, $suffix, $tokens, $depth);
            }
        }
        return $processed;
    }

    /**
     * Merges processed tag output into provided content string.
     *
     * @param array $tagMap An array with full tags as keys and processed output
     * as the values.
     * @param string $content The content to merge the tag output with (passed by
     * reference).
     */
    function mergeTagOutput($tagMap, & $content) {
        if (!empty ($content) && is_array($tagMap) && !empty ($tagMap)) {
            $content= str_replace(array_keys($tagMap), array_values($tagMap), $content);
        }
    }

    /**
     * Parses a tag property string.
     *
     * @param string $propString A valid element property string to parse.
     * @return array An array with each property parsed from the propString.
     */
    function parseProperties($propString) {
        $properties= array ();
        if (!empty ($propString)) {
            $tagProps= xPDO :: escSplit("&", $propString);
            foreach ($tagProps as $prop) {
                $property= xPDO :: escSplit('=', $prop);
                if (count($property) == 2) {
                    $propName= $property[0];
                    if (substr($propName, 0, 4) == "amp;") {
                        $propName= substr($propName, 4);
                    }
                    $propValue= trim($property[1], "`");
                    $propValue= str_replace("``", "`", $propValue);
                    $pvTmp= array ();
                    if (($pvTmp= explode(';', $propValue)) && isset ($pvTmp[1])) {
                        if ($pvTmp[1]=='list' && isset ($pvTmp[3]) && $pvTmp[3]) {
                            $properties[$propName] = $pvTmp[3]; //list default
                        }
                        elseif ($pvTmp[1]!='list' && isset ($pvTmp[2]) && $pvTmp[2]) {
                            $properties[$propName] = $pvTmp[2];
                        }
                    } else {
                        $properties[$propName]= $propValue;
                    }
                }
            }
        }
        return $properties;
    }

    /**
     * Processes a modElement tag and returns the result.
     *
     * @param string $tag A full tag string parsed from content.
     * @return mixed The output of the processed element represented by the
     * specified tag.
     */
    function processTag($tag) {
        $element= null;
        $elementOutput= null;

        $outerTag= $tag[0];
        $innerTag= $tag[1];

        // collect any nested element tags in the innerTag and process them
        $this->processElementTags($outerTag, $innerTag, true);
        $outerTag= '[[' . $innerTag . ']]';

        $tagParts= xPDO :: escSplit('?', $innerTag, '`', 2);
        $tagName= trim($tagParts[0]);
        $tagPropString= null;
        if (isset ($tagParts[1])) {
            $tagPropString= trim($tagParts[1]);
        }
        $token= substr($tagName, 0, 1);
        $tokenOffset= 0;
        $cacheable= true;
        if ($token === '!') {
            $cacheable= false;
            $tokenOffset++;
            $token= substr($tagName, $tokenOffset, 1);
        }
        if ($cacheable) {
            $elementOutput= $this->loadFromCache($outerTag);
        }
        if ($elementOutput === null) {
            switch ($token) {
            	case '+':
                    $tagName= substr($tagName, 1 + $tokenOffset);
                    $element= new modPlaceholderTag($this->modx);
                    $element->set('name', $tagName);
                    $element->_tag= $outerTag;
                    $element->_cacheable= false; // placeholders cannot be cacheable!
                    $elementOutput= $element->process($tagPropString);
            		break;
            	case '%':
                    $tagName= substr($tagName, 1 + $tokenOffset);
                    $element= new modLexiconTag($this->modx);
                    $element->set('name', $tagName);
                    $element->_tag= $outerTag;
                    $element->_cacheable= $cacheable;
                    $elementOutput= $element->process($tagPropString);
            		break;
                case '~':
                    $tagName= substr($tagName, 1 + $tokenOffset);
                    $element= new modLinkTag($this->modx);
                    $element->set('name', $tagName);
                    $element->_tag= $outerTag;
                    $element->_cacheable= $cacheable;
                    $elementOutput= $element->process($tagPropString);
                    break;
                case '$':
                    $tagName= substr($tagName, 1 + $tokenOffset);
                    if ($element= $this->modx->getObject('modChunk', array ('name' => $this->realname($tagName)), true)) {
                        $element->set('name', $tagName);
                        $element->_tag= $outerTag;
                        $element->_cacheable= $cacheable;
                        $elementOutput= $element->process($tagPropString);
                    }
                    break;
                case '*':
                    $tagName= substr($tagName, 1 + $tokenOffset);
                    $nextToken= substr($tagName, 0, 1);
                    if ($nextToken === '#') {
                        $tagName= substr($tagName, 1);
                    }
                    if (is_array($this->modx->resource->_fieldMeta) && in_array($this->realname($tagName), array_keys($this->modx->resource->_fieldMeta))) {
                        $element= new modFieldTag($this->modx);
                        $element->set('name', $tagName);
                        $element->_tag= $outerTag;
                        $element->_cacheable= $cacheable;
                        $elementOutput= $element->process($tagPropString);
                    }
                    elseif ($element= $this->modx->getObject('modTemplateVar', array ('name' => $this->realname($tagName)), true)) {
                        $element->set('name', $tagName);
                        $element->_tag= $outerTag;
                        $element->_cacheable= $cacheable;
                        $elementOutput= $element->process($tagPropString);
                    }
                    break;
            	default:
                    $tagName= substr($tagName, $tokenOffset);
                    if ($element= $this->modx->getObject('modSnippet', array ('name' => $this->realname($tagName)), true)) {
                        $element->set('name', $tagName);
                        $element->_tag= $outerTag;
                        $element->_cacheable= $cacheable;
                        $elementOutput= $element->process($tagPropString);
                    }
            }
        }
        if ($this->modx->getDebug() === true) {
            $this->modx->_log(XPDO_LOG_LEVEL_DEBUG, "Processing {$outerTag} as {$innerTag} using tagname {$tagName}:\n" . print_r($elementOutput, 1) . "\n\n");
//            $this->modx->cacheManager->writeFile(MODX_BASE_PATH . 'parser.log', "Processing {$outerTag} as {$innerTag}:\n" . print_r($elementOutput, 1) . "\n\n", 'a');
        }
        return $elementOutput;
    }

    /**
     * Gets the real name of an element containing filter modifiers.
     *
     * @param string $unfiltered The unfiltered name of a {@link modElement}.
     * @return string The name minus any filter modifiers.
     */
    function realname($unfiltered) {
        $filtered= $unfiltered;
        $split= xPDO :: escSplit(':', $filtered);
        if ($split && isset($split[0])) {
            $filtered= $split[0];
        }
        return $filtered;
    }

    /**
     * Loads output cached by complete tag signature from the elementCache.
     *
     * @uses modX::$_elementCache Stores all cacheable content from processed
     * elements.
     * @param string tag The tag signature representing the element instance.
     * @return string The cached output from the element instance.
     */
    function loadFromCache($tag) {
        $elementOutput= null;
        if (isset ($this->modx->elementCache[$tag])) {
            $elementOutput= (string) $this->modx->elementCache[$tag];
        }
        return $elementOutput;
    }
}

/**
 * Abstract class representing a pseudo-element that can be parsed.
 *
 * @abstract You must implement the process() method on derivatives to implement
 * a parseable element tag.  All element tags are identified by a unique single
 * character token at the beginning of the tag string.
 * @package modx
 */
class modTag {
    var $modx= null;
    var $name= '';
    var $_content= null;
    var $_output= '';
    var $_result= true;
    var $_properties= array ();
    var $_processed= false;
    var $_tag= '';
    var $_token= '';
    var $_fields= array (
        'name' => ''
    );
    var $_cacheable= true;
    var $_filters= array ();

    function modTag(& $modx) {
        $this->__construct($modx);
    }
    function __construct(& $modx) {
        $this->modx =& $modx;
        $this->name =& $this->_fields['name'];
    }

    /**
     * Generic getter method for modTag attributes.
     *
     * @see xPDOObject::get()
     * @param string $k The field key.
     * @return mixed The value of the field or null if it is not set.
     */
    function get($k) {
        return isset ($this->_fields[$k]) ? $this->_fields[$k] : null;
    }
    /**
     * Generic setter method for modTag attributes.
     *
     * @see xPDOObject::set()
     * @param string $k The field key.
     * @param mixed $v The value to assign to the field.
     */
    function set($k, $v) {
        $this->_fields[$k]= $v;
    }
    /**
     * Cache the element into the elementCache by tag signature.
     * @see modElement::cache()
     */
    function cache() {
        if ($this->_cacheable) {
            $this->modx->elementCache[$this->_tag]= $this->_output;
        }
    }

    function getTag() {
        if (empty($this->_tag) && ($name = $this->get('name'))) {
            $propTemp = array();
            if (empty($this->_propertyString) && !empty($this->_properties)) {
                while(list($key, $value) = each($this->_properties)) {
                    $propTemp[] = trim($key) . '=`' . $value . '`';
                }
                if (!empty($propTemp)) {
                    $this->_propertyString = '?' . implode('&', $propTemp);
                }
            }
            $tag = '[[';
            $tag.= $this->_token;
            $tag.= $name;
            if (!empty($this->_propertyString)) {
                $tag.= $this->_propertyString;
            }
            $tag.= ']]';
            $this->_tag = $tag;
        }
        if (empty($this->_tag)) {
            $this->xpdo->_log(XPDO_LOG_LEVEL_ERROR, 'Instance of ' . get_class($this) . ' produced an empty tag!');
        }
        return $this->_tag;
    }

    /**
     * Process the tag and return the result.
     *
     * @see modElement::process()
     * @param array|string $properties An array of properties or a formatted
     * property string.
     * @param string $content Optional content to use for the element
     * processing.
     * @return mixed The result of processing the tag.
     */
    function process($properties= null, $content= null) {
        $this->modx->getParser();
        if ($properties !== null && !empty ($properties)) {
            if (is_string($properties)) {
                $this->_properties= array_merge($this->modx->parser->parseProperties($this->get('properties')), $this->modx->parser->parseProperties($properties));
            }
            elseif (is_array($properties)) {
                $this->_properties= array_merge($this->modx->parser->parseProperties($this->get('properties')), $properties);
            }
        } else {
            $this->_properties= $this->modx->parser->parseProperties($this->get('properties'));
        }
        $this->getTag();
        $this->filterInput();
        if (is_string($content) && !empty($content)) {
            $this->_content= $content;
        }
        if ($this->modx->getDebug() === true) $this->modx->_log(MODX_LOG_LEVEL_DEBUG, "Processing Element: " . $this->get('name') . ($this->_tag ? "\nTag: {$this->_tag}" : "\n") . "\nProperties: " . print_r($this->_properties, true));
        return $this->_result;
    }
    /**
     * Apply an input filter to a tag.
     *
     * This is called by default in {@link modTag::process()} after the tag
     * properties have been parsed.
     *
     * @see modElement::filterInput()
     */
    function filterInput() {
        $filter= null;
        if (!isset ($this->_filters['input'])) {
            if (!$inputFilterClass= $this->get('input_filter')) {
                if (!isset($this->modx->config['input_filter']) || !$inputFilterClass= $this->modx->config['input_filter']) {
                    $inputFilterClass= 'filters.modInputFilter';
                }
            }
            if ($filterClass= $this->modx->loadClass($inputFilterClass, '', false, true)) {
                if ($filter= new $filterClass($this->modx)) {
                    $this->_filters['input']= $filter;
                }
            }
        }
        if (isset ($this->_filters['input']) && is_a($this->_filters['input'], 'modInputFilter')) {
            $this->_filters['input']->filter($this);
        }
    }
    /**
     * Apply an output filter to a tag.
     *
     * Call this method in your {modTag::process()} implementation when it is
     * appropriate, typically once all processing has been completed, but before
     * any caching takes place.
     */
    function filterOutput() {
        $filter= null;
        if (!isset ($this->_filters['output'])) {
            if (!$outputFilterClass= $this->get('output_filter')) {
                if (!isset($this->modx->config['output_filter']) || !$outputFilterClass= $this->modx->config['output_filter']) {
                    $outputFilterClass= 'filters.modOutputFilter';
                }
            }
            if ($filterClass= $this->modx->loadClass($outputFilterClass, '', false, true)) {
                if ($filter= new $filterClass($this->modx)) {
                    $this->_filters['output']= $filter;
                }
            }
        }
        if (isset ($this->_filters['output']) && is_a($this->_filters['output'], 'modOutputFilter')) {
            $this->_filters['output']->filter($this);
        }
    }
}
/**
 * Tag representing a modResource field from the current MODx resource.
 *
 * [[*content]] Represents the content field from modResource.
 *
 * @uses modX::$resource The modResource instance being processed by modX.
 * @package modx
 */
class modFieldTag extends modTag {
    function modFieldTag(& $modx) {
        $this->__construct($modx);
    }
    function __construct(& $modx) {
        parent :: __construct($modx);
        $this->_token = '*';
    }

    /**
     * Process the modFieldTag and return the output.
     */
    function process($properties= null, $content= null) {
        parent :: process($properties, $content);
        if ($this->_cacheable && isset ($this->modx->elementCache[$this->_tag])) {
            $this->_output= $this->modx->elementCache[$this->_tag];
        } else {
            if (!is_string($this->_content) || empty($this->_content))
                $this->_content= $this->modx->resource->get($this->get('name'));
            if (is_string($this->_content) && !empty ($this->_content)) {
                // collect element tags in the content and process them
                $maxIterations= isset ($this->modx->config['parser_max_iterations']) ? intval($this->modx->config['parser_max_iterations']) : 10;
                $this->modx->parser->processElementTags($this->_tag, $this->_content, false, false, '[[', ']]', array(), $maxIterations);
            }
            $this->filterOutput();
            $this->_output= $this->_content;
            $this->cache();
        }
        $this->_processed= true;
        // finally, return the processed element content
        return $this->_output;
    }
}

/**
 * Represents placeholder tags.
 *
 * [[+placeholder_key]] Represents a placeholder with name placeholder_key.
 *
 * @uses modX::getPlaceholder() To retrieve the placeholder value.
 * @package modx
 */
class modPlaceholderTag extends modTag {
    function modPlaceholderTag(& $modx) {
        $this->__construct($modx);
    }
    function __construct(& $modx) {
        parent :: __construct($modx);
        $this->_cacheable = false;
        $this->_token = '+';
    }

    /**
     * Processes the modPlaceholderTag, recursively processing nested tags.
     *
     * Tags in the properties of the tag itself, or the content returned by the
     * tag element are processed.  Non-cacheable nested tags are only processed
     * if this tag element is also non-cacheable.
     */
    function process($properties= null, $content= null) {
        parent :: process($properties, $content);
        if ($this->_cacheable && isset ($this->modx->elementCache[$this->_tag])) {
            $this->_output= $this->modx->elementCache[$this->_tag];
        } else {
            if (!is_string($this->_content) || empty($this->_content))
                $this->_content= $this->modx->getPlaceholder($this->get('name'));
            if (is_string($this->_content) && !empty ($this->_content)) {
                // collect element tags in the content and process them
                $maxIterations= isset ($this->modx->config['parser_max_iterations']) ? intval($this->modx->config['parser_max_iterations']) : 10;
                $this->modx->parser->processElementTags($this->_tag, $this->_content, false, false, '[[', ']]', array(), $maxIterations);
            }
            $this->filterOutput();
            $this->_output= $this->_content;
            $this->cache();
        }
        $this->_processed= true;
        // finally, return the processed element content
        return $this->_output;
    }
}

/**
 * Represents link tags.
 *
 * [[~12]] Creates a URL from the specified resource identifier.
 *
 * @package modx
 */
class modLinkTag extends modTag {
    function modLinkTag(& $modx) {
        $this->__construct($modx);
    }
    function __constructor(& $modx) {
        parent :: __construct($modx);
        $this->_token = '~';
    }

    /**
     * Processes the modLinkTag, recursively processing nested tags.
     */
    function process($properties= null, $content= null) {
        parent :: process($properties, $content);
        if ($this->_cacheable && isset ($this->modx->elementCache[$this->_tag])) {
            $this->_output= $this->modx->elementCache[$this->_tag];
        } else {
            if (!is_string($this->_content) || empty($this->_content)) {
                if (!$this->get('name')) {
                    $this->set('name', isset ($this->modx->config['error_page']) ? $this->modx->config['error_page'] : (isset ($this->modx->config['site_start']) ? $this->modx->config['site_start'] : $this->modx->config['base_url']));
                }
                $this->_content= $this->get('name');
            }
            if (is_string($this->_content) && !empty ($this->_content)) {
                if (isset ($this->modx->aliasMap[$this->_content])) {
                    $this->_content= $this->modx->aliasMap[$this->_content];
                }
                $this->_content= $this->modx->makeUrl($this->_content);
            } else {
                $this->_content= $this->modx->config['base_url'];
            }
            $this->filterOutput();
            $this->_output= $this->_content;
            $this->cache();
        }
        $this->_processed= true;
        // finally, return the processed element content
        return $this->_output;
    }
}

/**
 * Represents Lexicon tags, for localized strings.
 *
 * [[%word_or_phase]] Returns the lexicon representation of 'word_or_phrase' for
 * the currently loaded language.
 *
 * @package modx
 */
class modLexiconTag extends modTag {
    function modLexiconTag(& $modx) {
        $this->__construct($modx);
    }
    function __construct(& $modx) {
        parent :: __construct($modx);
        $this->_token = '%';
    }

    /**
     * Processes a modLexiconTag, recursively processing nested tags.
     */
    function process($properties= null, $content= null) {
        parent :: process($properties, $content);
        if ($this->_cacheable && isset ($this->modx->elementCache[$this->_tag])) {
            $this->_output= $this->modx->elementCache[$this->_tag];
        } else {
            if (!is_string($this->_content) || empty($this->_content))
                $this->_content= $this->modx->lexicon($this->get('name'),$this->_properties);
            if (is_string($this->_content) && !empty ($this->_content)) {
                // collect element tags in the content and process them
                $maxIterations= isset ($this->modx->config['parser_max_iterations']) ? intval($this->modx->config['parser_max_iterations']) : 10;
                $this->modx->parser->processElementTags($this->_tag, $this->_content, false, false, '[[', ']]', array(), $maxIterations);
            }
            $this->filterOutput();
            $this->_output= $this->_content;
            $this->cache();
        }
        $this->_processed= true;
        // finally, return the processed element content
        return $this->_output;
    }
}
?>