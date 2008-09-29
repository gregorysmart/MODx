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
 * Abstracts the vehicle construct for package building.
 *
 * @package modx
 * @subpackage transport
 */
class modTransportVehicle {
    /**
     * @var array The collection of attributes to attach to the vehicle.
     */
    var $attributes;
	/**
    * @var array The collection of dependencies to resolve post-save.
    */
	var $resolves;
	/**
    * @var string The collection of dependences to validate against for upgrade processes.
    */
	var $validates;
	/**
    * @var xPDOObject The actual object that the vehicle represents.
    */
	var $obj;

	function modTransportVehicle($obj, $attr = array()) {
        $this->__construct($obj, $attr);
    }
    function __construct($obj, $attr = array()) {
		$this->obj = $obj;
        $this->attributes = $attr;
		$this->validates = array();
		$this->resolves = array();
    }

	/**
    * Adds a pre-creation validator to the vehicle.
	*
	* @param string $type The type of validator (php,file,etc)
	* @param array $options An array of options for the validator.
    */
	function validate($type,$options) {
		$options['type'] = $type;
		array_push($this->validates,$options);
		return $options;
	}

	/**
    * Adds a post-save resolver to the vehicle.
	*
	* @param string $type The type of resolver (php,file,etc)
	* @param array $options An array of options for the resolver.
    */
	function resolve($type,$options) {
		$options['type'] = $type;
		array_push($this->resolves,$options);
		return $options;
	}

	/**
    * Compiles the attributes array to pass on to the modPackageBuilder instance.
    */
	function compile() {
		$attributes = array_merge($this->attributes, array(
			'resolve' => empty($this->resolves) ? NULL : $this->resolves,
			'validate' => empty($this->validates) ? NULL : $this->validates,
		));
		return $attributes;
	}

	/**
    * Returns the xPDOObject associated with the vehicle.
    */
	function fetch() {
		return $this->obj;
	}
}