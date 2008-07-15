{extends file='security/role/mutate.tpl'}

{modblock name='ab'}
<script type="text/javascript" src="assets/modext/sections/security/role/update.js"></script>
{literal}
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
	new MODx.Role.Update({
		id: '{/literal}{$role->id}{literal}'
	});
});
// ]]>
</script>
{/literal}
{/modblock}

{modblock name='othertabs'}
<div id="role_users_grid"></div>

<script type="text/javascript" src="assets/modext/grid/role.user.grid.js"></script>
{/modblock}
