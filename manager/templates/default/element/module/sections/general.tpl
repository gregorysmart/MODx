<h2>{$_lang.module}: {$module->name}</h2>

<p><img src="templates/{$_config.manager_theme}/images/icons/modules.gif" alt="." width="32" height="32" align="left" hspace="10" />{$_lang.module_msg}</p>
<br />

<div class="options list">
    <h3>{$_lang.options}</h3>
    <ul>
        <li class="x-form-element">
	        <input name="disabled" id="disabled" type="checkbox" value="on" {if $module->disabled EQ 1} checked="checked"{/if} />
        </li>
        <li class="x-form-element">
            <input name="locked" id="locked" type="checkbox" {if $module->locked EQ 1}checked="checked"{/if} />
            <span class="comment">{$_lang.module_lock_msg}</span>
        </li>
    </ul>
</div>

<table class="classy">
<tbody>
<tr>
	<th style="width: 10em"><label for="name">{$_lang.module_name}</label></th>
	<td class="x-form-element">
		<input name="name" id="name" type="text" value="{$module->name|escape}" />
		<span class="warning" id="savingMessage">&nbsp;</span>
		<span id="name_error" class="error"></span>
	</td>
</tr>
<tr class="odd">
	<th><label for="description">{$_lang.module_desc}</label></th>
	<td class="x-form-element">
		<input name="description" id="description" type="text" value="{$module->description|escape}" />
	</td>
</tr>
<tr>
	<th><label for="icon">{$_lang.icon} <span class="comment">(32x32)</span></label></th>
	<td class="x-form-element">
		<input type="text" id="icon" name="icon" value="{$module->icon}" /> 
		<input type="button" value="{$_lang.insert}" onclick="BrowseServer();" style="width:45px;" />
	</td>
</tr>
<tr>
	<th><label for="category">{$_lang.category}</label></th>
	<td class="x-form-element">
		<input name="category" id="category" />
	</td>
</tr>
<tr class="odd">
	<th><label for="enable_resource" title="{$_lang.enable_resource}">{$_lang.resource}</label></th>
	<td class="x-form-element">
		<input name="enable_resource" id="enable_resource" type="checkbox" {if $module->enable_resource EQ 1}checked="checked"{/if} />
		<input name="sourcefile" id="sourcefile" type="text" value="{$module->sourcefile}" />
	</td>
</tr>
</tbody>
</table>

<hr />
					
<!-- PHP text editor start -->
<div class="phpeditor">
	<div class="header">
		<span class="wrap">
			<input name="wrap" id="wrap" type="checkbox" onclick="setTextWrap($('mutate_module').post,this.checked)" {if $module->wrap EQ 1}checked="checked"{/if} />
		</span>
		<h3>{$_lang.module_code}</h3>
	</div>
	<textarea name="post" id="post" dir="ltr" rows="20" wrap="{if $module->wrap EQ 1}soft{else}off{/if}"><?php
{$module->modulecode|escape}
?></textarea>
</div>
<!-- PHP text editor end -->