<?php
/*
 * MODx Revolution
 *
 * Copyright 2006, 2007, 2008, 2009 by the MODx Team.
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
 *
 */
$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tstart= $mtime;

error_reporting(E_ALL & ~E_NOTICE);

/**
 * @deprecated 2.0.0
 * For backward compatibility with MODx 0.9.x
 */
define("IN_PARSER_MODE", "true");
/**
 * @deprecated 2.0.0
 * For backward compatibility with MODx 0.9.x
 */
define("IN_MANAGER_MODE", false);

/* [OPTIONAL]: for forcing emulated PDO when required native PDO drivers are not available */
/* define('XPDO_MODE', 2); */

/* define this as true in another entry file, then include this file to simply access the API
 * without executing the MODx request handler */
if (!defined('MODX_API_MODE')) {
    define('MODX_API_MODE', false);
}

/* this can be used to disable caching in MODx absolutely */
$modx_cache_disabled= false;

/* include custom core config and define core path */
@include(dirname(__FILE__) . '/config.core.php');
if (!defined('MODX_CORE_PATH')) define('MODX_CORE_PATH', dirname(__FILE__) . '/core/');

/* include the modX class */
if (!@require_once (MODX_CORE_PATH . "model/modx/modx.class.php")) {
    @include(MODX_CORE_PATH . 'error/unavailable.include.php');
    header('HTTP/1.1 503 Service Unavailable');
    echo "<html><title>Error 503: Site temporarily unavailable</title><body><h1>Error 503</h1><p>Site temporarily unavailable</p></body></html>";
    exit();
}

/* start output buffering */
ob_start();

/* Create an instance of the modX class */
if (empty($options) || !is_array($options)) $options = array();
if (!$modx= new modX('', $options)) {
    @ob_end_flush();
    @include(MODX_CORE_PATH . 'error/unavailable.include.php');
    header('HTTP/1.1 503 Service Unavailable');
    echo "<html><title>Error 503: Site temporarily unavailable</title><body><h1>Error 503</h1><p>Site temporarily unavailable</p></body></html>";
    exit();
}

/* Set the actual start time */
$modx->startTime= $tstart;

/* Set additional logging options including level and target: */
$modx->setLogLevel(MODX_LOG_LEVEL_ERROR);
$modx->setLogTarget('FILE');

/* Set debugging mode (i.e. error_reporting): */
$modx->setDebug(E_ALL & ~E_NOTICE);

/* Initialize the default 'web' context */
$modx->initialize('web');

/* execute the request handler */
if (!MODX_API_MODE) {
    $modx->handleRequest();
}