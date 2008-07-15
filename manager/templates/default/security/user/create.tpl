{extends file='security/user/mutate.tpl'}


{modblock name='ab'}
<script type="text/javascript" src="assets/modext/sections/security/user/create.js"></script>
{/modblock}

{modblock name='namepw'}
<tr>
	<th><label for="new_user_name">{$_lang.username}</label></th>
	<td class="x-form-element">
		<input name="newusername" id="new_user_name"
			type="text" class="textfield"
			value=""
			onchange="documentDirty=true;"
			modx:maxlength="100"
			modx:allowblank="0"
		/>
		<br /><span id="new_user_name_error" class="error"></span>
	</td>
</tr>
<tr>
	<th><label for="newpasswordcheck">{$_lang.password}</label></th>
	<td>
	<div id="pwg">
		<input type="hidden" name="newpassword" id="newpassword" class="hidden" value="1" />
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
		<label style="width:120px">
			{$_lang.change_password_new}:
			<input name="specifiedpassword" id="password"
				type="password" class="textfield"
				onchange="documentdirty=true;"
				modx:inputtype="password"
				modx:width="100"
			/>
		</label>
		<br />
		
		<label style="width:120px">
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