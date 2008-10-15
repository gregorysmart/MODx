
<table class="classy">
<tbody>
{modblock name='namepw'}{/modblock}
<tr>
	<th style="width: 11em;"><label for="fullname">{$_lang.user_full_name}</label></th>
	<td class="x-form-element">
		<input name="fullname" id="fullname2" type="text" value="{$user->profile->fullname}" />
		<span id="fullname_error" class="error"></span>
	</td>
</tr>
<tr class="odd">
	<th><label for="email">{$_lang.user_email}</label></th>
	<td class="x-form-element">
		<input name="email" id="email2" type="text" value="{$user->profile->email}" />
		<span id="email_error" class="error"></span>
	</td>
</tr>
<tr>
	<th><label for="role">{$_lang.user_role}</label></th>
	<td class="x-form-element">
	    <div id="role2" name="role2"></div>
		<span id="role_error" class="error"></span>
	</td>
</tr>
<tr class="odd">
	<th><label for="phone">{$_lang.user_phone}</label></th>
	<td class="x-form-element">
		<input name="phone" id="phone2" type="text" value="{$user->profile->phone}" />
		<br /><span id="phone_error" class="error"></span>
	</td>
</tr>
<tr>
	<th><label for="mobilephone">{$_lang.user_mobile}</label></th>
	<td class="x-form-element">
		<input name="mobilephone" id="mobilephone2" type="text" value="{$user->profile->mobilephone}" />
		<br /><span id="mobilephone_error" class="error"></span>
	</td>
</tr>		  
<tr class="odd">	  
	<th><label for="fax">{$_lang.user_fax}</label></th>
	<td class="x-form-element">
		<input name="fax" id="fax2" type="text" value="{$user->profile->fax}" />
		<br /><span id="fax_error" class="error"></span>
	</td>
</tr>
<tr>
	<th><label for="state">{$_lang.user_state}</label></th>
	<td class="x-form-element">
		<input name="state" id="state2" type="text" value="{$user->profile->state}" />
		<br /><span id="state_error" class="error"></span>
	</td>
</tr>
<tr class="odd">
	<th><label for="zip">{$_lang.user_zip}</label></th>
	<td class="x-form-element">
		<input type="text" name="zip" id="zip2" class="textfield" modx:width="300" value="{$user->profile->zip}" onchange="documentDirty=true;" />
		<br /><span id="zip_error" class="error"></span>
	</td>
</tr>
<tr>
	<th><label for="country">{$_lang.user_country}</label></th>
	<td class="x-form-element">
		<select name="country" id="country2">
			<option value=""></option>
			{foreach from=$_country_lang key=key item=country}
				<option value="{$key}" {if $user->profile->country EQ $key}selected="selected"{/if}>{$country}</option>
			{/foreach}
		</select>
		<span id="country_error" class="error"></span>
	</td>
</tr>
<tr class="odd">
	<th><label for="dob">{$_lang.user_dob}</label></th>
	<td class="x-form-element">
		<input name="dob" id="dob2" type="text"
		 value="{if $user->profile->dob}{$user->profile->dob|date_format:'%m-%d-%Y'}{/if}" />
		<span id="dob_error" class="error"></span>
	</td>
</tr>
<tr>
	<th><label for="gender">{$_lang.user_gender}</label></th>
	<td class="x-form-element">
		<select name="gender" id="gender2">
			<option value=""></option>
			<option value="1" {if $user->profile->gender EQ 1}selected="selected"{/if}>{$_lang.user_male}</option>
			<option value="2" {if $user->profile->gender EQ 2}selected="selected"{/if}>{$_lang.user_female}</option>
		</select>
		<span id="gender_error" class="error"></span>
	</td>
</tr>
<tr class="odd">
	<th><label for="comment">{$_lang.comment}</label></th>
	<td class="x-form-element">
		<textarea name="comment" id="comment2" rows="5">{$user->comment}</textarea>
		<br /><span id="comment_error" class="error"></span>
	</td>
</tr>
<tr>
	<th>{$_lang.user_logincount}</th>
	<td class="x-form-element">{$user->profile->logincount|default:0}</td>
</tr>
<tr class="odd">
	<th>{$_lang.user_prevlogin}</th>
	<td class="x-form-element">{$user->profile->lastlogin+$_config.server_offset_time|date_format:'%d-%m-%y %H:%M:%S'}</td>
</tr>
<tr>
	<th><label for="failedlogincount">{$_lang.user_failedlogincount}</label></th>
	<td class="x-form-element">
		<input type="hidden" name="failedlogincount" id="failedlogincount" onchange="documentDirty=true;" value="{$user->profile->failedlogincount|default:0}" />
		<span id="failed">{$user->profile->failedlogincount|default:0}</span>
		&nbsp;
		[<a href="javascript:resetFailed()">{$_lang.reset_failedlogins}</a>]
	</td>
</tr>
<tr class="odd">
	<th><label for="blocked">{$_lang.user_block}</label></th>
	<td class="x-form-element">
		<input name="blocked" id="blocked" type="checkbox" 
			{if $user->profile->blocked EQ 1
			  || ($user->profile->blockeduntil GT time() 
			  AND $user->profile->blockeduntil NEQ 0)
			}checked="checked"{/if}
		/>
		<span id="blocked_error" class="error"></span>
	</td>
</tr>
<tr>
	<th><label for="blockeduntil">{$_lang.user_blockeduntil}</label></th>
	<td class="x-form-element">
		<input type="text" name="blockeduntil" id="blockeduntil" value="{if $user->profile->blockeduntil}{$user->profile->blockeduntil|date_format:'%d-%m-%Y %H:%M:%S'}{/if}" />
		<span id="blockeduntil_error" class="error"></span>
	</td>
</tr>
<tr class="odd">
	<th><label for="blockedafter">{$_lang.user_blockedafter}</label></th>
	<td class="x-form-element">
		<input type="text" name="blockedafter" id="blockedafter" value="{if $user->profile->blockedafter}{$user->profile->blockedafter|date_format:'%d-%m-%Y %H:%M:%S'}{/if}" />
		<span id="blockedafter_error" class="error"></span>
	</td>
</tr>
</tbody>
</table>
{if $user->id EQ $modx->getLoginUserID()}
	{$_lang.user_edit_self_msg}
{/if}

{modblock name='otherJS'}{/modblock}