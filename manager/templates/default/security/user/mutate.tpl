{modblock name='ab'}{/modblock}

{$onUserFormPrerender}
<div id="modx-panel-user"></div>

<script type="text/javascript" src="assets/modext/widgets/security/modx.grid.user.group.js"></script>
<script type="text/javascript" src="assets/modext/widgets/security/modx.panel.user.js"></script>

<div class="padding x-hide-display" id="tab_access">	
	{include file='security/user/sections/access.tpl'}
</div>