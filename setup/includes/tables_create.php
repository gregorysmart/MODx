<?php
/**
 * Create a new MODx Revolution repository.
 *
 * @package setup
 */

$results= array ();
$classes= array (
    'modAccessAction',
    'modAccessElement',
    'modAccessMenu',
    'modAccessPolicy',
    'modAccessResource',
    'modAccessResourceGroup',
    'modAccessTemplateVar',
    'modAction',
    'modActiveUser',
    'modCategory',
    'modChunk',
    'modContentType',
    'modContext',
    'modContextResource',
    'modContextSetting',
    'modEvent',
    'modEventLog',
    'modKeyword',
    'modManagerLog',
    'modMenu',
    'modMetatag',
    'modModule',
    'modModuleDepobj',
    'modModuleUserGroup',
    'modPlugin',
    'modPluginEvent',
    'modResource',
    'modResourceGroup',
    'modResourceGroupResource',
    'modResourceKeyword',
    'modResourceMetatag',
    'modSession',
    'modSnippet',
    'modSystemSetting',
    'modTemplate',
    'modTemplateVar',
    'modTemplateVarResource',
    'modTemplateVarResourceGroup',
    'modTemplateVarTemplate',
    'transport.modTransportPackage',
    'transport.modTransportProvider',
    'modUser',
    'modUserProfile',
    'modUserGroup',
    'modUserGroupMember',
    'modUserGroupRole',
    'modUserMessage',
    'modUserRole',
    'modUserSetting',
    'modWorkspace',
    'modNamespace',
    'modLexiconEntry',
    'modLexiconLanguage',
    'modLexiconTopic',
);

$this->xpdo->setPackage('modx', MODX_CORE_PATH . 'model/');
$this->xpdo->getManager();
//$this->xpdo->setLogLevel(XPDO_LOG_LEVEL_INFO);
$connected= $this->xpdo->connect();
$created= false;
if (!$connected) {
    $dsnArray= xPDO :: parseDSN($this->xpdo->config['dsn']);
    $containerOptions['charset']= isset ($install->config['database_charset']) ? $install->config['database_charset'] : 'utf8';
    $containerOptions['collation']= isset ($install->config['database_collation']) ? $install->config['database_collation'] : 'utf8_unicode_ci';
    $created= $this->xpdo->manager->createSourceContainer($dsnArray, $this->xpdo->config['username'], $this->xpdo->config['password'], $containerOptions);
    if (!$created) {
        $results[]= array ('class' => 'failed', 'msg' => '<p class="notok">Error while attempting to create the database.</p>');
    }
    else {
        $connected= $this->xpdo->connect();
    }
    if ($connected) {
        $results[]= array ('class' => 'success', 'msg' => '<p class="ok">Successfully created the database.</p>');
    }
}
if ($connected) {
    $this->xpdo->loadClass('modAccess');
    $this->xpdo->loadClass('modAccessibleObject');
    $this->xpdo->loadClass('modAccessibleSimpleObject');
    $this->xpdo->loadClass('modResource');
    $this->xpdo->loadClass('modElement');
    $this->xpdo->loadClass('modScript');
    $this->xpdo->loadClass('modPrincipal');
    $this->xpdo->loadClass('modUser');
    foreach ($classes as $class) {
        if (!$dbcreated= $this->xpdo->manager->createObjectContainer($class)) {
            $results[]= array ('class' => 'failed', 'msg' => '<p class="notok">Error creating table for class ' . $class . '</p>');
        } else {
            $results[]= array ('class' => 'success', 'msg' => '<p class="ok">Successfully created table for class ' . $class . '</p>');
        }
    }
}
return $results;