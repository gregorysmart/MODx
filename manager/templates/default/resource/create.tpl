{extends file='resource/mutate.tpl'}

{modblock name='ab'}
<script type="text/javascript" src="assets/modext/sections/resource/create.js"></script>
{literal}
<script type="text/javascript">
// <![CDATA[
{/literal}MODx.config.publish_document = {if $modx->hasPermission('publish_document')}true{else}false{/if}{literal};
Ext.onReady(function() {
    MODx.load({
	{/literal}
	    xtype: 'modx-page-resource-create'
	    ,template: '{if $parent}{$parent->get('template')}{else}{$_config.default_template}{/if}'
	    ,class_key: '{$smarty.request.class_key|default:"modDocument"}'
        ,context_key: '{$smarty.request.context_key|default:"web"}'
	    ,which_editor: '{$which_editor}'
	   	,content_type: ''
	   	,parent: '{$smarty.request.parent|default:0}'
	    ,edit_doc_metatags: {if $modx->hasPermission('edit_doc_metatags')}true{else}false{/if}
	    ,access_permissions: {if $modx->hasPermission('access_permissions')}true{else}false{/if}
        ,publish_document: {if $modx->hasPermission('publish_document')}true{else}false{/if}
	{literal}
	});
});
// ]]>
</script>
{/literal}
{/modblock}