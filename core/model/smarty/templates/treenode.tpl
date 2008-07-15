
{foreach from=$documents item=document}
	{if NOT $document->isfolder}
		<div id="node{$document->id}" p="{$document->parent}" style="white-space: nowrap;">
			{$spacer}{$pad}
			<img id="p{$document->id}" 
				 align="absmiddle"
				 title="{$_lang.click_to_context}"
				 style="cursor: pointer;"
				 src="media/style/{$_config.manager_theme}/images/tree/{$document->get('icon')}.gif"
				 width="18"
				 height="18"
				 onclick="showPopup({$document->id},'{$document->pagetitle|escape:'javascript'}',event); return false;"
				 oncontextmenu="this.onclick(event); return false;"
				 onmouseover="setCNS(this,1);"
				 onmouseout="setCNS(this,0);"
				 onmousedown="itemToChange={$document->id}; selectedObjectName='{$document->pagetitle|escape:'javascript'}'; selectedObjectDeleted='{$document->deleted}'" />&nbsp;
			<span p="{$document->parent}"
				  onclick="treeAction({$document->id},'{$document->pagetitle|escape:'javascript'}'); setSelected(this);"
				  onmouseover="setHoverClass(this,1);"
				  onmouseout="setHoverClass(this,0);"
				  class="treeNode"
				  onmousedown="itemToChange={$document->id}; selectedObjectName='{$document->pagetitle|escape:'javascript'}'; selectedObjectDeleted={$document->deleted};"
				  oncontextmenu="$('p{$document->id}').onclick(event); return false;"
				  title="{$document->get('alt')}">
					{if $document->deleted EQ 1}
						<span class="deletedNode">{$document->pagetitle}</span>
					{elseif $document->published EQ 0}
						<span class="unpublishedNode">{$document->pagetitle}</span>
					{elseif $document->hidemenu EQ 1}
						<span class="notInMenuNode">{$document->pagetitle}</span>
					{else}
						<span class="publishedNode">{$document->pagetitle}</span>
					{/if}
					{if $document->type EQ 'reference'}
						&nbsp;<img src="{$_style.tree_linkgo}" width="16" height="16" />
					{/if}
				  </span>
			<small>{if $_config.manager_direction EQ 'rtl'}&rlm;{/if}({$document->id})</small>
		</div>
	{else}
		{if $expandAll EQ 1 OR ($expandAll EQ 2 AND in_array($document->id,$opened))}
			<div id="node{$document->id}"
				 p="{$document->parent}"
				 style="white-space: nowrap;">
				 {$spacer}
				 <img id="s{$document->id}"
				 	  align="absmiddle"
					  style="cursor: pointer;"
					  src="{$_style.tree_minusnode}"
					  width="18"
					  height="18"
					  onclick="toggleNode({$document->id},{$indent+1},{$document->id},0,{if $document->privateweb EQ 1 OR $document->privatemgr EQ 1}1{else}0{/if}); return false;"
					  oncontextmenu="this.onclick(event); return false;"
				/>
				&nbsp;
				<img id="f{$document->id}"
					 align="absmiddle"
					 title="{$_lang.click_to_context}"
					 style="cursor: pointer;"
					 src="{if $document->privateweb EQ 1 OR $document->privatemgr EQ 1}{$_style.tree_folderopen_secure}{else}{$_style.tree_folderopen}{/if}"
					 width="18"
					 height="18"
					 onclick="showPopup({$document->id},'{$document->pagetitle|escape:'javascript'}',event); return false;"
					 oncontextmenu="this.onclick(event); return false;"
					 onmouseover="setCNS(this,1);"
					 onmouseout="setCNS(this,0);"
					 onmousedown="itemToChange={$document->id}; selectedObjectName='{$document->pagetitle|escape:'javascript'}'; selectedObjectDeleted={$document->deleted}" />
				&nbsp;
				<span onclick="treeAction({$document->id},'{$document->pagetitle|escape:'javascript'})'); setSelected(this);"
					  onmouseover="setHoverClass(this,1);"
					  onmouseout="setHoverClass(this,0);"
					  class="treeNode"
					  onmousedown="itemToChange='{$document->id}'; selectedObjectName='{$document->pagetitle|escape:'javascript'}'; selectedObjectDeleted={$document->deleted}"
					  oncontextmenu="$('f{$document->id}').onclick(event); return false;"
					  title="{$document->get('alt')}">
					{if $document->deleted EQ 1}
						<span class="deletedNode">{$document->pagetitle}</span>
					{elseif $document->published EQ 0}
						<span class="unpublishedNode">{$document->pagetitle}</span>
					{elseif $document->hidemenu EQ 1}
						<span class="notInMenuNode">{$document->pagetitle}</span>
					{else}
						<span class="publishedNode">{$document->pagetitle}</span>
					{/if}
					{if $document->type EQ 'reference'}
						&nbsp;<img src="{$_style.tree_linkgo}" width="16" height="16" />
					{/if}
				</span>
				<small>{if $_config.manager_direction EQ 'rtl'}&rlm;{/if}({$document->id})</small>
			<div id="node{$document->id}_holder" style="display:block;">{constructTreeNode indent=$new_indent parent=$document->id expandAll=$expandAll}</div>
			</div>
		{else}
			<div id="node{$document->id}"
				 p="{$document->parent}"
				 style="white-space: nowrap;">
				{$spacer}
				<img id="s{$document->id}"
				 	  align="absmiddle"
					  style="cursor: pointer;"
					  src="{$_style.tree_plusnode}"
					  width="18"
					  height="18"
					  onclick="toggleNode({$document->id},{$indent+1},{$document->id},0,{if $document->privateweb EQ 1 OR $document->privatemgr EQ 1}1{else}0{/if}); return false;"
					  oncontextmenu="this.onclick(event); return false;"
				/>
				&nbsp;
				<img id="f{$document->id}"
					 title="{$_lang.click_to_context}"
					 align="absmiddle"
					 style="cursor: pointer;"
					 src="{if $document->privateweb EQ 1 OR $document->privatemgr EQ 1}{$_style.tree_folder_secure}{else}{$_style.tree_folder}{/if}"
					 width="18"
					 height="18"
					 onclick="showPopup({$document->id},'{$document->pagetitle|escape:'javascript'}',event); return false;"
					 oncontextmenu="this.onclick(event); return false;"
					 onmouseover="setCNS(this,1);"
					 onmouseout="setCNS(this,0);"
					 onmousedown="itemToChange={$document->id}; selectedObjectName='{$document->pagetitle}'; selectedObjectDeleted={$document->deleted}" />
				 &nbsp;
				 <span onclick="treeAction({$document->id},'{$document->pagetitle|escape:'javascript'}'); setSelected(this);"
					   onmouseover="setHoverClass(this,1);"
					   onmouseout="setHoverClass(this,0);"
					   class="treeNode"
					   onmousedown="itemToChange={$document->id}; selectedObjectName='{$document->pagetitle|escape:'javascript'}'; selectedObjectDeleted={$document->deleted};"
					   oncontextmenu="$(f{$document->id}').onclick(event); return false;"
					   title="{$document->get('alt')}">
					{if $document->deleted EQ 1}
						<span class="deletedNode">{$document->pagetitle}</span>
					{elseif $document->published EQ 0}
						<span class="unpublishedNode">{$document->pagetitle}</span>
					{elseif $document->hidemenu EQ 1}
						<span class="notInMenuNode">{$document->pagetitle}</span>
					{else}
						<span class="publishedNode">{$document->pagetitle}</span>
					{/if}
					{if $document->type EQ 'reference'}
						&nbsp;<img src="{$_style.tree_linkgo}" width="16" height="16" />
					{/if}
				</span>
				<small>{if $_config.manager_direction EQ 'rtl'}&rlm;{/if}({$document->id})</small>
			
				<div id="node{$document->id}_holder" style="display:none"></div>
			</div>
		{/if}
	{/if}
	{if $expandAll EQ 1}
		<script type="text/javascript">
		{foreach from=$opened2 item=i}
			parent.openedArray[{$i}] = 1;
		{/foreach}
		</script>
	{elseif $expandAll EQ 0}
		<script type="text/javascript">
		{foreach from=$closed2 item=i}
			parent.openedArray[{$i}] = 0;
		{/foreach}
		</script>
	{/if}
{foreachelse}
	<div style="white-space: nowrap;">
		{$spacer}{$pad}
		<img align="absmiddle" src="{$_style.tree_deletedpage}" width="18" height="18" />
		&nbsp;<span class="emptyNode">{$_lang.empty_folder}</span>
	</div>
{/foreach}