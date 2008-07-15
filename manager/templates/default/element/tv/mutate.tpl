<script type="text/javascript" src="assets/modext/sections/element/tv/common.js"></script>
<script type="text/javascript" src="assets/modext/grid/tv.template.grid.js"></script>
<script type="text/javascript" src="assets/modext/grid/tv.security.grid.js"></script>

<form id="mutate_tv" method="post" action="{$_config.connectors_url}element/tv.php" onsubmit="return false;">
{$onTVFormPrerender}
<input type="hidden" name="id" value="{$tv->id|default:0}" />
<input type="hidden" name="mode" value="{$smarty.request.a}" />
<input type="hidden" name="params" id="params" value="{$tv->display_params}" />

{modblock name='ab'}{/modblock}

<div id="tabs_div">
<div class="padding x-hide-display" id="tab_general">
{include file='element/tv/sections/general.tpl'}
</div>
</div>

{$onTVFormRender}
</form>