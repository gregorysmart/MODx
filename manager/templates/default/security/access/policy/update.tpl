<div class="padding">
    <div id="panel-access-policy" style="width: 725px;"></div>
</div>

<script type="text/javascript" src="assets/modext/panel/security/access.policy.panel.js"></script>
<script type="text/javascript" src="assets/modext/sections/security/access/policy/update.js"></script>

<script type="text/javascript">
{literal}
// <![CDATA[
Ext.onReady(function() {
    MODx.load({ 
        xtype: 'access-policy-update'
        ,policy: '{/literal}{$policy->id}{literal}'
    });
});
// ]]>
{/literal}
</script>