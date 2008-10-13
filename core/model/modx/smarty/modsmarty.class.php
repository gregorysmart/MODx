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
 * @subpackage smarty
 */
include_once (strtr(realpath(dirname(__FILE__)) . '/../../smarty/Smarty.class.php', '\\', '/'));
/**
 * An extension of the Smarty class for use with modX.
 *
 * Automatically sets appropriate configuration variables for Smarty in
 * the MODx context.
 * @package modx
 * @subpackage smarty
 */
class modSmarty extends Smarty {
    var $modx= null;
	var $smarty;
    var $_blocks;
    var $_derived;

	function modSmarty(& $modx, $params= array ()) {
		$this->__construct($modx, $params);
	}

	function __construct(& $modx, $params= array ()) {
		parent :: Smarty();
        $this->modx= & $modx;

		/* set up configuration variables for Smarty. */
        $this->template_dir = $modx->config['manager_path'] . 'templates/';
        $this->compile_dir  = $modx->cachePath . 'smarty/';
        $this->config_dir   = $modx->config['core_path'] . 'model/smarty/configs';
        $this->plugins_dir  = array(
            $this->modx->config['core_path'] . 'model/smarty/plugins',
        );
        $this->caching = false;

        foreach ($params as $paramKey => $paramValue) {
            $this->$paramKey= $paramValue;
        }

        $this->compile_dir= $this->modx->cachePath . 'smarty/';
        if (!is_dir($this->compile_dir)) {
            $this->modx->getCacheManager();
            $this->modx->cacheManager->writeTree($this->compile_dir);
        }

		$this->assign('app_name','MODx');

		$this->_blocks = array();
		$this->_derived = null;
	}

    function setCachePath($path = '') {
    	$path = $this->modx->cachePath.$path;
        if (!is_dir($path)) {
            $this->modx->getCacheManager();
            $this->modx->cacheManager->writeTree($path);
        }
        $this->modx->smarty->compile_dir = $path;
    }

    function setTemplatePath($path = '') {
        if ($path == '') return false;

        $this->template_dir = $path;
    }

	function display($resource_name) {
		echo $this->fetch($resource_name);
	}

	function fetch($resource_name) {
		$ret = parent::fetch($resource_name);
		while ($resource = $this->_derived) {
			$this->_derived = null;
			$ret = parent::fetch($resource);
		}
		return $ret;
	}
}