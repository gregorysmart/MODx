<div id="panel-resource"></div>

{include file='resource/_javascript.tpl'}
<script type="text/javascript" src="assets/modext/panel/resource/resource.panel.js"></script>
<script type="text/javascript" src="assets/modext/panel/resource/resource.tv.panel.js"></script>
<script type="text/javascript" src="assets/modext/grid/resource.security.grid.js"></script>

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


<br /><br />
<!-- START Content -->
<div id="tabs_div">
<div id="tab_content" class="x-hide-display">
    {if $resource->richtext EQ 1 AND $_config.use_editor EQ 1}
    <div style="width:98%">
        <textarea id="ta" name="ta" style="width:100%; height: 400px;">{$htmlcontent}</textarea> 
    
        <label for="which_editor">{$_lang.which_editor_title}</label>
        <select id="which_editor" name="which_editor" onchange="changeRTE();">
            <option value="none" {if $which_editor EQ 'none'}selected="selected"{/if}>{$_lang.none}</option>
        {foreach from=$text_editors item=te}
            <option value="{$te}" {if $which_editor EQ $te} selected="selected"{/if}>{$te}</option>
        {/foreach}
        </select>
    </div>
    {else}
    <div style="width:98%">
        <textarea id="ta" name="ta" style="width:100%; height: 400px;">{$resource->content|escape}</textarea>
    </div>
    {/if}
</div>
</div>
<!-- END Content -->

{$onDocFormRender}

{if $resource->richtext AND $_config.use_editor}
{$onRichTextEditorInit}
{/if}