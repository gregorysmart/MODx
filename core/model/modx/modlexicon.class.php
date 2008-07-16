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
 * The lexicon handling class.
 * Eventually needs to be reworked to allow for context/area-specific lexicons.
 *
 * @package modx
 * @subpackage mysql
 */
class modLexicon {
    /**
     * @var array $_lexicon The actual language array.  
     * @access private
     */
    var $_lexicon;
    /**
     * @var array $_paths Directories to search for language strings in
     * @access private
     */
    var $_paths;
    /**
     * @var MODx $modx Reference to the MODx object.
     * @access protected
     */
    var $modx = null;

    function modLexicon(&$modx) {
        $this->__construct($modx);
    }
    function __construct(&$modx) {
        $this->modx =& $modx;
        $this->init();
    }
    
    /**
     * Returns if the key exists in the lexicon.
     * 
     * @access public
     * @param string $index
     * @return boolean True if exists.
     */
    function exists($index) {
        return (is_string($index) && isset($this->_lexicon[$index]));
    }
    
    /**
     * Get a lexicon string by its index.
     * 
     * @access public
     * @param string $key The key of the lexicon string.
     * @param array $params An assocative array of placeholder
     * keys and values to parse
     * @return string The text of the lexicon key, blank if not found.
     */
    function process($key,$params = array()) {
        if (!is_string($key) || !isset($this->_lexicon[$key])) {
            $this->modx->_log(XPDO_LOG_LEVEL_WARN,'Language string not found: "'.$key.'"');
            return $key;
        }
        return empty($params)
            ? $this->_lexicon[$key]
            : $this->_parse($this->_lexicon[$key],$params);
    }
    
    /**
     * Parses a lexicon string, replacing placeholders with
     * specified strings.
     * 
     * @access private
     * @param string $str The string to parse
     * @param array $params An associative array of keys to replace
     * @return string The processed string
     */
    function _parse($str,$params) {
        if (!$str) return '';
        if (empty($params)) return $str; 
        
        foreach ($params as $k => $v) {
            $str = str_replace('[[+'.$k.']]',$v,$str);
        }
        return $str;
    }
    
    /**
     * Accessor method for the lexicon array.
     * 
     * @access public
     * @return array The internal lexicon.
     */
    function fetch() {
        return $this->_lexicon;
    }
    
    /**
     * Initializes the lexicon with the default strings.
     * 
     * @access protected
     */
    function init() {
        $_lang= array ();
        $this->_paths = array(
             'core' => $this->modx->config['core_path'] . 'lexicon/',
        );
        if ($this->modx->isBackend()) {
            // include_once the language file
            if(!isset($this->modx->config['manager_language'])) {
                $this->modx->cultureKey= 'en';
                // if not set, get the english language file.
            } else {
                $this->modx->cultureKey = $this->modx->config['manager_language'];
            }
            @include_once $this->_paths['core'].'en/default.inc.php';
            // always load the default lexicon
            $length_eng_lang= count($_lang);
            if ($this->modx->cultureKey !== 'en') {
                @include_once $this->_paths['core'].$this->modx->cultureKey.'/default.inc.php';
            }
        }
        $this->_lexicon = & $_lang;
    }
    
    /**
     * Adds a directory to search for language files
     * 
     * @access public
     * @param string $dir The directory to add
     * @param string $namespace A namespace for the directory
     * @return boolean True if successful
     */
    function addDirectory($dir,$namespace = '') {
        if ($namespace == 'core') return false;
        if ($namespace == '') {
            // prevent duplicate names if no name specified
            $namespace = count($this->_paths);
        }
        if (is_dir($dir)) {
            $this->_paths[$namespace] = $dir;
            return true;
        } else {
            $this->modx->_log(XPDO_LOG_LEVEL_ERROR,'Cannot add lexicon directory: '.$dir.' with namespace: '.$namespace);
        }
        return false;
    }
    
    /**
     * Loads a variable number of focus areas. They must reside as focusname.
     * inc.php files in their proper culture directory. Can load an infinite
     * number of focus areas via a dynamic number of arguments.
     * 
     * @access public
     */
    function load() {
        $foci = func_get_args(); // allow for dynamic number of lexicons to load
        foreach ($foci as $focus) {
            if (!is_string($focus) || $focus == '') return false;
            $nspos = strpos($focus,':');
            $focus = str_replace('.','/',$focus); // allow for lexicon subdirs
            
            $found = false;
            // if no namespace, search all lexicons
            if ($nspos === false) {
                foreach ($this->_paths as $namespace => $path) {
                    $f = $path.$this->modx->cultureKey.'/'.$focus.'.inc.php';
                    if (file_exists($f)) {
                        $found = true;
                        break;
                    }
                }
            } else { // if namespace, search specified lexicon
                $ns = substr($focus,0,$nspos);
                $foc = substr($focus,$nspos+1);
                if (isset($this->_paths[$ns])) {
                    $path = $this->_paths[$ns];
                    $f = $path.$this->modx->cultureKey.'/'.$foc.'.inc.php';
                    if (file_exists($f)) {
                        $found = true;
                    }
                }
            }
            if ($found) {
                $_lang = array();
                @include_once $f;
                $this->_lexicon = array_merge($this->_lexicon,$_lang);
            }
        }
    }
    
    /**
     * Sets a lexicon key to a value.
     * 
     * @access public
     * @param string/array $keys Either an array of array pairs of key/values or
     * a key string.
     * @param string $text The text to set, if the first parameter is a string.
     */
    function set($keys, $text = '') {
        if (is_array($keys)) {
            foreach ($keys as $key => $str) {
                if ($key == '') continue;
                $this->_lexicon[$key] = $str;
            }
        } else if (is_string($keys) && $keys != '') {
            $this->_lexicon[$keys] = $text;
        }
    }
}