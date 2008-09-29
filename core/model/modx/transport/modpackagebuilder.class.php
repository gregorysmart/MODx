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
 * @package modx
 * @subpackage transport
 */
 /**
 * Abstracts the package building process
 *
 */
class modPackageBuilder {
	/**
    * @var string The directory in which the package file is located.
    */
	var $directory;
	/**
    * @var string The unique signature for the package.
    */
	var $signature;
	/**
    * @var string The filename of the actual package.
    */
	var $filename;
	/**
    * @var string The xPDOTransport package object.
    */
	var $package;
    /**
     * @var string The modNamespace that the package is associated with.
     */
    var $namespace;
    /**
     * @var array An array of classnames to automatically select by namespace
     */
    var $autoselects;

	function modPackageBuilder(&$modx) {
        $this->__construct($modx);
    }
    function __construct(&$modx) {
		$this->modx = $modx;
		$this->modx->loadClass('transport.modTransportVehicle','',false, true);
		$this->modx->loadClass('transport.xPDOTransport', XPDO_CORE_PATH, true, true);

		if (!$workspace= $this->modx->getObject('modWorkspace', array('active' => 1))) {
			$this->modx->log(MODX_LOG_LEVEL_FATAL,"\nYou must have a valid core installation with an active workspace to run the build.\n");
			exit();
		}
		$this->directory = $workspace->get('path') . 'packages/';
        $this->autoselects = array();
    }

	/**
    * Allows for customization of the package workspace.
	*
	* @param integer $workspace_id The ID of the workspace to select.
	* @returns modWorkspace The workspace set, false if invalid.
    */
	function setWorkspace($workspace_id) {
		if (!is_numeric($workspace_id)) return false;
		$workspace = $this->modx->getObject('modWorkspace',$workspace_id);
		if ($workspace == null) return false;

		$this->directory = $workspace->get('path') . 'packages/';
		return $workspace;
	}

	/**
    * Creates a new xPDOTransport package.
	*
    * @param string $name The name of the component the package represents.
    * @param string $version A string representing the version of the package.
    * @param string $release A string describing the specific release of this version of the
    * package.
	* @returns xPDOTransport The xPDOTransport package object.
    */
	function create($name, $version, $release= '') {
        // setup the signature and filename
        $s['name']= $name;
        $s['version']= $version;
        if (!empty($release)) $s['release']= $release;
		$this->signature = $s['name'].'-'.$s['version'].'-'.$s['release'];
		$this->filename = $this->signature . '.transport.zip';


        // remove the package if it's already been made
		if (file_exists($this->directory . $this->filename)) {
			unlink($this->directory . $this->filename);
		}
		if (file_exists($this->directory . $this->signature) && is_dir($this->directory . $this->signature)) {
			if ($cacheManager= $this->modx->getCacheManager()) {
				$cacheManager->deleteTree($this->directory . $this->signature);
			}
		}

        // create the transport package
		$this->package = new xPDOTransport($this->modx, $this->signature, $this->directory);
        $this->modx->log(MODX_LOG_LEVEL_INFO,'Created new transport package with signature: '.$this->signature);

		return $this->package;
	}

    /**
     * Sets the classes that are to automatically be included and built into the
     * package.
     *
     * @param array An array of class names to build in
     */
    function setAutoSelects($classes = array()) {
    	$this->autoselects = $classes;
    }

	/**
    * Creates the modTransportVehicle for the specified object.
	*
	* @param xPDOObject $obj The xPDOObject being abstracted as a vehicle.
	* @param array $attr Attributes for the vehicle.
	* @returns modTransportVehicle The createed modTransportVehicle instance.
    */
	function createVehicle($obj, $attr) {
		if ($this->namespace) {
			$attr['namespace'] = $this->namespace; // package the namespace into the metadata
        }
        $vehicle = new modTransportVehicle($obj, $attr);

        return $vehicle;
	}

    /**
     * Registers a namespace to the transport package. If no namespace is found,
     * will create a namespace.
     *
     * @access public
     * @param string/modNamespace $namespace The modNamespace object or the
     * string name of the namespace
     * @param boolean/array $autoincludes If true, will automatically select
     * relative resources to the namespace.
     * @param boolean $packageNamespace If false, will not package the namespace
     * as a vehicle.
     * @return boolean True if successful.
     */
    function registerNamespace($ns = 'core', $autoincludes = true, $packageNamespace = true) {
    	if (!is_a($ns, 'modNamespace')) {
    		$namespace = $this->modx->getObject('modNamespace',$ns);
            if ($namespace == null) {
            	$namespace = $this->modx->newObject('modNamespace');
                $namespace->set('name',$ns);
            }
    	} else $namespace = $ns;
        $this->namespace = $namespace;

        $this->modx->log(MODX_LOG_LEVEL_INFO,'Registered package namespace as: '.$this->namespace->get('name'));

        // define some basic attributes
        $attributes= array(
            XPDO_TRANSPORT_UNIQUE_KEY => 'name',
            XPDO_TRANSPORT_PRESERVE_KEYS => true,
            XPDO_TRANSPORT_UPDATE_OBJECT => true,
            XPDO_TRANSPORT_RESOLVE_FILES => true,
            XPDO_TRANSPORT_RESOLVE_PHP => true,
        );
        if ($packageNamespace) {
            // create the namespace vehicle
            $v = $this->createVehicle($namespace,$attributes);

            // put it into the package
            if (!$this->putVehicle($v)) return false;
            $this->modx->log(MODX_LOG_LEVEL_INFO,'Packaged namespace "'.$this->namespace->get('name').'" into package.');
        }

        if ($autoincludes == true || (is_array($autoincludes) && !empty($autoincludes))) {
            $this->modx->log(MODX_LOG_LEVEL_INFO,'Packaging in autoincludes: '.print_r($autoincludes,true));
            if (is_array($autoincludes)) {
                // set automatically included packages
                $this->setAutoSelects($autoincludes);
            }

            // grab all related classes that can be auto-packaged and package them in
            foreach ($this->autoselects as $classname) {
                $objs = $this->modx->getCollection($classname,array(
                    'namespace' => $namespace->get('name'),
                ));
                foreach ($objs as $obj) {
                    $v = $this->createVehicle($obj,$attributes);
                    if (!$this->putVehicle($v)) return false;
                }
            }
        }
        return true;
    }

	/**
    * Puts the vehicle into the package.
	*
	* @param modTransportVehicle $vehicle The vehicle to insert into the package.
	* @return boolean True if successful.
    */
    function putVehicle($vehicle) {
		$attr = $vehicle->compile();
		$obj = $vehicle->fetch();
		return $this->package->put($obj,$attr);
	}

	/**
    * Packs the package.
	*
	* @return boolean True if successful.
    */
	function pack() {
		return $this->package->pack();
	}

    /**
     * Retrieves the package signature.
     *
     * @access public
     * @return string The signature of the included package.
     */
    function getSignature() {
    	return $this->package->signature;
    }

    /**
     * Generates the model from a schema.
     *
     * @access public
     * @param string $model The directory path of the model to generate to.
     * @param string $schema The schema file to generate from.
     * @return boolean true if successful
     */
    function buildSchema($model,$schema) {
        $manager= $this->modx->getManager();
        $generator= $manager->getGenerator();
        $generator->parseSchema($schema,$model);
        return true;
    }


    /**
     * Build in the lexicon into the package
     *
     * @access public
     * @return boolean True if successful
     */
    function buildLexicon($path) {
        $invdirs = array('.','..','.svn');
        $i = 0;
        $foci = array();
        $languages = array();
        $entries = array();

        if (!is_dir($path)) {
            $this->modx->log(MODX_LOG_LEVEL_FATAL,'<b>Error</b> - Lexicon path not found: '.$path);
        }

        $this->modx->log(MODX_LOG_LEVEL_INFO,'Auto-building in lexicon from path: '.$path);

        // package in languages
        $attributes= array(
            XPDO_TRANSPORT_UNIQUE_KEY => 'name',
            XPDO_TRANSPORT_PRESERVE_KEYS => true,
            XPDO_TRANSPORT_UPDATE_OBJECT => true,
        );
        foreach ($languages as $language) {
            $vehicle = $this->createVehicle($language,$attributes);
            $this->putVehicle($vehicle);
        }

        // loop through cultures
        $dir = dir($path);
        while (false !== ($culture = $dir->read())) {
            if (in_array($culture,$invdirs)) continue;
            if (!is_dir($path.$culture)) continue;

            $language= $this->modx->getObject('modLexiconLanguage',$culture);
            if ($language == null) {
                $language= $this->modx->newObject('modLexiconLanguage');
                $language->fromArray(array(
                    'name' => $culture,
                ),'',true,true);
                $language->save();
            }
            $languages[$culture]= $language;

            // loop through foci
            $fdir = $path.$culture.'/';
            $fd = dir($fdir);
            while (false !== ($entry = $fd->read())) {
                if (in_array($entry,$invdirs)) continue;
                if (is_dir($fdir.$entry)) continue;

                $foc = str_replace('.inc.php','',$entry);

                $focus = $this->modx->getObject('modLexiconFocus',array(
                    'name' => $foc,
                    'namespace' => $this->namespace->get('name'),
                ));
                if ($focus == null) {
                    $focus= $this->modx->newObject('modLexiconFocus');
                    $focus->fromArray(array (
                      'name' => $foc,
                      'namespace' => $this->namespace->get('name'),
                    ));
                    $focus->save();
                }

                $f = $fdir.$entry;
                $entries = array();
                if (file_exists($f)) {
                    $_lang = array();
                    @include $f;

                    foreach ($_lang as $key => $value) {
                        $entry = $this->modx->newObject('modLexiconEntry');
                        $entry->fromArray(array (
                          'name' => $key,
                          'value' => $value,
                          'focus' => $focus->get('id'),
                          'namespace' => $this->namespace->get('name'),
                          'language' => $culture,
                        ));
                        $entries[] = $entry;
                    }
                }
                $focus->addMany($entries);
                $focus->save();

                $vehicle = $this->createVehicle($focus,array (
                    XPDO_TRANSPORT_PRESERVE_KEYS => false,
                    XPDO_TRANSPORT_UPDATE_OBJECT => true,
                    XPDO_TRANSPORT_UNIQUE_KEY => array ('name', 'namespace'),
                    XPDO_TRANSPORT_RELATED_OBJECTS => true,
                    XPDO_TRANSPORT_RELATED_OBJECT_ATTRIBUTES => array (
                        'modLexiconEntry' => array (
                            XPDO_TRANSPORT_PRESERVE_KEYS => false,
                            XPDO_TRANSPORT_UPDATE_OBJECT => true,
                            XPDO_TRANSPORT_UNIQUE_KEY => array ('name', 'focus', 'namespace', 'language'),
                        ),
                    ),
                ));
                $this->putVehicle($vehicle);

                $focus->remove();
            }
        }
        $dir->close();


        return true;
    }
}
?>