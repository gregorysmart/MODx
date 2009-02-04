<?php
/**
 * Specific upgrades for Revolution 2.0.0-beta-1
 *
 * @package setup
 */
/* handle new class creation */
$classes = array(
    'modElementPropertySet',
    'modPropertySet'
);
if (!empty($classes)) {
    $this->createTable($classes);
}
unset($classes);

/* remove some menu items that are deprecated from alpha */
/* first, remove the dashes from revo-alpha */
$dashes = $this->install->xpdo->getCollection('modMenu',array( 'text' => '-'));
foreach ($dashes as $dash) {
    $dash->remove();
}
unset($dashes,$dash);

/* remove link to home */
$home = $this->install->xpdo->getObject('modMenu',array('text' => 'home'));
if ($home != null) $home->remove();
unset($home);

/* remove logout link */
$logout = $this->install->xpdo->getObject('modMenu',array('text' => 'logout'));
if ($logout != null) $logout->remove();
unset($logout);

/* remove no-longer-valid lexicon topics */
$roletopic = $this->install->xpdo->getObject('modLexiconTopic',array(
    'name' => 'role',
));
if ($roletopic != null) { $roletopic->remove(); }
unset($roletopic);

/* add description field to menus */
$class = 'modMenu';
$table = $this->install->xpdo->getTableName($class);
$sql = "ALTER TABLE {$table} ADD COLUMN `description` VARCHAR(255) NOT NULL AFTER `text`";
$description = 'Added new column `description` to '.$table.'.';
$this->processResults($class, $description, $sql);
unset($class,$description,$sql,$table);

/* change modAction context_key to namespace */
$class = 'modAction';
$table = $this->install->xpdo->getTableName($class);
$sql = "ALTER TABLE `modx_actions` CHANGE `context_key` `namespace` VARCHAR( 100 ) NOT NULL DEFAULT 'core'";
$description = 'Changed column `context_key` to `namespace` on '.$table.'.';
$this->processResults($class,$description,$sql);
unset($class,$description,$sql,$table);


/* fix not null `properties` columns on all element (and property set) tables to allow null */
$elements = array (
    'modChunk',
    'modPlugin',
    'modSnippet',
    'modTemplate',
    'modTemplateVar',
    'modPropertySet',
);
foreach ($elements as $class) {
    $table = $this->install->xpdo->getTableName($class);
    $sql = "ALTER TABLE {$table} CHANGE `properties` `properties` TEXT NULL";
    $description = 'Fixing allow null for ' . $class . '.`properties`.';
    $this->processResults($class, $description, $sql);
}
unset($elements,$class,$description,$sql,$table);