<h2>{$_lang.tv}: {$tv->name}</h2>

<p>{$_lang.tv_msg}</p>

<div class="options list">
<h3>{$_lang.options}</h3>
<ul>
    <li class="x-form-element">
        <label for="rank">{$_lang.tv_rank}</label>
        <input name="rank" id="rank" type="text"value="{$tv->rank}" />
    </li>
    <li class="x-form-element">
        <input name="locked" id="locked" type="checkbox" {if $tv->locked EQ 1} checked="checked"{/if} />
        <span>{$_lang.tv_lock_msg}</span>
    </li>
</ul>
</div>

<table class="classy">
<tbody>
<tr>
    <th style="width: 10em;"><label for="name">{$_lang.tv_name}</label></th>
    <td class="x-form-element">
        <input name="name" id="name" type="text" value="{$tv->name|escape}" />
    </td>
</tr>
<tr class="odd">
    <th><label for="caption">{$_lang.tv_caption}</label></th>
    <td class="x-form-element">
        <input name="caption" id="caption" type="text" value="{$tv->caption|escape}" />
    </td>
</tr>
<tr>
    <th><label for="description">{$_lang.tv_description}</label></th>
    <td class="x-form-element">
        <input name="description" id="description" type="text" value="{$tv->description|escape}" />
    </td>
</tr>
<tr class="odd">
    <th><label for="category">{$_lang.category}</label></th>
    <td class="x-form-element">
        <input name="category" id="category" />
    </td>
</tr>
<tr>
    <th><label for="type">{$_lang.tv_type}</label></th>
    <td class="x-form-element">
        <select name="type" id="type"></select>
    </td>
</tr>
<tr class="odd">
    <th><label for="display">{$_lang.tv_output_type}</label></th>
    <td class="x-form-element">
        <select name="display" id="display"></select>
    </td>
</tr>
<tr class="odd">
    <th><label for="elements">{$_lang.tv_elements}</label></th>
    <td class="x-form-element">
        <input name="els" id="els" type="text" value="{$tv->elements|escape}" />
        <img src="templates/{$_config.manager_theme}/images/icons/bkmanager.gif" width="17" height="18" alt="{$_lang.tmplvars_binding_msg}" onclick="alert(this.alt)" style="vertical-align: middle; cursor:hand" />
    </td>
</tr>
<tr>
    <th><label for="default_text">{$_lang.tv_default}</label></th>
    <td class="x-form-element">
        <textarea name="default_text" id="default_text">{$tv->default_text|escape}</textarea>
        <img src="templates/{$_config.manager_theme}/images/icons/bkmanager.gif" width="17" height="18" alt="{$_lang.tmplvars_binding_msg}" onclick="alert(this.alt)" style="cursor:hand" />
        <span id="default_text_error" class="error"></span>
    </td>
</tr>
<tr id="displayparamrow" style="display: none;">
    <th>
        {$_lang.tv_output_type_properties}
        <div style="padding-top:8px;">
            <a href="javascript:void(0);" onclick="resetParameters(); return false;">
                <img src="templates/{$_config.manager_theme}/images/icons/refresh.gif" width="16" height="16" alt="{$_lang.tmplvars_reset_params}" />
            </a>
        </div>
    </th>
    <td id="displayparams">&nbsp;</td>
</tr>
</tbody>
</table>