<h2>{$_lang.properties}</h2>

<table class="classy">
<tbody>
<tr>
	<th><label for="guid">{$_lang.module_guid}</label></th>
	<td class="x-form-element">
		<input name="guid" id="guid" type="text" value="{if $module}{$module->guid}{else}{$guid}{/if}" />
		<br /><br />
	</td>
</tr>
<tr>
	<th><label for="enable_sharedparams">{$_lang.module_sharedparams_enable}</label></th>
	<td class="x-form-element">
		<input name="enable_sharedparams" id="enable_sharedparams" type="checkbox" {if $module->enable_sharedparams}checked="checked"{/if} />
	</td>
</tr>
<tr>
	<th><label for="properties">{$_lang.module_config}</label></th>
	<td class="x-form-element">
		<input name="properties" id="properties" type="text" value="{$module->properties}" />
		<input type="button" value=".." style="width:16px; margin-left:2px;" title="{$_lang.module_sharedparams_update}" />
	</td>
</tr>
<tr id="displayparamrow">
	<th>&nbsp;</th>
	<td id="displayparams">&nbsp;</td>
</tr>
</tbody>
</table>