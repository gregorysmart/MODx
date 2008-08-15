<?php
/**
 * Contains logic to upgrade existing Revolution repositories only.
 *
 * WARNING: Do not attempt to upgrade MODx Evolution releases using this script.
 *
 * @todo Make upgrades smarter by detecting specific versions and/or providing
 * auto-migration capabilities via xPDO (i.e. detect differences between actual
 * table and current map).
 *
 * @package setup
 */

$results = array ();

// put new classes here to have tables created on upgrade (if they don't exist)
$classes = array (
    'modAccessAction',
    'modAccessElement',
    'modAccessMenu',
    'modAccessPolicy',
    'modAccessResource',
    'modAccessResourceGroup',
    'modAccessTemplateVar',
    'modAction',
    'modContextResource',
    'modContentType',
    'modManagerLog',
    'modMenu',
    'modWorkspace',
    'transport.modTransportPackage',
    'transport.modTransportProvider',
    'modNamespace',
    'modLexiconEntry',
    'modLexiconLanguage',
    'modLexiconFocus',
);

$this->xpdo->setPackage('modx', MODX_CORE_PATH . 'model/');
$connected = $this->xpdo->connect();
if ($connected) {
    $this->xpdo->getManager();
    foreach ($classes as $class) {
        if (!$dbcreated = $this->xpdo->manager->createObjectContainer($class)) {
            $results[] = array (
                'class' => 'failed',
                'msg' => '<p class="notok">Error creating table for class ' . $class . '</p>'
            );
        } else {
            $results[] = array (
                'class' => 'success',
                'msg' => '<p class="ok">Successfully created table for class ' . $class . '</p>'
            );
        }
    }

    // add table structure changes here for upgrades to previous Revolution installations
    $class = 'modResource';
    $table = $this->xpdo->getTableName($class);
    $sql = "ALTER TABLE {$table} DROP INDEX `content_ft_idx`";
    $description = 'Removed full-text index `content_ft_idx`.';
    $removedOldFullTextIndex = processResults($this->xpdo,$results,$class,$description,$sql);

    $sql = "ALTER TABLE {$table} ADD COLUMN `content_type` INT(11) unsigned NOT NULL DEFAULT 0 ";
    $description = 'Added `content_type` column.';
    processResults($this->xpdo,$results,$class,$description,$sql);

    if ($removedOldFullTextIndex) {
        $sql = "ALTER TABLE {$table} ADD FULLTEXT INDEX `content_ft_idx` (`pagetitle`, `longtitle`, `description`, `introtext`, `content`)";
        $description = 'Added new `content_ft_idx` full-text index on the fields `pagetitle`, `longtitle`, `description`, `introtext`, `content`.';
        processResults($this->xpdo,$results,$class,$description,$sql);
    }

    $class = 'modUserGroup';
    $table = $this->xpdo->getTableName($class);
    $description = 'Added new column `parent`.';
    $sql = "ALTER TABLE {$table} ADD COLUMN `parent` INT(11) unsigned NOT NULL DEFAULT 0";
    processResults($this->xpdo,$results,$class,$description,$sql);

    $description = 'Added new index on `parent`.';
    $sql = "ALTER TABLE {$table} ADD INDEX `parent` (`parent`)";
    processResults($this->xpdo,$results,$class,$description,$sql);

    $class = 'modUserGroupMember';
    $table = $this->xpdo->getTableName($class);
    $description = 'Added new column `role`.';
    $sql = "ALTER TABLE {$table} ADD COLUMN `role` INT(10) unsigned NOT NULL DEFAULT 0";
    processResults($this->xpdo,$results,$class,$description,$sql);

    $description = 'Added new index on `role`.';
    $sql = "ALTER TABLE {$table} ADD INDEX `role` (`role`)";
    processResults($this->xpdo,$results,$class,$description,$sql);

    $class = 'modModule';
    $table = $this->xpdo->getTableName($class);
    $description = 'Added disabled field missing in early Revolution releases';
    $sql = "ALTER TABLE {$table} ADD COLUMN `disabled` TINYINT(1) unsigned NOT NULL DEFAULT 0 AFTER `category`";
    processResults($this->xpdo,$results,$class,$description,$sql);

    $class = 'modUser';
    $table = $this->xpdo->getTableName($class);
    $description = 'Added cachepwd field missing in early Revolution releases';
    $sql = "ALTER TABLE {$table} ADD COLUMN `cachepwd` VARCHAR(100) NOT NULL DEFAULT '' AFTER `password`";
    processResults($this->xpdo,$results,$class,$description,$sql);

    $class = 'modActiveUser';
    $table = $this->xpdo->getTableName($class);
    $description = 'Modified modActiveUser `action` field to allow longer action labels';
    $sql = "ALTER TABLE {$table} MODIFY COLUMN `action` VARCHAR(255)";
    processResults($this->xpdo,$results,$class,$description,$sql);

    $class = 'modAction';
    $table = $this->xpdo->getTableName($class);
    $description = 'Added modAction `lang_foci` field.';
    $sql = "ALTER TABLE {$table} ADD COLUMN `lang_foci` TEXT AFTER `haslayout`";
    processResults($this->xpdo,$results,$class,$description,$sql);

    $class = 'modSystemSetting';
    $table = $this->xpdo->getTableName($class);
    $description = 'Changed modSystemSetting `setting_name` field to `key`.';
    $sql = "ALTER TABLE {$table} CHANGE COLUMN `setting_name` `key` VARCHAR(50) NOT NULL";
    processResults($this->xpdo,$results,$class,$description,$sql);
    $description = 'Changed modSystemSetting `setting_value` field to `value`.';
    $sql = "ALTER TABLE {$table} CHANGE COLUMN `setting_value` `value` TEXT NOT NULL";
    processResults($this->xpdo,$results,$class,$description,$sql);
    $description = 'Added modSystemSetting `xtype`.';
    $sql = "ALTER TABLE {$table} ADD COLUMN `xtype` VARCHAR(75) NOT NULL DEFAULT 'textfield'";
    processResults($this->xpdo,$results,$class,$description,$sql);
    $description = 'Added modSystemSetting `namespace`.';
    $sql = "ALTER TABLE {$table} ADD COLUMN `namespace` VARCHAR(40) NOT NULL DEFAULT 'core'";
    processResults($this->xpdo,$results,$class,$description,$sql);
    $description = 'Added modSystemSetting `editedon`.';
    $sql = "ALTER TABLE {$table} ADD COLUMN `editedon` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";
    processResults($this->xpdo,$results,$class,$description,$sql);


    $class = 'modContextSetting';
    $table = $this->xpdo->getTableName($class);
    $description = 'Added modContextSetting `xtype`.';
    $sql = "ALTER TABLE {$table} ADD COLUMN `xtype` VARCHAR(75) NOT NULL DEFAULT 'textfield'";
    processResults($this->xpdo,$results,$class,$description,$sql);
    $description = 'Added modContextSetting `namespace`.';
    $sql = "ALTER TABLE {$table} ADD COLUMN `namespace` VARCHAR(40) NOT NULL DEFAULT 'core'";
    processResults($this->xpdo,$results,$class,$description,$sql);
    $description = 'Added modContextSetting `editedon`.';
    $sql = "ALTER TABLE {$table} ADD COLUMN `editedon` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";
    processResults($this->xpdo,$results,$class,$description,$sql);

    $class = 'modUserSetting';
    $table = $this->xpdo->getTableName($class);
    $description = 'Changed modUserSetting `setting_name` field to `key`.';
    $sql = "ALTER TABLE {$table} CHANGE COLUMN `setting_name` `key` VARCHAR(50) NOT NULL";
    processResults($this->xpdo,$results,$class,$description,$sql);
    $description = 'Changed modUserSetting `setting_value` field to `value`.';
    $sql = "ALTER TABLE {$table} CHANGE COLUMN `setting_value` `value` TEXT NOT NULL";
    processResults($this->xpdo,$results,$class,$description,$sql);
    $description = 'Added modUserSetting `xtype`.';
    $sql = "ALTER TABLE {$table} ADD COLUMN `xtype` VARCHAR(75) NOT NULL DEFAULT 'textfield'";
    processResults($this->xpdo,$results,$class,$description,$sql);
    $description = 'Added modUserSetting `namespace`.';
    $sql = "ALTER TABLE {$table} ADD COLUMN `namespace` VARCHAR(40) NOT NULL DEFAULT 'core'";
    processResults($this->xpdo,$results,$class,$description,$sql);
    $description = 'Added modUserSetting `editedon`.';
    $sql = "ALTER TABLE {$table} ADD COLUMN `editedon` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";
    processResults($this->xpdo,$results,$class,$description,$sql);


    $class = 'modManagerLog';
    $table = $this->xpdo->getTableName($class);
    $description = 'Changed modManagerLog `class_key` field to `classKey`.';
    $sql = "ALTER TABLE {$table} CHANGE COLUMN `class_key` `classKey` VARCHAR(100) NOT NULL";
    processResults($this->xpdo,$results,$class,$description,$sql);

    $class = 'modUserMessage';
    $table = $this->xpdo->getTableName($class);
    $description = 'Changed modUserMessage `postdate` field from an INT to a DATETIME and to name `date_sent`.';
    $sql = "ALTER TABLE {$table} CHANGE COLUMN `postdate` `date_sent` DATETIME NOT NULL";
    processResults($this->xpdo,$results,$class,$description,$sql);
    $description = 'Changed modUserMessage `subject` field from VARCHAR(60) to VARCHAR(255).';
    $sql = "ALTER TABLE {$table} CHANGE COLUMN `subject` `subject` VARCHAR(255) NOT NULL";
    processResults($this->xpdo,$results,$class,$description,$sql);
    $description = 'Changed modUserMessage `messageread` field to `read`.';
    $sql = "ALTER TABLE {$table} CHANGE COLUMN `messageread` `read` TINYINT(1) NOT NULL";
    processResults($this->xpdo,$results,$class,$description,$sql);

    $class = 'modLexiconEntry';
    $table = $this->xpdo->getTableName($class);
    $description = 'Changed modLexiconEntry `createdon` to allow NULL.';
    $sql = "ALTER TABLE {$table} CHANGE COLUMN `createdon` `createdon` DATETIME NULL";
    processResults($this->xpdo,$results,$class,$description,$sql);
}
return $results;


function processResults(&$xpdo,&$results,$class,$description,$sql) {
    if (!$xpdo->exec($sql)) {
        $results[] = array (
            'class' => 'warning',
            'msg' => '<p class="notok">Error upgrading table for class ' . $class . '<br /><small>' . nl2br(print_r($xpdo->errorInfo(), true)) . '</small></p>'
        );
        return false;
    } else {
        $results[] = array (
            'class' => 'success',
            'msg' => '<p class="ok">Successfully upgraded table for class ' . $class . '<br /><small>' . $description . '</small></p>'
        );
        return true;
    }
}