{extends file='resource/weblink/mutate.tpl'}

{modblock name='ab'}
<script type="text/javascript" src="{$_config.manager_url}assets/modext/sections/resource/weblink/create.js"></script>
{literal}
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
    MODx.load({
	{/literal}
	    xtype: 'weblink-create'
	    ,template: '{$resource->template}'
	    ,class_key: '{$resource->class_key}'
	    ,edit_doc_metatags: {if $modx->hasPermission('edit_doc_metatags')}true{else}false{/if}
	    ,access_permissions: {if $modx->hasPermission('access_permissions')}true{else}false{/if}
	{literal}
	});
});
// ]]>
</script>
{/literal}
{/modblock}