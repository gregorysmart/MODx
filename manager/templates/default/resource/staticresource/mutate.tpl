<div id="panel-static"></div>

{include file='resource/_javascript.tpl'}
<script type="text/javascript" src="assets/modext/panel/resource/static.panel.js"></script>
<script type="text/javascript" src="assets/modext/panel/resource/resource.tv.panel.js"></script>
<script type="text/javascript" src="assets/modext/grid/resource.security.grid.js"></script>

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