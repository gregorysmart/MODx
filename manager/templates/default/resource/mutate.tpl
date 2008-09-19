<div id="panel-resource"></div>

{include file='resource/_javascript.tpl'}
<script type="text/javascript" src="{$_config.manager_url}assets/modext/core/modx.view.js"></script>
<script type="text/javascript" src="{$_config.manager_url}assets/modext/widgets/core/modx.browser.js"></script>
<script type="text/javascript" src="{$_config.manager_url}assets/modext/widgets/system/modx.tree.directory.js"></script>
<script type="text/javascript" src="{$_config.manager_url}assets/modext/widgets/element/modx.panel.tv.renders.js"></script>
<script type="text/javascript" src="{$_config.manager_url}assets/modext/widgets/resource/modx.grid.resource.security.js"></script>
<script type="text/javascript" src="{$_config.manager_url}assets/modext/widgets/resource/modx.panel.resource.tv.js"></script>
<script type="text/javascript" src="{$_config.manager_url}assets/modext/widgets/resource/modx.panel.resource.js"></script>

{modblock name='ab'}{/modblock}
{$onDocFormPrerender}
<!-- BEGIN TOP PANE -->

<!-- START META Keywords -->
{if $modx->hasPermission('edit_doc_metatags')}
<div id="tab_mtkw" class="x-hide-display">
{include file='resource/sections/metatags.tpl'}
</div>
{/if}
<!-- END META Keywords -->

<!-- START Template Variables -->
<div id="tab_tvs" class="x-hide-display">
{include file='resource/sections/tvs.tpl'}
</div>
<!-- END Template Variables -->

<!-- START Access Permissions -->
<div id="tab_access" class="x-hide-display">
    <h2>{$_lang.security}</h2>

    <p>{$_lang.access_permissions_docs_message}</p>
    <div id="grid-resource-security"></div>
</div>
<!-- END Access Permissions -->


{$onDocFormRender}

{if $resource->richtext AND $_config.use_editor}
{$onRichTextEditorInit}
{/if}