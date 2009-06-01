<?php
/**
 * Load update snippet page
 *
 * @package modx
 * @subpackage manager.element.snippet
 */
if(!$modx->hasPermission('edit_snippet')) return $modx->error->failure($modx->lexicon('access_denied'));

/* get snippet */
$snippet = $modx->getObject('modSnippet',$_REQUEST['id']);
if ($snippet == null) return $modx->error->failure($modx->lexicon('snippet_err_not_found'));
if ($snippet->get('locked') && !$modx->hasPermission('edit_locked')) {
    return $modx->error->failure($modx->lexicon('snippet_err_locked'));
}

/* invoke OnSnipFormPrerender event */
$onSnipFormPrerender = $modx->invokeEvent('OnSnipFormPrerender',array('id' => 0));
if (is_array($onSnipFormPrerender)) $onSnipFormPrerender = implode('',$onSnipFormPrerender);
$modx->smarty->assign('onSnipFormPrerender',$onSnipFormPrerender);

/* invoke onSnipFormRender event */
$onSnipFormRender = $modx->invokeEvent('OnSnipFormRender',array('id' => 0));
if (is_array($onSnipFormRender)) $onSnipFormRender = implode('',$onSnipFormRender);
$modx->smarty->assign('onSnipFormRender',$onSnipFormRender);

/* check unlock default element properties permission */
$unlock_element_properties = $modx->hasPermission('unlock_element_properties') ? 1 : 0;

/* register JS scripts */
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/core/modx.grid.local.property.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/element/modx.grid.element.properties.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/element/modx.panel.element.properties.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/widgets/element/modx.panel.snippet.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/element/snippet/common.js');
$modx->regClientStartupScript($modx->getOption('manager_url').'assets/modext/sections/element/snippet/update.js');
$modx->regClientStartupHTMLBlock('
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
    MODx.load({
        xtype: "modx-page-snippet-update"
        ,id: "'.$snippet->get('id').'"
        ,category: "'.$snippet->get('category').'"
    });
});
MODx.onSnipFormRender = "'.$onSnipFormRender.'";
MODx.perm.unlock_element_properties = "'.$unlock_element_properties.'";
// ]]>
</script>');

/* assign snippet to parser and display template */
$modx->smarty->assign('snippet',$snippet);

return $modx->smarty->fetch('element/snippet/update.tpl');