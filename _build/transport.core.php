<?php
/**
 * Builds the MODx core transport package.
 *
 * @package modx
 * @subpackage build
 */
$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
/* get rid of time limit */
set_time_limit(0);

error_reporting(E_ALL); ini_set('display_errors',true);

/* override with your own defines here (see build.config.sample.php) */
$f = dirname(__FILE__) . '/build.config.php';
if (file_exists($f)) {
    @require_once $f;
} else {
    die('build.config.php was not found. Please make sure you have created one using the template of build.config.sample.php.');
}
unset($f);

if (!defined('MODX_CORE_PATH'))
    define('MODX_CORE_PATH', dirname(dirname(__FILE__)) . '/core/');
require_once MODX_CORE_PATH . 'xpdo/xpdo.class.php';

/* define the MODX path constants necessary for core installation */
if (!defined('MODX_BASE_PATH'))
    define('MODX_BASE_PATH', dirname(dirname(__FILE__)) . '/');
if (!defined('MODX_MANAGER_PATH'))
    define('MODX_MANAGER_PATH', MODX_BASE_PATH . 'manager/');
if (!defined('MODX_CONNECTORS_PATH'))
    define('MODX_CONNECTORS_PATH', MODX_BASE_PATH . 'connectors/');
if (!defined('MODX_ASSETS_PATH'))
    define('MODX_ASSETS_PATH', MODX_BASE_PATH . 'assets/');

/* define the connection variables */
if (!defined('XPDO_DSN'))
    define('XPDO_DSN', 'mysql:host=localhost;dbname=modx;charset=utf8');
if (!defined('XPDO_DB_USER'))
    define('XPDO_DB_USER', 'root');
if (!defined('XPDO_DB_PASS'))
    define('XPDO_DB_PASS', '');
if (!defined('XPDO_TABLE_PREFIX'))
    define('XPDO_TABLE_PREFIX', 'modx_');

/* instantiate xpdo instance */
$xpdo = new xPDO(XPDO_DSN, XPDO_DB_USER, XPDO_DB_PASS,
    array (
        XPDO_OPT_TABLE_PREFIX => XPDO_TABLE_PREFIX,
        XPDO_OPT_CACHE_PATH => MODX_CORE_PATH . 'cache/',
    ),
    array (
        PDO_ATTR_ERRMODE => PDO_ERRMODE_SILENT,
        PDO_ATTR_PERSISTENT => false,
        PDO_MYSQL_ATTR_USE_BUFFERED_QUERY => true
    )
);
$cacheManager= $xpdo->getCacheManager();
$xpdo->setLogLevel(XPDO_LOG_LEVEL_ERROR);
$xpdo->setLogTarget('ECHO');

$xpdo->loadClass('transport.xPDOTransport', XPDO_CORE_PATH, true, true);
$packageDirectory = MODX_CORE_PATH . 'packages/';

/* remove pre-existing package files and directory */
if (file_exists($packageDirectory . 'core.transport.zip')) {
    unlink($packageDirectory . 'core.transport.zip');
}
if (file_exists($packageDirectory . 'core') && is_dir($packageDirectory . 'core')) {
    $cacheManager->deleteTree($packageDirectory . 'core', true);
}

/* create core transport package */
$package = new xPDOTransport($xpdo, 'core', $packageDirectory);

$xpdo->setPackage('modx', MODX_CORE_PATH . 'model/');
$xpdo->loadClass('modAccess');
$xpdo->loadClass('modAccessibleObject');
$xpdo->loadClass('modAccessibleSimpleObject');
$xpdo->loadClass('modPrincipal');

/* modWorkspace */
$collection = array ();
$collection['1'] = $xpdo->newObject('modWorkspace');
$collection['1']->fromArray(array (
    'id' => 1,
    'name' => 'Default MODx workspace',
    'active' => 1,
), '', true, true);
$attributes = array (
    XPDO_TRANSPORT_PRESERVE_KEYS => true,
    XPDO_TRANSPORT_UPDATE_OBJECT => false,
);
foreach ($collection as $c) {
    $package->put($c, $attributes);
}
unset ($collection, $c, $attributes);

/* modx wtf provisioner */
$collection = array ();
$collection['1'] = $xpdo->newObject('transport.modTransportProvider');
$collection['1']->fromArray(array (
    'id' => 1,
    'name' => 'MODx Web Transport Facility',
    'description' => 'The official MODx transport facility for 3rd party components.',
    'service_url' => 'http://wtf.modxcms.com/repos/addons.js',
    'created' => strftime('%Y-%m-%d %H:%M:%S'),
), '', true, true);
$attributes = array (
    XPDO_TRANSPORT_PRESERVE_KEYS => false,
    XPDO_TRANSPORT_UPDATE_OBJECT => true,
    XPDO_TRANSPORT_UNIQUE_KEY => array ('name'),
);
foreach ($collection as $c) {
    $package->put($c, $attributes);
}
unset ($collection, $c, $attributes);

/* modAction */
$collection= array();
include dirname(__FILE__).'/data/transport.core.actions.php';
$attributes = array (
    XPDO_TRANSPORT_PRESERVE_KEYS => true,
    XPDO_TRANSPORT_UPDATE_OBJECT => true,
);
foreach ($collection as $c) {
    $package->put($c, $attributes);
}
unset ($collection, $c, $attributes);

/* modMenu */
$collection= array();
include dirname(__FILE__).'/data/transport.core.menus.php';
$attributes = array (
    XPDO_TRANSPORT_PRESERVE_KEYS => true,
    XPDO_TRANSPORT_UPDATE_OBJECT => true,
);
foreach ($collection as $c) {
    $package->put($c, $attributes);
}
unset ($collection, $c, $attributes);

/* modContentTypes */
$collection = array ();
include dirname(__FILE__).'/data/transport.core.content_types.php';
$attributes = array (
    XPDO_TRANSPORT_PRESERVE_KEYS => true,
    XPDO_TRANSPORT_UPDATE_OBJECT => false,
);
foreach ($collection as $c) {
    $package->put($c, $attributes);
}
unset ($collection, $c, $attributes);

/* modContext = web */
$c = $xpdo->newObject('modContext');
$c->fromArray(array (
    'key' => 'web',
    'description' => 'The default front-end context for your web site.',
), '', true, true);
$attributes = array (
    XPDO_TRANSPORT_PRESERVE_KEYS => true,
    XPDO_TRANSPORT_UPDATE_OBJECT => false
);
$attributes['resolve'][] = array (
    'type' => 'file',
    'source' => MODX_BASE_PATH . 'index.php',
    'target' => "return MODX_BASE_PATH;",
);
$attributes['resolve'][] = array (
    'type' => 'file',
    'source' => MODX_BASE_PATH . 'ht.access',
    'target' => "return MODX_BASE_PATH;",
);
$attributes['resolve'][] = array (
    'type' => 'php',
    'source' => dirname(__FILE__) . '/resolvers/resolve.core.php',
    'target' => "return MODX_BASE_PATH . 'config.core.php';",
);
$package->put($c, $attributes);
unset ($c, $attributes);

/* modContext = mgr */
$c = $xpdo->newObject('modContext');
$c->fromArray(array (
    'key' => 'mgr',
    'description' => 'The default manager or administration context for content management activity.',
), '', true, true);
$attributes = array (
    XPDO_TRANSPORT_PRESERVE_KEYS => true,
    XPDO_TRANSPORT_UPDATE_OBJECT => false
);
$attributes['resolve'][] = array (
    'type' => 'file',
    'source' => MODX_BASE_PATH . 'manager/assets',
    'target' => "return MODX_MANAGER_PATH;",
);
$attributes['resolve'][] = array (
    'type' => 'file',
    'source' => MODX_BASE_PATH . 'manager/controllers',
    'target' => "return MODX_MANAGER_PATH;",
);
$attributes['resolve'][] = array (
    'type' => 'file',
    'source' => MODX_BASE_PATH . 'manager/templates',
    'target' => "return MODX_MANAGER_PATH;",
);
$attributes['resolve'][] = array (
    'type' => 'file',
    'source' => MODX_BASE_PATH . 'manager/ht.access',
    'target' => "return MODX_MANAGER_PATH;",
);
$attributes['resolve'][] = array (
    'type' => 'file',
    'source' => MODX_BASE_PATH . 'manager/index.php',
    'target' => "return MODX_MANAGER_PATH;",
);
$attributes['resolve'][] = array (
    'type' => 'php',
    'source' => dirname(__FILE__) . '/resolvers/resolve.core.php',
    'target' => "return MODX_MANAGER_PATH . 'config.core.php';",
);
$package->put($c, $attributes);
unset ($c, $attributes);

/* modContext = connector */
$c = $xpdo->newObject('modContext');
$c->fromArray(array (
    'key' => 'connector',
    'description' => 'A context of AJAX processors for interacting with your MODx or custom models.',

), '', true, true);
$attributes = array (
    XPDO_TRANSPORT_PRESERVE_KEYS => true,
    XPDO_TRANSPORT_UPDATE_OBJECT => false
);
$attributes['resolve'][] = array (
    'type' => 'file',
    'source' => MODX_BASE_PATH . 'connectors/browser',
    'target' => "return MODX_CONNECTORS_PATH;",

);
$attributes['resolve'][] = array (
    'type' => 'file',
    'source' => MODX_BASE_PATH . 'connectors/context',
    'target' => "return MODX_CONNECTORS_PATH;",

);
$attributes['resolve'][] = array (
    'type' => 'file',
    'source' => MODX_BASE_PATH . 'connectors/element',
    'target' => "return MODX_CONNECTORS_PATH;",

);
$attributes['resolve'][] = array (
    'type' => 'file',
    'source' => MODX_BASE_PATH . 'connectors/layout',
    'target' => "return MODX_CONNECTORS_PATH;",

);
$attributes['resolve'][] = array (
    'type' => 'file',
    'source' => MODX_BASE_PATH . 'connectors/resource',
    'target' => "return MODX_CONNECTORS_PATH;",

);
$attributes['resolve'][] = array (
    'type' => 'file',
    'source' => MODX_BASE_PATH . 'connectors/security',
    'target' => "return MODX_CONNECTORS_PATH;",

);
$attributes['resolve'][] = array (
    'type' => 'file',
    'source' => MODX_BASE_PATH . 'connectors/system',
    'target' => "return MODX_CONNECTORS_PATH;",

);
$attributes['resolve'][] = array (
    'type' => 'file',
    'source' => MODX_BASE_PATH . 'connectors/workspace',
    'target' => "return MODX_CONNECTORS_PATH;",

);
$attributes['resolve'][] = array (
    'type' => 'file',
    'source' => MODX_BASE_PATH . 'connectors/index.php',
    'target' => "return MODX_CONNECTORS_PATH;",

);
$attributes['resolve'][] = array (
    'type' => 'file',
    'source' => MODX_BASE_PATH . 'connectors/lang.js.php',
    'target' => "return MODX_CONNECTORS_PATH;",

);
$attributes['resolve'][] = array (
    'type' => 'php',
    'source' => dirname(__FILE__) . '/resolvers/resolve.core.php',
    'target' => "return MODX_CONNECTORS_PATH . 'config.core.php';",
);
$package->put($c, $attributes);
unset ($c, $attributes);

/* modEvent collection */
$collection = array ();
include dirname(__FILE__).'/data/transport.core.events.php';
$attributes = array (
    XPDO_TRANSPORT_PRESERVE_KEYS => true,
    XPDO_TRANSPORT_UPDATE_OBJECT => true
);
foreach ($collection as $c) {
    $package->put($c, $attributes);
}
unset ($c, $attributes);

/* modSystemSetting collection */
$collection = array ();
include dirname(__FILE__).'/data/transport.core.system_settings.php';
$attributes= array (
    XPDO_TRANSPORT_PRESERVE_KEYS => true
);
foreach ($collection as $c) {
    switch ($c->get('key')) {
        case 'filemanager_path' :
        case 'rb_base_dir' :
        case 'rb_base_url' :
        case 'site_id' :
            $attributes[XPDO_TRANSPORT_UPDATE_OBJECT]= true;
            $c->set('value', '');
            break;
        case 'session_cookie_path' :
            $attributes[XPDO_TRANSPORT_UPDATE_OBJECT]= true;
            $c->set('value', '/');
            break;
        default :
            $attributes[XPDO_TRANSPORT_UPDATE_OBJECT]= false;
            break;
    }
    $package->put($c, $attributes);
}
unset ($collection, $c, $attributes);

/* modContextSetting collection */
$collection = array ();
include dirname(__FILE__).'/data/transport.core.context_settings.php';
$attributes= array(
    XPDO_TRANSPORT_PRESERVE_KEYS => true,
    XPDO_TRANSPORT_UPDATE_OBJECT => false,
);
foreach ($collection as $c) {
    $package->put($c, $attributes);
}
unset ($collection, $c, $attributes);

/* modUserGroup */
$collection = array ();
include dirname(__FILE__).'/data/transport.core.usergroups.php';
$attributes = array (
    XPDO_TRANSPORT_PRESERVE_KEYS => true,
    XPDO_TRANSPORT_UPDATE_OBJECT => false,
);
foreach ($collection as $c) {
    $package->put($c, $attributes);
}
unset ($collection, $c, $attributes);

/* modUserGroupRole */
$collection = array ();
include dirname(__FILE__).'/data/transport.core.usergrouproles.php';
$attributes = array (
    XPDO_TRANSPORT_PRESERVE_KEYS => true,
    XPDO_TRANSPORT_UPDATE_OBJECT => false,
);
foreach ($collection as $c) {
    $package->put($c, $attributes);
}
unset ($collection, $c, $attributes);

/* modAccessPolicy */
$collection = array ();
include dirname(__FILE__).'/data/transport.core.accesspolicies.php';
$attributes = array (
    XPDO_TRANSPORT_PRESERVE_KEYS => true,
    XPDO_TRANSPORT_UPDATE_OBJECT => true,
);
foreach ($collection as $c) {
    $package->put($c, $attributes);
}
unset ($collection, $c, $attributes);

/* Lexicon stuff */
$entries = array ();
$topics = array ();
$languages = array ();
$namespace = $xpdo->newObject('modNamespace');
$namespace->set('name','core');
$namespace->set('path','{core_path}');
$package->put($namespace,array(
    XPDO_TRANSPORT_PRESERVE_KEYS => true,
    XPDO_TRANSPORT_UPDATE_OBJECT => true,
));
include dirname(__FILE__).'/data/transport.core.lexicon.php';
foreach ($topics as $t) {
    $package->put($t,array (
        XPDO_TRANSPORT_PRESERVE_KEYS => false,
        XPDO_TRANSPORT_UPDATE_OBJECT => true,
        XPDO_TRANSPORT_UNIQUE_KEY => array ('name', 'namespace'),
        XPDO_TRANSPORT_RELATED_OBJECTS => true,
        XPDO_TRANSPORT_RELATED_OBJECT_ATTRIBUTES => array (
            'modLexiconEntry' => array (
                XPDO_TRANSPORT_PRESERVE_KEYS => false,
                XPDO_TRANSPORT_UPDATE_OBJECT => true,
                XPDO_TRANSPORT_UNIQUE_KEY => array ('name', 'topic', 'language'),
            )
        )
    ));
}
foreach ($languages as $l) {
    $package->put($l,array (
        XPDO_TRANSPORT_PRESERVE_KEYS => true,
        XPDO_TRANSPORT_UPDATE_OBJECT => true,
    ));
}
unset ($entries, $languages, $topics, $c, $t, $l);

/* modAccessContext */
$collection = array ();
include dirname(__FILE__).'/data/transport.core.access_contexts.php';
$attributes = array (
    XPDO_TRANSPORT_PRESERVE_KEYS => true,
    XPDO_TRANSPORT_UPDATE_OBJECT => false,
);
foreach ($collection as $c) {
    $package->put($c, $attributes);
}
unset ($collection, $c, $attributes);

$package->pack();

$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tend = $mtime;
$totalTime = ($tend - $tstart);
$totalTime = sprintf("%2.4f s", $totalTime);

echo "\nExecution time: {$totalTime}\n";

exit ();