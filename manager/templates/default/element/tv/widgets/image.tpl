<input id="tv{$tv->name}" name="tv{$tv->name}"
	type="text"
	value="{$tv->get('value')}"
	{$style}
	onchange="documentDirty=true;" 
/>&nbsp;
<input type="button" 
	value="{$_lang.insert}"
	onclick="BrowseServer('tv{$tv->name}'); return false;" 
/>

{literal}
<script type="text/javascript">
var lastImageCtrl;
function OpenServerBrowser(url, width, height ) {
	var iLeft = (screen.width  - width) / 2 ;
	var iTop  = (screen.height - height) / 2 ;

	var sOptions = 'toolbar=no,status=no,resizable=yes,dependent=yes' ;
	sOptions += ',width=' + width ;
	sOptions += ',height=' + height ;
	sOptions += ',left=' + iLeft ;
	sOptions += ',top=' + iTop ;

	var oWindow = window.open( url, 'FCKBrowseWindow', sOptions ) ;
}
function BrowseServer(ctrl) {
	lastImageCtrl = ctrl;
	var w = screen.width * 0.7;
	var h = screen.height * 0.7;
	{/literal}
	OpenServerBrowser('{$base_url}manager/media/browser/mcpuk/browser.html?Type=images&Connector={$base_url}manager/media/browser/mcpuk/connectors/php/connector.php&ServerPath={$base_url}', w, h);
	{literal}
}
function SetUrl(url, width, height, alt){
	if(!lastImageCtrl) return;
	var c = document.mutate[lastImageCtrl];
	if(c) c.value = url;
	lastImageCtrl = '';
}
</script>
{/literal}