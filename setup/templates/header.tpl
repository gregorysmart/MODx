<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>{$app_name} {$app_version} &raquo; {$_lang.install}</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link href="assets/css/style.css" type="text/css" rel="stylesheet" />
	{if $_lang.additional_css NEQ ''}
    <style type="text/css">{$_lang.additional_css}</style>
	{/if}
    <script type="text/javascript" src="assets/js/mootools.js"></script>
    <script type="text/javascript" src="assets/js/moodx.js"></script>
    <script type="text/javascript" src="assets/js/formhandler.js"></script>
	{literal}
	<script type="text/javascript">
	// <![CDATA[
		Object.extend(FormHandler, {
			type: 'error'
			,showError: function(e,t) {
				$('modx_error_content').setHTML(e);
				$('modx_error').setProperty('class',t || error);
				$('modx_error').setStyle('display', 'block');
				$('modx_error').effect('opacity',{
					duration: 500
				}).start(1);
			}
			,closeError: function() {
				$('modx_error').effect('opacity',{
					duration: 500,
					onComplete: function() { $('modx_error').setStyle('display', 'none'); }
				}).start(0);
			}
			,errorJSON: function(e) {
                this.unhighlightFields();
				if (e == '') return this.showError('Unknown error processing request.');
				this.type = e.type;
    
                if (e.fields != null) {
                    for (p=0;p<e.fields.length;p++) {
                        this.highlightField(e.fields[p]);
                    }
                }

				if (e.message == '') return this.showError('Unknown error processing request.');
                this.showError(e.message,e.type);
			}
		});
		var installHandler = function(r) {
			r = Json.evaluate(r);
			if (r.success) {
				goAction(r.message);
			} else {
			    FormHandler.errorJSON(r);
			}
			return false;
		}
		var doAction = function(action) {
			return FormHandler.send($('install'), action, installHandler);
		}
		var goAction = function(action) {
		    $('install').setProperty('action', 'index.php?action=' + action);
		    $('install').submit();
		    return false;
		}
	// ]]>
	</script>
	{/literal}
</head>	
<body dir="{$_lang.dir}">

<div id="container">
  <div id="header">
    <img src="{$_lang.img_banner}" alt="[{$_lang.app_description}]" />
  </div>
  <div id="subheader" class="smalltext">
    <strong>{$app_name}</strong>&nbsp;<em>{$_lang.version} {$app_version}</em>
  </div>

  <div id="main">

    <form id="install" action="processors/connector.php" method="post">
      <div id="content">

        <div id="modx_error">
          <h2><span class="close">[&nbsp;<a href="javascript:;" onclick="FormHandler.closeError();">{$_lang.close}</a>&nbsp;]</span></h2>
          <span id="modx_error_content" class="content"></span>
          <br />
        </div>
   