<div id="panel-symlink"></div>

{include file='resource/_javascript.tpl'}
<script type="text/javascript" src="{$_config.manager_url}assets/modext/core/modx.view.js"></script>
<script type="text/javascript" src="{$_config.manager_url}assets/modext/widgets/core/modx.browser.js"></script>
<script type="text/javascript" src="{$_config.manager_url}assets/modext/widgets/system/modx.tree.directory.js"></script>
<script type="text/javascript" src="{$_config.manager_url}assets/modext/widgets/element/modx.panel.tv.renders.js"></script>
<script type="text/javascript" src="assets/modext/widgets/resource/modx.panel.resource.tv.js"></script>
<script type="text/javascript" src="assets/modext/widgets/resource/modx.panel.resource.symlink.js"></script>
<script type="text/javascript" src="assets/modext/widgets/resource/modx.grid.resource.security.js"></script>

<form id="mutate_document" method="post" enctype="multipart/form-data" action="{$_config.connectors_url}resource/document.php" onsubmit="return false;">


{modblock name='ab'}{/modblock}

{$onDocFormPrerender}
<input type="hidden" class="hidden" id="id" name="id" value="{$resource->id}" />
<input type="hidden" class="hidden" id="parent" name="parent" value="{$resource->parent}" />

<!-- BEGIN TOP PANE -->

<!-- START Template Variables -->
<div id="tab_tvs" class="padding x-hide-display">
{include file='resource/sections/tvs.tpl'}
</div>
<!-- END Template Variables -->

<!-- START Access Permissions -->
<div id="tab_access" class="padding x-hide-display">
	<h2>{$_lang.security}</h2>
	
	<p>{$_lang.access_permissions_docs_message}</p>
	<div id="grid-resource-security"></div>
</div>
<!-- END Access Permissions -->
	
{modblock name='otherTabs'}{/modblock}
<!-- END TOP PANE -->

<br /><br />

{$onDocFormRender}
</form>