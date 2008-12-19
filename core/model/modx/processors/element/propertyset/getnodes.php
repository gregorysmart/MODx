<?php
/**
 * Grabs all elements for propertyset tree
 *
 * @param string $id (optional) Parent ID of object to grab from. Defaults to 0.
 *
 * @package modx
 * @subpackage processors.element.propertyset
 */
$modx->lexicon->load('element','propertyset');

$_REQUEST['id'] = !isset($_REQUEST['id']) ? 0 : (substr($_REQUEST['id'],0,2) == 'n_' ? substr($_REQUEST['id'],2) : $_REQUEST['id']);
$nodeId = $_REQUEST['id'];

/* split the array */
$node = split('_',$nodeId);
$list = array();

switch ($node[0]) {
    case 'root': /* grab all property sets */
        $c = $modx->newQuery('modPropertySet');
        $c->sortby('name','ASC');
        $sets = $modx->getCollection('modPropertySet',$c);

        foreach ($sets as $set) {
            $sa = array(
                'text' => $set->get('name'),
                'id' => 'ps_'.$set->get('id'),
                'leaf' => false,
                'cls' => 'folder',
                'href' => '',
                'class_key' => 'modPropertySet',
                'qtip' => $set->get('description'),
                'menu' => array(
                    array(
                        'text' => $modx->lexicon('propertyset_element_add'),
                        'handler' => 'this.addElement',
                    ),
                    '-',
                    array(
                        'text' => $modx->lexicon('propertyset_remove'),
                        'handler' => 'this.removeSet',
                    ),
                ),
            );
            $list[] = $sa;
        }
        break;
    case 'ps': /* grab all elements for property set */

        $classes = array(
            'modChunk' => 'Chunk',
            'modPlugin' => 'Plugin',
            'modSnippet' => 'Snippet',
            'modTemplate' => 'Template',
            'modTemplateVar' => 'TemplateVar',
        );

        foreach ($classes as $class => $alias) {
            $c = $modx->newQuery('modElementPropertySet');
            $c->select('modElementPropertySet.*, '.$alias.'.*');
            $c->innerJoin($class,$alias,array(
                'modElementPropertySet.element = '.$alias.'.id',
                'modElementPropertySet.element_class = "'.$class.'"',
                'modElementPropertySet.property_set' => $node[1],
            ));
            $uk = ($class == 'modTemplate') ? 'templatename' : 'name';
            $c->sortby('`'.$alias.'`.`'.$uk.'`','ASC');
            $els = $modx->getCollection('modElementPropertySet',$c);

            foreach ($els as $el) {
                $sa = array(
                    'text' => '<i>('.$alias.')</i> '.$el->get('name'),
                    'id' => 'el_'.$el->get('property_set').'_'.$el->get('id'),
                    'leaf' => true,
                    'href' => '',
                    'pk' => $el->get('id'),
                    'propertyset' => $el->get('property_set'),
                    'element_class' => $class,
                    'menu' => array(
                        array(
                            'text' => $modx->lexicon('propertyset_element_remove'),
                            'handler' => 'this.removeElement',
                        )
                    ),
                );
                $list[] = $sa;
            }
            unset($c,$els,$el);
        }
        break;
}


return $modx->toJSON($list);