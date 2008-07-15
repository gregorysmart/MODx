<h2>{$_lang.plugin_properties}</h2>

<table class="classy">
<tbody>
<tr>
    <th><label for="moduleguid">{$_lang.plugin_import_params}</label>:</th>
    <td class="x-form-element">
        <select name="moduleguid" id="moduleguid">
        <option value=""></option>
        {foreach from=$plugin->params item=param}
            <option value="{$param->guid}" {if $plugin->moduleguid EQ $param->guid} selected="selected"{/if}>{$param->name}</option>
        {/foreach}
        </select>
        <br />
        <span class="comment">{$_lang.plugin_import_params_msg}</span>
    </td>
</tr>
<tr class="odd">
    <th><label for="properties">{$_lang.plugin_config}</label>:</th>
    <td class="x-form-element">
        <textarea name="properties" id="properties">{$plugin->properties}</textarea>
        <br />
        <input type="button" value="{$_lang.plugin_update_params}" class="button" />
    </td>
</tr>
<tr id="displayparamrow">
    <th>&nbsp;</th>
    <td id="displayparams">&nbsp;</td>
</tr>
</tbody>
</table>