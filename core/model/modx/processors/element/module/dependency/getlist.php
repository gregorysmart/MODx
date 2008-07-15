<?php
/**
 * @package modx
 * @subpackage processors.element.module.dependency
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('module');


// get module
$module = $modx->getObject('modModule',$_REQUEST['id']);
if ($module->locked == 1 && $_SESSION['mgrRole'] != 1) $error->failure($modx->lexicon('access_denied'));

// get dependencies
$c = $modx->newQuery('modModuleDepobj');
$c = $c->where(array('module:=' => $_REQUEST['id']));
$deps = $modx->getCollection('modModuleDepobj',$c);
$processedDeps = array();
if (count($deps) > 0) {
	foreach ($deps as $dep) {
		$d = $dep->toArray();
		switch ($d['type']) {
		case 10:
			$d['type'] = 'Chunk';
			$resource = $modx->getObject('modChunk',$d['resource']);
			$d['resource_name'] = $resource->name;
			break;
		case 20:
			$d['type'] = 'Document';
			$resource = $modx->getObject('modResource',$d['resource']);
			$d['resource_name'] = $resource->pagetitle;
			break;
		case 30:
			$d['type'] = 'Plugin';
			$resource = $modx->getObject('modPlugin',$dep['resource']);
			$d['resource_name'] = $resource->name;
			break;
		case 40:
			$d['type'] = 'Snippet';
			$resource = $modx->getObject('modSnippet',$d['resource']);
			$d['resource_name'] = $resource->name;
			break;
		case 50:
			$d['type'] = 'Template';
			$resource = $modx->getObject('modTemplate',$d['resource']);
			$d['resource_name'] = $resource->templatename;
			break;
		case 60:
			$d['type'] = 'TV';
			$resource = $modx->getObject('modTemplateVar',$d['resource']);
			$d['resource_name'] = $resource->name;
			break;
		}
		$processedDeps[] = $d;
	}
}
$count = count($deps);
$this->outputArray($processedDeps,$count);