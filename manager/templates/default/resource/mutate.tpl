<div id="modx-panel-resource"></div>

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

<!-- START Template Variables -->
<div id="modx-tab-tvs" class="x-hide-display">
{include file='resource/sections/tvs.tpl'}
</div>
<!-- END Template Variables -->


{$onDocFormRender}

{if $resource->richtext AND $_config.use_editor}
{$onRichTextEditorInit}
{/if}