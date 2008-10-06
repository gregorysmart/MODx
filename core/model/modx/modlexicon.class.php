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
            $this->modx->log(XPDO_LOG_LEVEL_WARN,'Language string not found: "'.$key.'"');
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
             'core' => $this->modx->config['core_path'] . 'cache/lexicon/',
        );
        if ($this->modx->isBackend()) {
            // include_once the language file
            if(!isset($this->modx->config['manager_language'])) {
                $this->modx->cultureKey= 'en';
                // if not set, get the english language file.
            } else {
                $this->modx->cultureKey = $this->modx->config['manager_language'];
            }
            // load default core cache file of lexicon strings
            $_lang = $this->loadCache('core','default',$this->modx->cultureKey);
        }
        $this->_lexicon = & $_lang;
    }

    /**
     * Loads a lexicon topic from the cache. If not found, tries to generate a
     * cache file from the database.
     *
     * @access public
     * @param string $namespace The namespace to load from. Defaults to 'core'.
     * @param string $topic The topic to load. Defaults to 'default'.
     * @param string $language The language to load. Defaults to 'en'.
     * @return array The loaded lexicon array.
     */
    function loadCache($namespace = 'core',$topic = 'default',$language = '') {
        if ($language == '') $language = $this->modx->config['manager_language'];


        $fileName = $this->modx->getCachePath().'lexicon/'.$language.'/'.$namespace.'/'.$topic.'.cache.php';

        $_lang = array();
        if (file_exists($fileName)) {
            @include $fileName;
        } else { // if cache files don't exist, generate
            $cacheManager = $this->modx->getCacheManager();
            $cacheManager->generateLexiconCache($namespace,$topic,$language);

            if (file_exists($fileName)) {
                @include $fileName;
            } else {
                $this->modx->log(MODX_LOG_LEVEL_ERROR,"An error occurred while trying to load and create the cache file for the namespace ".$namespace." with topic: ".$topic);
            }
        }
        return $_lang;
    }

    /**
     * Loads a variable number of topic areas. They must reside as topicname.
     * inc.php files in their proper culture directory. Can load an infinite
     * number of topic areas via a dynamic number of arguments.
     *
     * They are loaded by language:namespace:topic, namespace:topic, or just
     * topic. Examples: $modx->lexicon->load('en:core:snippet'); $modx->lexicon-
     * >load ('demo:test'); $modx->lexicon->load('chunk');
     *
     * @access public
     */
    function load() {
        $topics = func_get_args(); // allow for dynamic number of lexicons to load

        foreach ($topics as $topic) {
            if (!is_string($topic) || $topic == '') return false;
            $nspos = strpos($topic,':');
            $topic = str_replace('.','/',$topic); // allow for lexicon subdirs

            // if no namespace, search all lexicons
            if ($nspos === false) {
                foreach ($this->_paths as $namespace => $path) {
                    $_lang = $this->loadCache($namespace,$topic);
                    $this->_lexicon = array_merge($this->_lexicon,$_lang);
                }
            } else { // if namespace, search specified lexicon
                $params = explode(':',$topic);
                if (count($params) <= 2) {
                    $language = $this->modx->config['manager_language'];
                    $namespace = $params[0];
                    $top = $params[1];
                } else {
                    $language = $params[0];
                    $namespace = $params[1];
                    $top = $params[2];
                }

                $_lang = $this->loadCache($namespace,$top,$language);
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

    /**
     * Clears the lexicon cache for the specified path.
     *
     * @param string $path The path to clear.
     * @return string The results of the cache clearing.
     */
    function clearCache($path = '') {
    	$path = 'lexicon/'.$path;
        $cacheManager = $this->modx->getCacheManager();
        return $cacheManager->clearCache(array($path));
    }
}