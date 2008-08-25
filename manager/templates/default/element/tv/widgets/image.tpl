<input id="tv{$tv->id}" name="tv{$tv->id}"
	type="text"
	value="{$tv->get('value')}"
	{$style}
	onchange="documentDirty=true;" 
/>&nbsp;
<input type="button" 
	value="{$_lang.insert}"
	onclick="loadBrowser('tv{$tv->id}'); return false;" 
/>

<div id="browser"></div>

<script src="{$_config.manager_url}assets/modext/ui/modx.view.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/ui/modx.browser.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/tree/directory.tree.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/util/switchbutton.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/ui/modx.actionbuttons.js" type="text/javascript"></script>

{literal}
<script type="text/javascript">
var browser = null;
var loadBrowser = function(tv) { 
    if (browser == null) {
	    browser = new MODx.browser.Window({
	       el: 'browser'
	       ,onSelect: function(data) {
	            Ext.get(tv).dom.value = unescape(data.url);
	       }
	    });
    }
	browser.show('browser');
}
</script>
{/literal}