<div id="panel-snippet"></div>
<script type="text/javascript" src="assets/modext/widgets/element/modx.panel.snippet.js"></script>
<script type="text/javascript" src="assets/modext/sections/element/snippet/common.js"></script>

{modblock name='ab'}{/modblock}

{$onSnipFormPrerender}

<div class="x-hide-display" id="tab_properties">
{include file='element/snippet/sections/properties.tpl'}
</div>

<script type="text/javascript">
var onSnipFormRender = '{$onSnipFormRender}';
</script>