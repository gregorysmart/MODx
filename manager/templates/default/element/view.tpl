<div id="tabs_div">

<!-- Templates -->
{if $modx->hasPermission('new_template') OR $modx->hasPermission('edit_template')}
    <div class="sectionBody" id="tab_templates">
		<p>{$_lang.template_management_msg}</p>
		<br />
		
		{foreach from=$categories item=category}
		<div class="expandable {if $category->templates|@count EQ 0}noitems{/if}">
			<div class="header">{$category->category|capitalize}</div>
			<ul class="item_holder" id="cat-{$category->id}">
			{foreach from=$category->templates item=template}
				<li class="item" id="item-{$template->id}">
					<a href="index.php?a=element/template/update&amp;id={$template->id}">{$template->templatename}</a>
					{if $template->description} - {$template->description}{/if}
					{if $template->locked}<em><small>({$_lang.locked})</small></em>{/if}
				</li>
			{foreachelse}
				<li class="nomove" id="template0">{$_lang.category_no_templates}</li>
			{/foreach}
			</ul>
		</div>
		{/foreach}
	</div>
{/if}
<!-- End Templates -->


<!-- Template variables -->
{if $modx->hasPermission('new_template') OR $modx->hasPermission('edit_template')}
    <div class="sectionBody" id="tab_tvs">
		<p>{$_lang.tmplvars_management_msg}</p>
		<br />
		
		{foreach from=$categories item=category}
		<div class="expandable {if $category->tvs|@count EQ 0}noitems{/if}">
			<div class="header">{$category->category|capitalize}</div>
			<ul>
			{foreach from=$category->tvs item=tv}
				<li>
					<a href="index.php?a=element/tv/update&amp;id={$tv->id}">{$tv->name}</a>
					{if $tv->description} - {$tv->description}{/if}
					{if $tv->locked}<em><small>({$_lang.locked})</small></em>{/if}
				</li>
			{foreachelse}
				<li class="nomove" id="tv0">{$_lang.category_no_template_variables}</li>
			{/foreach}
			</ul>
		</div>
		{/foreach}
	</div>
{/if}
<!-- END Template variables -->


<!-- START chunks -->
{if $modx->hasPermission('new_chunk') OR $modx->hasPermission('edit_chunk')}
    <div class="sectionBody" id="tab_chunks">
		<p>{$_lang.htmlsnippet_management_msg}</p>
		<br />
		
		{foreach from=$categories item=category}
		<div class="expandable {if $category->chunks|@count EQ 0}noitems{/if}"">
			<div class="header">{$category->category|capitalize}</div>
			<ul>
			{foreach from=$category->chunks item=chunk}
				<li>
					<a href="index.php?a=element/chunk/update&amp;id={$chunk->id}">{$chunk->name}</a>
					{if $chunk->description} - {$chunk->description}{/if}
					{if $chunk->locked}<em><small>({$_lang.locked})</small></em>{/if}
				</li>
			{foreachelse}
				<li class="nomove" id="chunk0">{$_lang.category_no_chunks}</li>
			{/foreach}
			</ul>
		</div>
		{/foreach}
	</div>
{/if}
<!-- END chunks -->



<!-- START snippets -->
{if $modx->hasPermission('new_snippet') OR $modx->hasPermission('edit_snippet')}
    <div class="sectionBody" id="tab_snippets">
		<p>{$_lang.snippet_management_msg}</p>
		<br />
		
		{foreach from=$categories item=category}
		<div class="expandable {if $category->snippets|@count EQ 0}noitems{/if}"">
			<div class="header">{$category->category|capitalize}</div>
			<ul>
			{foreach from=$category->snippets item=snippet}
				<li>
					<a href="index.php?a=element/snippet/update&amp;id={$snippet->id}">{$snippet->name}</a>
					{if $snippet->description} - {$snippet->description}{/if}
					{if $snippet->locked}<em><small>({$_lang.locked})</small></em>{/if}
				</li>
			{foreachelse}
				<li class="nomove" id="snippet0">{$_lang.category_no_snippets}</li>
			{/foreach}
			</ul>
		</div>
		{/foreach}
	</div>
{/if}
<!-- END snippets -->


<!-- plugins -->
{if $modx->hasPermission('new_plugin') OR $modx->hasPermission('edit_plugin')}
    <div class="sectionBody" id="tab_plugins">
		<p>{$_lang.plugin_management_msg}</p>
		
		{if $modx->hasPermission('save_plugin')}
		<a href="index.php?a=element/plugin/sortpriority">{$_lang.plugin_priority}</a>
		{/if}
		<br />
		<br />
		
		{foreach from=$categories item=category}
		<div class="expandable {if $category->plugins|@count EQ 0}noitems{/if}">
			<div class="header">{$category->category|capitalize}</div>
			<ul>
			{foreach from=$category->plugins item=plugin}
				<li {if $plugin->disabled} class="disabledPlugin"{/if}>
					<a href="index.php?a=element/plugin/update&amp;id={$plugin->id}">{$plugin->name}</a>
					{if $plugin->description} - {$plugin->description}{/if}
					{if $plugin->locked}<em><small>({$_lang.locked})</small></em>{/if}
				</li>
			{foreachelse}
				<li class="nomove" id="plugin0">{$_lang.category_no_plugins}</li>
			{/foreach}
			</ul>
		</div>
		{/foreach}
	</div>
{/if}



<!-- START category view -->
<div class="sectionBody" id="tab_category">
	<p>{$_lang.category_msg}</p>
	<br />

	{foreach from=$categories item=category}
	<div class="expandable">
		<div class="header">{$category->category|capitalize}
		{if $category->id NEQ 0 AND $delPerm}
			(<a href="index.php?a=element/category/delete&amp;catId={$category->id}" onclick="return confirm('{$_lang.category_confirm_delete}');">{$_lang.delete}</a>)
		{/if}
		</div>
		<ul>
		{foreach from=$category->templates item=template}
		<li>
			<a href="index.php?id={$template->id}&amp;a=element/template/update">{$template->templatename}</a>
			({$_lang.template})
			{if $template->description NEQ ''}{$template->description}{/if}
			{if $template->locked}<em><small>({$_lang.locked})</small></em>{/if}
		</li>
		{/foreach}
		{foreach from=$category->tvs item=tv}
		<li>		
			<a href="index.php?id={$tv->id}&amp;a=element/tv/update">{$tv->name}</a>
			({$_lang.tmplvar}) 
			{if $tv->description NEQ ''}{$tv->description}{/if}
			{if $tv->locked}<em><small>({$_lang.locked})</small></em>{/if}
		</li>
		{/foreach}
		{foreach from=$category->chunks item=chunk}
		<li>		
			<a href="index.php?id={$chunk->id}&amp;a=element/chunk/update">{$chunk->name}</a>
			({$_lang.chunk}) 
			{if $chunk->description NEQ ''}{$chunk->description}{/if}
			{if $chunk->locked}<em><small>({$_lang.locked})</small></em>{/if}
		</li>
		{/foreach}

		{foreach from=$category->snippets item=snippet}
		<li>		
			<a href="index.php?id={$tv->id}&amp;a=element/snippet/update">{$snippet->name}</a>
			({$_lang.snippet}) 
			{if $snippet->description NEQ ''}{$snippet->description}{/if}
			{if $snippet->locked}<em><small>({$_lang.locked})</small></em>{/if}
		</li>
		{/foreach}
		{foreach from=$category->plugins item=plugin}
		<li {if $plugin->disabled} class="pluginDisabled"{/if}>		
			<a href="index.php?id={$tv->id}&amp;a=element/plugin/update">{$plugin->name}</a>
			({$_lang.plugin}) 
			{if $plugin->description NEQ ''}{$plugin->description}{/if}
			{if $plugin->locked}<em><small>({$_lang.locked})</small></em>{/if}
		</li>
		{/foreach}
		</ul>
	</div>
	{/foreach}
</div>
<!-- END category view -->

</div>

{literal}
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
	var ab = new MODx.toolbar.ActionButtons({
		type: 'create'
		,actions: {
			'cancel': 'welcome'
		}
	});
	ab.create({
		text: _('new')
		,menu: new Ext.menu.Menu({
			id: 'menu_new'
			,items: [
				new Ext.menu.Item({
					text: _('template')
					,href: 'index.php?a=element/template/create'
				})
				,new Ext.menu.Item({
					text: _('tv')
					,href: 'index.php?a=element/tv/create'
				})
				,new Ext.menu.Item({
					text: _('chunk')
					,href: 'index.php?a=element/chunk/create'
				})
				,new Ext.menu.Item({
					text: _('snippet')
					,href: 'index.php?a=element/snippet/create'
				})
				,new Ext.menu.Item({
					text: _('plugin')
					,href: 'index.php?a=element/plugin/create'
				})
			]
		})
	},'-',{
		process: 'cancel', text: _('cancel'), params: {a:'welcome'}
	});

	var t = new Ext.TabPanel('tabs_div');
	t.addTab('tab_templates',_('manage_templates'));
	t.addTab('tab_tvs',_('tmplvars'));
	t.addTab('tab_chunks',_('manage_htmlsnippets'));
	t.addTab('tab_snippets',_('manage_snippets'));
	t.addTab('tab_plugins',_('manage_plugins'));
	t.addTab('tab_category',_('resource_categories'));
	t.activate('tab_templates');
});
// ]]>
</script>
{/literal}