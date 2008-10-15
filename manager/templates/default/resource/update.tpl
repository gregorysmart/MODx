{extends file='resource/mutate.tpl'}

{modblock name='ab'}

<script type="text/javascript" src="assets/modext/sections/resource/update.js"></script>
{literal}
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
    MODx.load({
	{/literal}
	    xtype: 'page-resource-update'
	    ,id: '{$resource->id}'
	    ,ctx: '{$resource->context_key}'
	    ,template: '{$resource->template}'
	    ,class_key: '{$resource->class_key}'
	    ,context_key: '{$resource->context_key}'
	    ,which_editor: '{$which_editor}'
	    ,published: '{$resource->published}'
	    ,deleted: '{$resource->deleted}'
	    ,content_type: '{$resource->content_type}'
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