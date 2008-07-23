<?php
/**
 * @package modx
 * @subpackage processors.element.module.dependency
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('module');


// get module
$module = $modx->getObject('modModule',$_REQUEST['module']);
if ($module->locked == 1) $modx->error->failure($modx->lexicon('access_denied'));

// get dependencies
$c = $modx->newQuery('modModuleDepobj');
$c = $c->where(array('module' => $_REQUEST['module']));
$deps = $modx->getCollection('modModuleDepobj',$c);
$processedDeps = array();
if (count($deps) > 0) {
	foreach ($deps as $dep) {
		$d = $dep->toArray();
		switch ($d['type']) {
		case 10:
			$d['class_key'] = 'modChunk';
			$resource = $modx->getObject('modChunk',$d['resource']);
			$d['name'] = $resource->name;
			break;
		case 20:
			$d['class_key'] = 'modResource';
			$resource = $modx->getObject('modResource',$d['resource']);
			$d['name'] = $resource->pagetitle;
			break;
		case 30:
			$d['class_key'] = 'modPlugin';
			$resource = $modx->getObject('modPlugin',$dep['resource']);
			$d['name'] = $resource->name;
			break;
		case 40:
			$d['class_key'] = 'modSnippet';
			$resource = $modx->getObject('modSnippet',$d['resource']);
			$d['name'] = $resource->name;
			break;
		case 50:
			$d['class_key'] = 'modTemplate';
			$resource = $modx->getObject('modTemplate',$d['resource']);
			$d['name'] = $resource->templatename;
			break;
		case 60:
			$d['class_key'] = 'modTemplateVar';
			$resource = $modx->getObject('modTemplateVar',$d['resource']);
			$d['name'] = $resource->name;
			break;
		}

        $d['menu'] = array(
            array(
                'text' => $modx->lexicon('module_dep_remove'),
                'handler' => 'this.remove.createDelegate(this,["module_dep_remove_confirm"])',
            )
        );
		$processedDeps[] = $d;
	}
}
$count = count($deps);
$this->outputArray($processedDeps,$count);