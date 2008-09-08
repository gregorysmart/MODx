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
 * Wrapper class for handling the Register
 *
 * @package modx
 * @subpackage registry
 */
class modRegisterHandler {
    /**
     * A reference to the modX instance the register is loaded by.
     * @var modX
     * @access public
     */
    var $modx = null;

    var $oldLogTarget;

    function modRegisterHandler(&$modx) {
    	$this->__construct($modx);
    }

    function __construct(&$modx) {
    	$this->modx = $modx;
    }

    function load($register,$topic) {
    	$this->oldLogTarget = $this->modx->logTarget;
        if (isset($register) && !empty($register) && preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $register)) {
            if (isset($topic) && !empty($topic)) {
                $register = trim($register);
                $topic = trim($topic);

                if ($this->modx->getService('registry', 'registry.modRegistry')) {
                    $this->modx->registry->addRegister($register, 'registry.modFileRegister', array('directory' => $register));
                    if ($this->modx->registry->$register->connect()) {
                        $this->modx->registry->$register->subscribe($topic);
                        $this->modx->registry->$register->setCurrentTopic($topic);
                        $this->modx->setLogTarget($this->modx->registry->$register);
                        $this->modx->setLogLevel(XPDO_LOG_LEVEL_INFO);
                    }
                }
            }
        }

        return $register;
    }

    function unload() {
        if ($this->oldLogTarget != null) {
            $this->modx->setLogLevel(XPDO_LOG_LEVEL_ERROR);
            $this->modx->logTarget = $this->oldLogTarget;
        }
    }
}