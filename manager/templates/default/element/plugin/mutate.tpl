<div id="panel-plugin"></div>

<script type="text/javascript" src="assets/modext/widgets/core/modx.grid.local.js"></script>
<script type="text/javascript" src="assets/modext/widgets/core/modx.grid.local.property.js"></script>
<script type="text/javascript" src="assets/modext/widgets/element/modx.grid.element.properties.js"></script>
<script type="text/javascript" src="assets/modext/widgets/element/modx.grid.plugin.event.js"></script>
<script type="text/javascript" src="assets/modext/widgets/element/modx.panel.plugin.js"></script>
<script type="text/javascript" src="assets/modext/sections/element/plugin/common.js"></script>
{include file='element/plugin/_javascript.tpl'}

{$onPluginFormPrerender}
{modblock name='ab'}{/modblock}

<script type="text/javascript">
var onPluginFormRender = '{$onPluginFormRender}';
</script>