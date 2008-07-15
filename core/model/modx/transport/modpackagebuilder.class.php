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

	function modPackageBuilder(&$modx) {
        $this->__construct($modx);
    }
    function __construct(&$modx) {
		$this->modx = $modx;
		$this->modx->loadClass('transport.modTransportVehicle','',false, true);
		$this->modx->loadClass('transport.xPDOTransport', XPDO_CORE_PATH, true, true);

		if (!$workspace= $this->modx->getObject('modWorkspace', array('active' => 1))) {
			echo "\nYou must have a valid core installation with an active workspace to run the build.\n";
			exit();
		}
		$this->directory = $workspace->get('path') . 'packages/';
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
		if ($workspace == NULL) return false;

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
		$s['name']= $name;
        $s['version']= $version;
        if (!empty($release)) $s['release']= $release;
		$this->signature = implode('-',$s);
		$this->filename = $this->signature . '.transport.zip';
		if (file_exists($this->directory . $this->filename)) {
			unlink($this->directory . $this->filename);
		}
		if (file_exists($this->directory . $this->signature) && is_dir($this->directory . $this->signature)) {
			if ($cacheManager= $this->modx->getCacheManager()) {
				$cacheManager->deleteTree($this->directory . $this->signature);
			}
		}
		$this->package = new xPDOTransport($this->modx, $this->signature, $this->directory);
		return $this->package;
	}

	/**
    * Creates the modTransportVehicle for the specified object.
	*
	* @param xPDOObject $obj The xPDOObject being abstracted as a vehicle.
	* @param array $attr Attributes for the vehicle.
	* @returns modTransportVehicle The createed modTransportVehicle instance.
    */
	function createVehicle($obj, $attr) {
		return new modTransportVehicle($obj, $attr);
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
}
?>