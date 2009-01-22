<div id="modx-panel-access-policy"></div>

<script type="text/javascript" src="assets/modext/widgets/security/modx.panel.access.policy.js"></script>
<script type="text/javascript" src="assets/modext/sections/security/access/policy/update.js"></script>

<script type="text/javascript">
{literal}
// <![CDATA[
Ext.onReady(function() {
    MODx.load({ 
        xtype: 'modx-page-access-policy'
        ,policy: '{/literal}{$policy->id}{literal}'
    });
});
// ]]>
{/literal}
</script>