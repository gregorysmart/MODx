<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" {if $_config.manager_direction EQ 'rtl'}dir="rtl"{/if} lang="{$_config.manager_lang_attribute}" xml:lang="{$_config.manager_lang_attribute}">
<head>
<title>MODx</title>
<meta http-equiv="Content-Type" content="text/html; charset={$_config.modx_charset}" />
<link rel="stylesheet" type="text/css" href="media/style/{$_config.manager_theme}/css/index.css" />
<link rel="stylesheet" type="text/css" href="media/style/{$_config.manager_theme}/css/ext/ext-all.css" />

<script type="text/javascript">
var MODX_MEDIA_PATH = "{if $smarty.const.IN_MANAGER_MODE}media{else}manager/media{/if}";
</script>
<script src="media/script/mootools/mootools.js" type="text/javascript"></script>
<script src="media/script/mootools/moodx.js" type="text/javascript"></script>
<script src="media/script/ext/ext-mootools-adapter.js" type="text/javascript"></script>
<script src="media/script/ext/ext-all.js" type="text/javascript"></script>
<script src="media/script/bin/formhandler.js" type="text/javascript"></script>
<script src="media/script/bin/utilities.js" type="text/javascript"></script>
<script src="media/script/modext/modActionButtons.class.js" type="text/javascript"></script>
{literal}
<script type="text/javascript">
var documentDirty = false;
var dontShowWorker = false;
var managerPath = '';
var modError;

Ext.onReady(function() {
	$('modx_error').fx = new Fx.Style('modx_error','opacity',{wait: false});

	stopWorker();
	hideLoader();
	//{/literal}{if $smarty.request.r}doRefresh({$smarty.request.r});{/if}{literal}
	
	// set tree to default action.
	//parent.tree.ca = 'open';
	
	// add the 'unsaved changes' warning event handler
	/*
	if (window.addEventListener) {
		window.addEventListener('beforeunload',checkDirt,false);
	} else if ( window.attachEvent ) {
		window.attachEvent('onbeforeunload',checkDirt);
	} else {
		window.onbeforeunload = checkDirt;
	}*/
});

// <![CDATA[
Element.extend({
   serialize: function(tag,key) {
      var ret = [],key = key || this.id;
      this.getElements(tag || 'li').each(function(el, i) {
          ret.push(key+'['+i+']=' + el.id);
      });
      return ret.join('&');
   }
});

var sortableOptions = {
	onStart: function() {
	},
	onComplete: function() {
		$('sortableVals').value=$('sortable').serialize();
	}
};

window.onload = function() {
var Sortable = new Sortables('sortable',sortableOptions);

}

function save() {
	$('tvsort').send({onComplete: showResponse });
}

function showResponse(request) {
	var updatedFx = new Fx.Styles('updated');
	updatedFx.start({'opacity':[0,1]});
	$('updated').innerHTML=request;
	setTimeout("hideResponse()",1000);
}

function hideResponse() {
	var updatedFx = new Fx.Styles('updated');
	updatedFx.start({'opacity':[1,0]});
}

// ]]>
</script>
{/literal}
</head>
<body ondragstart="return false;">
<div class="subTitle">
<br />
<ul class="actionButtons">
<li>
<li>
<a href="#" onclick="documentDirty=false;save();">
				<img src="media/style/{$_config.manager_theme}/images/icons/save.gif" alt="{$_lang.save}" />
				{$_lang.save}
			</a>
		</li>
<li>
<a href="#" onclick="documentDirty=false;document.location.href='index.php?a=16&id={$smarty.request.id}'">
				<img src="media/style/{$_config.manager_theme}/images/icons/cancel.gif" alt="{$_lang.cancel}" />
				{$_lang.cancel}
			</a>
</li>
</ul>
</div>
<div id="updated"></div>
<span class="warning" style="display:none;" id="updating">Updating...<br /><br /> </span>
<div class="sectionHeader">{$_lang.plugin_priority}</div>
<div class="sectionBody">
<ul id="sortable">
	{foreach from=$objEventList item=Event}
		<li class="sort" id="{$tv->id}">
			<strong>{$Event->evtid}</strong> 
		</li>
		{/foreach}
	</ul>
</div>