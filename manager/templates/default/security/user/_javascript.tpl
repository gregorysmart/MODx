{literal}
<script type="text/javascript">
// <![CDATA[
function OpenServerBrowser(url, width, height ) {
	var iLeft = (screen.width  - width) / 2 ;
	var iTop  = (screen.height - height) / 2 ;

	var sOptions = "toolbar=no,status=no,resizable=yes,dependent=yes" ;
	sOptions += ",width=" + width ;
	sOptions += ",height=" + height ;
	sOptions += ",left=" + iLeft ;
	sOptions += ",top=" + iTop ;

	var oWindow = window.open( url, "FCKBrowseWindow", sOptions ) ;
}
function BrowseServer() {
	var w = screen.width * 0.7;
	var h = screen.height * 0.7;
	OpenServerBrowser("{/literal}{$smarty.const.MODX_MANAGER_URL}media/browser/mcpuk/browser.html?Type=images&Connector={$smarty.const.MODX_MANAGER_URL}media/browser/mcpuk/connectors/php/connector.php&ServerPath={$smarty.const.MODX_MANAGER_URL}{literal}", w, h);
}
function SetUrl(url, width, height, alt){
	$('uf').photo.value = url;
	$('iphoto').src = url;
}

function changestate(element) {
	documentDirty=true;
	currval = eval(element).value;
	if(currval==1) {
		eval(element).value=0;
	} else {
		eval(element).value=1;
	}
}

function changePasswordState(element) {
	currval = eval(element).value;
	if(currval==1) {
		$('passwordBlock').style.display='block';
	} else {
		$('passwordBlock').style.display='none';
	}
}

function changeblockstate(element, checkelement) {
	currval = eval(element).value;
	if(currval==1) {
		if(confirm("{/literal}{$_lang.confirm_unblock}{literal}")){
			$('uf').blocked.value=0;
			$('uf').blockeduntil.value="";
			$('uf').blockedafter.value="";
			$('uf').failedlogincount.value=0;
			blocked.innerHTML="<strong>{/literal}{$_lang.unblock_message}{literal}</strong>";
			blocked.className='TD';
			eval(element).value=0;
		} else {
			eval(checkelement).checked=true;
		}
	} else {
		if(confirm("{/literal}{$_lang.confirm_block}{literal}")){
			$('uf').blocked.value=1;
			blocked.innerHTML="<strong>{/literal}{$_lang.block_message}{literal}</strong>";
			blocked.className='warning';
			eval(element).value=1;
		} else {
			eval(checkelement).checked=false;
		}
	}
}

function resetFailed() {
	$('uf').failedlogincount.value=0;
	$('failed').innerHTML='0';
}

function deleteuser() {
{/literal}{if $smarty.get.id EQ $modx->getLoginUserID()}
	alert("{$_lang.alert_delete_self}");
{else}{literal}
	if(confirm("{/literal}{$_lang.confirm_delete_user}{literal}")) {
		document.location.href='index.php?id='+$('uf').id.value+'&a=33';
	}
{/literal}{/if}{literal}
}

// change name
function changeName(){
	if(confirm("{/literal}{$_lang.confirm_name_change}{literal}")) {
		$('showname').style.display = 'none';
		$('editname').style.display = '';
	}
};

// showHide - used by custom settings
function showHide(what, onoff){
	var all = document.getElementsByTagName('*');
	var l = all.length;
	var buttonRe = what;
	var id, el, stylevar;

	stylevar = onoff == 1
		? '{/literal}{$displayStyle}{literal}'
		: 'none';

	for ( var i = 0; i < l; i++ ) {
		el = all[i]
		id = el.id;
		if (id == '') continue;
		if (buttonRe.test(id)) el.style.display = stylevar;
	}
};
// ]]>
</script>
{/literal}