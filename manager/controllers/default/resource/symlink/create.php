<?php
/**
 * @package modx
 * @subpackage controllers.resource.symlink
 */
if (!$modx->hasPermission('new_document')) return $modx->error->failure($modx->lexicon('access_denied'));

$resource = $modx->newObject('modSymLink');

/* invoke OnDocFormPrerender event */
$onDocFormPrerender = $modx->invokeEvent('OnDocFormPrerender',array(
    'id' => 0,
    'mode' => 'new',
));
if (is_array($onDocFormPrerender)) {
    $onDocFormPrerender = implode('',$onDocFormPrerender);
}
$modx->smarty->assign('onDocFormPrerender',$onDocFormPrerender);

/* handle default parent */
$parentname = $modx->getOption('site_name');
$resource->set('parent',0);
if (isset ($_REQUEST['parent'])) {
    if ($_REQUEST['parent'] == 0) {
        $parentname = $modx->getOption('site_name');
    } else {
        $parent = $modx->getObject('modResource',$_REQUEST['parent']);
        if ($parent != null) {
          $parentname = $parent->get('pagetitle');
          $resource->set('parent',$parent->get('id'));
        }
    }
}
$modx->smarty->assign('parentname',$parentname);

/* invoke OnDocFormRender event */
$onDocFormRender = $modx->invokeEvent('OnDocFormRender',array(
    'id' => 0,
    'mode' => 'new',
));
if (is_array($onDocFormRender)) $onDocFormRender = implode('',$onDocFormRender);
$onDocFormRender = str_replace(array('"',"\n","\r"),array('\"','',''),$onDocFormRender);
$modx->smarty->assign('onDocFormRender',$onDocFormRender);

/* assign symlink to smarty */
$modx->smarty->assign('resource',$resource);


/* check permissions */
$publish_document = $modx->hasPermission('publish_document');
$edit_doc_metatags = $modx->hasPermission('edit_doc_metatags');
$access_permissions = $modx->hasPermission('access_permissions');

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/util/datetime.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/element/modx.panel.tv.renders.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/resource/modx.grid.resource.security.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/resource/modx.panel.resource.tv.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/resource/modx.panel.resource.symlink.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/resource/symlink/create.js');
$modx->regClientStartupHTMLBlock('
<script type="text/javascript">
// <![CDATA[
MODx.config.publish_document = "'.$publish_document.'";
MODx.onDocFormRender = "'.$onDocFormRender.'";
Ext.onReady(function() {
    MODx.load({
        xtype: "modx-page-symlink-create"
        ,template: "'.($parent != null ? $parent->get('template') : $modx->getOption('default_template')).'"
        ,content_type: "1"
        ,class_key: "'.(isset($_REQUEST['class_key']) ? $_REQUEST['class_key'] : 'modSymLink').'"
        ,context_key: "'.(isset($_REQUEST['context_key']) ? $_REQUEST['context_key'] : 'web').'"
        ,parent: "'.(isset($_REQUEST['parent']) ? $_REQUEST['parent'] : '0').'"
        ,which_editor: "'.$which_editor.'"
        ,edit_doc_metatags: "'.$edit_doc_metatags.'"
        ,access_permissions: "'.$access_permissions.'"
        ,publish_document: "'.$publish_document.'"
        ,canSave: "'.($modx->hasPermission('save_document') ? 1 : 0).'"
    });
});
// ]]>
</script>');

return $modx->smarty->fetch('resource/symlink/create.tpl');