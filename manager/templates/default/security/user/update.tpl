{extends file='security/user/mutate.tpl'}


{modblock name='ab'}
<script type="text/javascript" src="assets/modext/widgets/core/modx.grid.settings.js"></script>
<script type="text/javascript" src="assets/modext/widgets/security/modx.grid.user.settings.js"></script>
<script type="text/javascript" src="assets/modext/sections/security/user/update.js"></script>
{literal}
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
	MODx.load({
	{/literal}
		xtype: 'page-user-update'
		,user: '{$user->id}'
		,manager_language: '{$user->get("language")}'
		,which_editor: '{$user->settings.which_editor}'
	{literal}
	});
});
// ]]>
</script>
{/literal}
{/modblock}