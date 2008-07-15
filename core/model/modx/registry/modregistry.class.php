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
 * Represents a collection of message registers.
 *
 * A register can consist of loggable audit events, error events, debug events,
 * and any other messages that may be sent to a message queue and later
 * retrieved or redirected to some other output source.  Some key features will
 * include:
 *
 * -Logging of registry transactions to file or DB
 * -Tracking progress of asynchonous processes
 * -Can serve as a generic message queue, where MODx elements can register new
 * messages or grab the latest messages via scheduled or ad hoc requests.
 *
 * @todo Encapsulate all debugging, error handling, error reporting, and audit
 * logging features into appropriate registers.
 *
 * @package modx
 * @subpackage registry
 */
class modRegistry {
    /**
     * A reference to the modX instance the registry is loaded by.
     * @var modX
     * @access public
     */
    var $modx = null;
    /**
     * An array of global options applied to the registry.
     * @var array
     * @access protected
     */
    var $_options = array();
    /**
     * An array of register keys that are reserved from use.
     * @var array
     * @access protected
     */
    var $_invalidKeys = array(
        'modx',
    );
    /**
     * An array of MODx registers managed by the registry.
     * @var array
     * @access private
     */
    var $_registers = array();

    /**#@+
     * Construct a new registry instance.
     *
     * @param modX &$modx A reference to a modX instance.
     * @param array $options Optional array of registry options.
     */
    function modRegistry(& $modx, $options = array()) {
        $this->__construct($modx, $options);
    }
    /**@ignore*/
    function __construct(& $modx, $options = array()) {
        $this->modx =& $modx;
        $this->_options = $options;
    }
    /**#@-*/

    /**
     * Add a modRegister instance to the registry.
     *
     * Once a register is added, it is available directly from this registry
     * instance by the key provided, e.g. $registry->key.
     *
     * @param string $key A unique name for the register in the registry. Must
     * be a valid PHP variable string.
     * @param string $class The actual modRegister derivative which implements
     * the register functionality.
     * @param array $options An optional array of register options.
     */
    function addRegister($key, $class, $options = array()) {
        if (!in_array($key, $this->_invalidKeys) && substr($key, 0, 1) !== '_') {
            $this->_registers[$key] = $class;
            $this->$key = $this->_initRegister($class, $options);
        }
    }

    /**
     * Remove a modRegister instance from the registry.
     *
     * @param string $key The unique name of the register to remove.
     */
    function removeRegister($key) {
        if (!in_array($key, $this->_invalidKeys) && substr($key, 0, 1) !== '_') {
            $this->_registers[$key] = null;
            $this->$key = null;
        }
    }

    /**
     * Initialize a register within the registry.
     *
     * @access protected
     * @param string $class The class of the modRegister implementation to
     * initialize.
     * @param array $options An optional array of register options.
     * @return modRegister The register instance.
     */
    function _initRegister($class, $options = array()) {
        $register = null;
        if ($className = $this->modx->loadClass($class, '', false, true)) {
            $register = new $className($this->modx, $options);
        }
        return $register;
    }
}
?>