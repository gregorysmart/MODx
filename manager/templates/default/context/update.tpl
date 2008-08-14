<div id="tabs_div"></div>

<div id="panel-context"></div>

<script type="text/javascript" src="assets/modext/ui/modx.settings.grid.js"></script>
<script type="text/javascript" src="assets/modext/grid/settings.context.grid.js"></script>
<script type="text/javascript" src="assets/modext/panel/context.panel.js"></script>
<script type="text/javascript" src="assets/modext/sections/context/update.js"></script>

<script type="text/javascript">
{literal}
Ext.onReady(function() {
    MODx.load({
        xtype: 'modx-context-update'
        ,context: '{/literal}{$context->key}{literal}'
    });
});
{/literal}
</script>