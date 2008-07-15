<?php
/**
 * modRSSParser
 *
 * @package modx
 * @subpackage xmlrss
 */
/**
 * RSS Parser for MODx, implementing MagpieRSS
 *
 * @package modx
 * @subpackage xmlrss
 */
class modRSSParser {
    /**#@+
     * Constructor for modRSSParser
     *
     * @param modX &$modx A reference to the modx object.
     */
    function modRSSParser(&$modx) {
        $this->__construct($modx);
    }
    /** @ignore */
    function __construct(&$modx) {
        $this->modx =& $modx;
        if (!defined('MAGPIE_CACHE_DIR')) {
            define('MAGPIE_CACHE_DIR',$this->modx->config['core_path'].'cache/rss/');
        }
        $this->modx->loadClass('xmlrss.rssfetch','',false,true);
    }
    /**#@- */

    /**
     * Parses and interprets an RSS or Atom URL
     *
     * @param string $url The URL of the RSS/Atom feed.
     * @return array The parsed RSS/Atom feed. $rss->items gets you the items parsed.
     */
    function parse($url) {
        $rss = call_user_func('fetch_rss',$url);
        return $rss;
    }
}