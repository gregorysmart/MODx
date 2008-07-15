<div id="panel-plugin"></div>

<script type="text/javascript" src="assets/modext/panel/element/plugin.panel.js"></script>
<script type="text/javascript" src="assets/modext/sections/element/plugin/common.js"></script>
<script type="text/javascript" src="assets/modext/grid/plugin.event.grid.js"></script>
{include file='element/plugin/_javascript.tpl'}

{$onPluginFormPrerender}
{modblock name='ab'}{/modblock}

<div id="tab_configuration" class="x-hide-display">
{include file='element/plugin/sections/config.tpl'}
</div>