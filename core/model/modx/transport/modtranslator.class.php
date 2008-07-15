<?php
/**
 * @package modx
 * @subpackage transport
 */
require_once MODX_CORE_PATH . 'model/modx/modtranslate095.class.php';
 /**
 * Abstracts the pre-097 site conversion utilities.
 *
 */
class modTranslator extends modTranslate095 {
    /**
    * @var array An array of files (with full paths) to be translated
    */
    var $files;
    /**
    * @var array Paths of files, according to the patterns, to be translated (recursive)
    */
    var $paths;
    /**
    * @var string File patterns to limit the file types to be included for translation in the paths
    */
    var $patterns;
    /**
    * @var array Can either be an array of array of classname => fields to translate,
    *  empty to skip classes or null to process all standard content
    *  fields for MODx (use processAllFields())
    */
    var $classes;
    /**
    * @var boolean If true, paths added will be recursively translated.
    */
    var $recursive;

    function modTranslator(&$modx, $recursive = true) {
        $this->__construct($modx, $recursive);
    }
    function __construct(&$modx, $recursive = true) {
        parent :: __construct($modx);
        $this->recursive = $recursive;
        $this->files = array();
        $this->paths = array();
        $this->patterns = array();
        $this->classes = array();
    }

    /**
    * Adds a file to be translated.
    * Can have an unlimited number of arguments.
    */
    function addFile() {
        $args = func_get_args();
        $c = count($args);
        for ($i=0;$i<$c;$i++) {
            $file = $args[$i];
            if (!file_exists($file)) continue;
            $this->files[] = $file;
        }
    }
    
    /**
    * Adds a path to be translated.
    * Can have an unlimited number of arguments.
    */
    function addPath() {
        $args = func_get_args();
        $c = count($args);
        for ($i=0;$i<$c;$i++) {
            $path = $args[$i];
            if (!file_exists($path) || !is_dir($path) || in_array($path, array('.', '..'))) continue;
            $this->paths[] = $path;
            if ($this->recursive) {
                $this->getAllSubdirs($path);
            }
        }
    }

    /**
    * Recursively adds all subdirs of a directory.
    *
    * @param string $path The path to recursively search in.
    */
    function getAllSubdirs($path) {
        if (file_exists($path) && is_dir($path)) {
            $handle = opendir($path);
            if ($handle) {
                while (($file = readdir($handle)) !== false) {
                    if (is_dir($path . $file . '/') && $file !== '.svn' && !in_array($file, array('.', '..'))) {
                        array_push($this->paths, $path . $file . '/');
                        $this->getAllSubdirs($path . $file . '/');
                    }
                }
                closedir($handle);
            }
        }
    }

    /**
    * Sets the translator to process all standard MODx fields.
    *
    * @param boolean $b If false, will revert to an empty array of classes.
    */
    function processAllFields($b = true) {
        $this->classes = $b ? NULL : array();
    }

    /**
    * Adds a pattern to be translated.
    * Can have an unlimited number of arguments.
    */
    function addPattern() {
        $args = func_get_args();
        $c = count($args);
        for ($i=0;$i<$c;$i++) {
            $pattern = $args[$i];
            $this->patterns[] = $pattern;
        }
    }
    /**
    * Adds a class to be translated.
    * Can have an unlimited number of arguments.
    */
    function addClass() {
        $args = func_get_args();
        $c = count($args);
        for ($i=0;$i<$c;$i++) {
            $class = $args[$i];
            $this->classes[] = $class;
        }
    }

    /**
    * Translates the site.
    *
    * @param boolean $save Determines if translation is written to the database tables
    *  and/or files (changes are permanent if true)
    * @param boolean $toFile A path to a file where a log of the translation session is written
    */
    function translateSite($save = false,$toFile = false) {
        if (!empty($this->paths)) {
            $c = count($this->paths);
            for ($i=0;$i<$c;$i++) {
                $path = $this->paths[$i];
                $directory= opendir($path);
                if ($directory) {
                    while (false !== ($filename= readdir($directory))) {
                        $extension= substr($filename, strrpos($filename, '.') + 1);
                        if ($filename != '.' && $filename != '..' && $filename != '.svn' && in_array($extension,$this->patterns)) {
                            $file= $path . $filename;
                            if (!is_dir($file)) {
                                $this->files[]= $file;
                            }
                        }
                    }
                    closedir($directory);
                }
            }
        }
        if ($toFile !== false) {
            $toFile = $this->modx->getCachePath() . 'logs/' . $toFile;
        }
        parent::translateSite($save,$this->classes,$this->files,$toFile);
    }
}
