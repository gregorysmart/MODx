<div id="tabs_div">

<div id="tab_pairing" class="padding x-hide-display">
	<h2>{$_lang.user_groups}</h2>
	
	<p>	
		{$_lang.user_group_management_msg} 
	</p>
	<br />
	
	<div id="modx_ugtree" class="tree" style="width: 300px;"></div>
	
	<br style="clear: left;" />
</div>

<div id="tab_dg" class="padding x-hide-display">
	<h2>{$_lang.document_groups}</h2>
	
	<p>
		Drag documents into document groups here.
	</p>
	<br />
	
	<div id="modx_doctree" class="tree" style="float: left; margin: 1em; width: 300px;"></div>
	
	<div id="modx_dgtree" class="tree" style="float: left; margin: 1em; width: 300px;"></div>
	
	<br style="clear: left;" />
</div>

<div id="tab_roles" class="padding x-hide-display">
    <h2>{$_lang.roles}</h2>
    
	<p>{$_lang.role_management_msg}</p>
	<br /><br />
	<div id="modx_rolegrid" class="grid"></div>
</div>
</div>

<script type="text/javascript" src="assets/modext/widgets/resource/modx.tree.resource.js"></script>
<script type="text/javascript" src="assets/modext/widgets/security/modx.tree.user.group.js"></script>
<script type="text/javascript" src="assets/modext/widgets/security/modx.tree.resource.group.js"></script>
<script type="text/javascript" src="assets/modext/widgets/security/modx.grid.role.js"></script>
<script type="text/javascript" src="assets/modext/sections/security/permissions/list.js"></script>