<?php
/**
 * @package modx
 */
class modResourceGroup extends modAccessibleSimpleObject {
    public function getResources() {
        $c= $this->xpdo->newQuery('modResource');
        $c->innerJoin('modResourceGroupResource', 'ResourceGroupResources');
        $c->where(array ('ResourceGroupResources.document_group' => $this->get('id')));
        $collection= $this->xpdo->getCollection('modResource', $c);
        return $collection;
    }

    public function getUserGroups() {
        $access= $this->xpdo->getCollection('modAccessResourceGroup', array (
            'target' => $this->get('id'),
            'principal_class' => 'modUserGroup',
        ));
        $groups= array();
        foreach ($access as $arg) {
            $groups[$arg->get('membergroup')]= $arg->getOne('Target');
        }
        return $groups;
    }
}