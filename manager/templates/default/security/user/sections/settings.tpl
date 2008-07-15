
<table class="classy">
<tbody>
<tr>
	<th style="width: 11em"><label for="manager_language">{$_lang.language_title}</label></th>
	<td class="x-form-element">
		<input name="manager_language" id="manager_language" type="text" onchange="documentDirty=true;" />
		<span class="comment">{$_lang.language_message}</span>
		<br /><span id="manager_language_error" class="error"></span>
	</td>
</tr>
<tr class="odd">
	<th><label for="manager_direction">{$_lang.manager_direction_title}</label></th>
	<td class="x-form-element">
		<select name="manager_direction" id="manager_direction" class="combobox" modx:width="50" onchange="documentDirty=true;">
			<option value="ltr" {if $user->settings.manager_direction EQ 'ltr'}selected="selected"{/if}>ltr</option>
			<option value="rtl" {if $user->settings.manager_direction EQ 'rtl'}selected="selected"{/if}>rtl</option>
		 </select>
		 <span class="comment">{$_lang.manager_direction_message}</span>
		 <br /><span id="manager_direction_error" class="error"></span>
	</td>
</tr>
<tr>
	<th><label for="manager_lang_attribute">{$_lang.manager_lang_attribute_title}</label></th>
	<td class="x-form-element">
		<input type="text" name="manager_lang_attribute" id="manager_lang_attribute" class="textfield" modx:width="75" onchange="documentDirty=true;" value="{$user->settings.manager_lang_attribute|default:'en'}" />
		<br /><span class="comment">{$_lang.manager_lang_attribute_message}</span>
		<br /><span id="manager_lang_attribute_error" class="error"></span>
	</td>
</tr>
<tr class="odd">
	<th><label for="manager_login_startup">{$_lang.mgr_login_start}</label></th>
	<td class="x-form-element">
		<input name="manager_login_startup" id="manager_login_startup" type="text" class="textfield" modx:width="75" modx:maxlength="255" onchange="documentDirty=true;" value="{$user->settings.manager_login_startup}" />
		<br /><span class="comment">{$_lang.mgr_login_start_message}</span>
		<br /><span id="manager_login_startup_error" class="error"></span>
	</td>
</tr>
<tr>
	<th><label>{$_lang.allow_mgr_access}</label></th>
	<td class="x-form-element">
		<label>
			<input type="radio" name="allow_manager_access" id="allow_manager_access" class="radio" value="1" onchange="documentDirty=true;" {if $user->settings.allow_manager_access EQ 1}checked="checked"{/if} />
			{$_lang.yes}
		</label>
		<br />
		<label>
			<input type="radio" name="allow_manager_access" id="allow_manager_access_no" class="radio" value="0" onchange="documentDirty=true;" {if $user->settings.allow_manager_access EQ 0}checked="checked"{/if} />
			{$_lang.no}
		</label>
		<br /><span class="comment">{$_lang.allow_mgr_access_message}</span>
		<br /><span id="allow_manager_access_error" class="error"></span>
	</td>
</tr>
<tr class="odd">
	<th><label for="allowed_ip">{$_lang.login_allowed_ip}</label></th>
	<td class="x-form-element">
		<input name="allowed_ip" id="allowed_ip" type="text" class="textfield" modx:width="300" onchange="documentDirty=true;" value="{$user->settings.allowed_ip}" />
		<br /><span class="comment">{$_lang.login_allowed_ip_message}</span>
		<br /><span id="allowed_ip_error" class="error"></span>
	</td>
</tr> 
<tr>
	<th><label>{$_lang.login_allowed_days}</label></th>
	<td class="x-form-element">
		<label>
			<input name="allowed_days[]" id="allowed_days" type="checkbox" onchange="documentDirty=true;" value="1" {if strpos($user->settings.allowed_days,1) !== false}checked="checked"{/if} /> 
			{$_lang.sunday}
		</label><br />
		
		<label>
			<input name="allowed_days[]" type="checkbox" onchange="documentDirty=true;" value="2" {if strpos($user->settings.allowed_days,2) !== false}checked="checked"{/if} /> 
			{$_lang.monday}
		</label><br />
		
		<label>
			<input name="allowed_days[]" type="checkbox" onchange="documentDirty=true;" value="3" {if strpos($user->settings.allowed_days,3) !== false}checked="checked"{/if} /> 
			{$_lang.tuesday}
		</label><br />
		
		<label>
			<input name="allowed_days[]" type="checkbox" onchange="documentDirty=true;" value="4" {if strpos($user->settings.allowed_days,4) !== false}checked="checked"{/if} /> 
			{$_lang.wednesday}
		</label><br />
		
		<label>
			<input name="allowed_days[]" type="checkbox" onchange="documentDirty=true;" value="5" {if strpos($user->settings.allowed_days,5) !== false}checked="checked"{/if} /> 
			{$_lang.thursday}
		</label><br />
		
		<label>
			<input name="allowed_days[]" type="checkbox" onchange="documentDirty=true;" value="6" {if strpos($user->settings.allowed_days,6) !== false}checked="checked"{/if} /> 
			{$_lang.friday}
		</label><br />
		
		<label>
			<input name="allowed_days[]" type="checkbox" onchange="documentDirty=true;" value="7" {if strpos($user->settings.allowed_days,7) !== false}checked="checked"{/if} /> 
			{$_lang.saturday}
		</label>
		<br /><span class="comment">{$_lang.login_allowed_days_message}</span>
		<br /><span id="allowed_days_error" class="error"></span>
	</td>
</tr>
<tr class="odd">
	<th><label for="manager_theme">{$_lang.manager_theme}</label></th>
	<td class="x-form-element">
		<select name="manager_theme" id="manager_theme" class="combobox" modx:width="150" onchange="documentDirty=true;$('uf').theme_refresher.value = Date.parse(new Date());">
			<option value=""></option>
		{foreach from=$themes key=val item=theme}
		 	<option value="{$val}" {if $val EQ $user->settings.manager_theme} selected="selected"{/if}>{$theme}</option>
		{/foreach}
		</select>
		<span class="comment">{$_lang.manager_theme_message}</span>
		<br /><span id="manager_theme_error" class="error"></span>
	</td>
</tr>
<tr>
	<th><label for="filemanager_path">{$_lang.filemanager_path_title}</label></th>
	<td class="x-form-element">
		<input name="filemanager_path" id="filemanager_path" type="text" class="textfield" modx:width="300" onchange="documentDirty=true;" value="{$user->settings.filemanager_path}" />
		<br /><span class="comment">{$_lang.filemanager_path_message}</span>
		<br /><span id="filemanager_path_error" class="error"></span>
	</td>
</tr>
<tr class="odd">
	<th><label for="upload_images">{$_lang.uploadable_images_title}</label></th>
	<td class="x-form-element">
		  <input name="upload_images" id="upload_images" type="text" class="textfield" modx:width="275" onchange="documentDirty=true;" value="{$user->settings.upload_images}" />
		  &nbsp;&nbsp;
		  <input type="checkbox" name="default_upload_images" id="default_upload_images" value="1" onchange="documentDirty=true;" {if NOT $user->settings.upload_images}checked="checked"{/if} />
		  {$_lang.user_use_config}
		  <br /><span class="comment">{$_lang.uploadable_images_message} {$_lang.user_upload_message}</span>
		  <br /><span id="upload_images_error" class="error"></span>  
	</td>
</tr>
<tr>
	<th><label for="upload_media">{$_lang.uploadable_media_title}</label></th>
	<td class="x-form-element">
		<input name="upload_media" id="upload_media" type="text" class="textfield" modx:width="275" onchange="documentDirty=true;" value="{$user->settings.upload_media}" />
		&nbsp;&nbsp; 
		<input type="checkbox" name="default_upload_media" id="default_upload_media" value="1" onchange="documentDirty=true;" {if NOT $user->settings.upload_media}checked="checked"{/if} />
		{$_lang.user_use_config}
		<br /><span class="comment">{$_lang.uploadable_media_message} {$_lang.user_upload_message}</span>
		<br /><span id="upload_media_error" class="error"></span>
	</td>
</tr>
<tr class="odd">
	<th><label for="upload_flash">{$_lang.uploadable_flash_title}</label></th>
	<td class="x-form-element">
		<input name="upload_flash" id="upload_flash" type="text" class="textfield" modx:width="275" onchange="documentDirty=true;" value="{$user->settings.upload_flash}" />
		&nbsp;&nbsp;
		<input type="checkbox" name="default_upload_flash" id="default_upload_flash" value="1" onchange="documentDirty=true;" {if NOT $user->settings.upload_flash}checked="checked"{/if} />
		{$_lang.user_use_config}
		<br /><span class="comment">{$_lang.uploadable_flash_message} {$_lang.user_upload_message}</span>
		<br /><span id="upload_flash_error" class="error"></span>
	</td>
</tr>
<tr>
	<th><label for="upload_files">{$_lang.uploadable_files_title}</label></th>
	<td class="x-form-element">
		<input name="upload_files" id="upload_files" type="text" class="textfield" modx:width="275" onchange="documentDirty=true;" value="{$user->settings.upload_files}" />
		&nbsp;&nbsp;
		<input type="checkbox" name="default_upload_files" value="1" onchange="documentDirty=true;" {if NOT $user->settings.upload_files}checked="checked"{/if} />
		{$_lang.user_use_config}
		<br /><span class="comment">{$_lang.uploadable_files_message} {$_lang.user_upload_message}</span>
		<br /><span id="upload_files_error" class="error"></span>
	</td>
</tr>
<tr class="odd">
	<th><label for="upload_maxsize">{$_lang.upload_maxsize_title}</label></th>
	<td class="x-form-element">
		  <input name="upload_maxsize" id="upload_maxsize" class="textfield" modx:width="150" type="text" onchange="documentDirty=true;" value="{$user->settings.upload_maxsize}" />
		  <br /><span class="comment">{$_lang.upload_maxsize_message}</span>
		  <br /><span id="upload_maxsize_error" class="error"></span>
	</td>
</tr>
<tr id="editorRow" style="display: {if $_config.use_editor EQ 1}{$displayStyle}{else}none{/if}; ?> ">
	<th><label for="which_editor">{$_lang.which_editor_title}</label></th>
	<td class="x-form-element">
		<input name="which_editor" id="which_editor" type="text" onchange="documentDirty=true;" />
		<span class="comment">{$_lang.which_editor_message}</span>
		<br /><span id="which_editor_error" class="error"></span>
	</td>
</tr>
<tr id="rbRow1" class="odd" style="display: {if $_config.use_browser}{$displayStyle}{else}none{/if};">
	<th><label for="rb_base_dir">{$_lang.rb_base_dir_title}</label></th>
	<td class="x-form-element">
		<input name="rb_base_dir" id="rb_base_dir" type="text" class="textfield" modx:width="300" onchange="documentDirty=true;" value="{$user->settings.rb_base_dir}" />
		<br /><span class="comment">{$_lang.rb_base_dir_message}</span>
		<br /><span id="rb_base_dir_error" class="error"></span>
	</td>
</tr>
<tr id="rbRow4" class="row3" style="display: {if $_config.use_browser}{$displayStyle}{else}none{/if};">
	<th><label for="rb_base_url">{$_lang.rb_base_url_title}</label></th>
	<td class="x-form-element">
		<input name="rb_base_url" id="rb_base_url" type="text" class="textfield" modx:width="300" onchange="documentDirty=true;" value="{$user->settings.rb_base_url}" />
		<br /><span class="comment">{$_lang.rb_base_url_message}</span>
		<br /><span id="rb_base_url_error" class="error"></span>
	</td>
</tr>
{if $onInterfaceSettingsRender NEQ ''}
<tr class="row1">
	<td colspan="2">
	{$onInterfaceSettingsRender}
	</td>
</tr>
{/if}
</tbody>
</table>