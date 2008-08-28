{extends file='security/user/mutate.tpl'}


{modblock name='ab'}
<script type="text/javascript" src="assets/modext/widgets/modx.grid.settings.js"></script>
<script type="text/javascript" src="assets/modext/widgets/security/modx.grid.user.settings.js"></script>
<script type="text/javascript" src="assets/modext/sections/security/user/update.js"></script>
{literal}
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
	MODx.load({
	{/literal}
		xtype: 'user-update'
		,id: '{$user->id}'
		,manager_language: '{$user->get("language")}'
		,which_editor: '{$user->settings.which_editor}'
	{literal}
	});
});
// ]]>
</script>
{/literal}
{/modblock}




{modblock name='namepw'}
{if $user->profile->blocked 
	|| ($user->profile->blockeduntil GT time() AND $user->profile->blockeduntil NEQ 0) 
	|| $user->profile->failedlogins GT 3}
<tr>
	<td colspan="3">
		<span id="blocked" class="warning">
			<strong>{$_lang.user_is_blocked}</strong>
		</span>
		<br />
	</td>
</tr>
{/if}
<tr id="showname">
	<td colspan="2">
		<img src="media/style/{$_config.manager_theme}/images/icons/user.gif" alt="." />
		&nbsp;
		<strong>{$user->username}</strong>
		 - <a href="javascript:;" onclick="changeName();return false;">{$_lang.change_name}</a>
	</td>
</tr>
<tr id="editname" style="display:none;">
	<th><label for="new_user_name">{$_lang.username}</label></th>
	<td>
		<input name="newusername" id="new_user_name"
			 type="text" class="textfield"
			 value="{$user->username}"
			 onchange="documentDirty=true;"
		/>
		<br /><span id="new_user_name_error" class="error"></span>
	</td>
</tr>
<tr>
	<th><label for="newpassword">{$_lang.change_password_new}</label></th>
	<td>
		<input name="newpassword" id="newpassword"
			type="checkbox"
			onclick="toggleNewPassword();"
		/>
	
	<div id="pwg">
		
		<label class="warning">{$_lang.password_method}</label>
		<span id="password_notify_method_error" class="error"></span>
		<br />	
		<label>
			<input type="radio" name="passwordnotifymethod" id="password_notify_method_e" value="e" />
			{$_lang.password_method_email}
		</label>
		<br />
		<label>
			<input type="radio" name="passwordnotifymethod" id="password_notify_method_s" value="s" checked="checked" />
			{$_lang.password_method_screen}
		</label>
			
		<br /><br />
		
		
		<label class="warning">{$_lang.password_gen_method}</label>
		<span id="password_generation_method_error" class="error"></span>
		<br />
		<label>
			<input type="radio" name="passwordgenmethod" id="password_generation_method" value="g" checked="checked" onclick="{literal}Ext.get('specpassword').slideOut('t',{useDisplay:true});{/literal}" />
			{$_lang.password_gen_gen}
		</label>
		<br />
		<label>
			<input type="radio" name="passwordgenmethod" value="spec" onclick="{literal}Ext.get('specpassword').slideIn('t',{useDisplay:true});{/literal}" />
			{$_lang.password_gen_specify}
		</label>		
		
		
	</div>
	<div id="specpassword">
		<span id="password_error" class="error"></span><br />
		<label style="width:120px" class="x-form-element">
			{$_lang.change_password_new}:
			<input name="specifiedpassword" id="password"
				type="password" class="textfield"
				modx:inputtype="password"
				modx:width="100"
				onchange="documentdirty=true;"
			/>
		</label>
		<br />
		
		<label style="width:120px" class="x-form-element">
			{$_lang.change_password_confirm}:
			<input name="confirmpassword" id="password_confirm"
				type="password" class="textfield"
				modx:inputtype="password"
				modx:width="100"
				onchange="documentdirty=true;"
			/>
		</label>
		<br />
		
		<span class="warning" style="font-weight:normal; font-size: x-small;">{$_lang.password_gen_length}</span>
	</div>
	</td>
</tr>
{/modblock}