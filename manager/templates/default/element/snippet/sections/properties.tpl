<h2>{$_lang.snippet_properties}</h2>

<table class="classy">
<tbody>
<tr>
    <th><label for="moduleguid">{$_lang.snippet_import_params}</label></th>
    <td class="x-form-element">
        <select name="moduleguid" id="moduleguid">
        <option value=""></option>
        {foreach from=$params item=p}
            <option value="{$p->get('guid')}" {if $p->get('guid') EQ $snippet->moduleguid} selected="selected"{/if}>{$p->get('name')}</option>
        {/foreach}
        </select>
        <span style="width:300px;" class="comment">{$_lang.snippet_import_params_msg}</span>
    </td>
</tr>
<tr class="odd">
    <th><label for="properties">{$_lang.snippet_properties}</label></th>
    <td class="x-form-element">
        <input name="properties" id="properties" type="text" value="{$snippet->properties}" />
    </td>
</tr>
<tr id="displayparamrow">
    <th>&nbsp;</th>
    <td id="displayparams">&nbsp;</td>
  </tr>
</tbody>
</table>