{if $p.allowed}
<div class="menuLink" 
	onmouseover="this.className='menuLinkOver';"
	onmouseout="this.className='menuLink';"
	onclick="this.className='menuLink'; menuHandler({$p.id}); hideMenu();">
	<img src="media/style/{$_config.manager_theme}/images/icons/{$p.img}.gif" alt="" />
	{$p.text}
</div>
{else}
<div class="menuLinkDisabled">
	<img src="media/style/{$_config.manager_theme}/images/icons/{$p.img}.gif" alt="" />
	{$p.text}
</div>
{/if}