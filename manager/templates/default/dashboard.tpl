<div id="modx-dashboard" class="modx-dashboard">

<div id="db-header" class="section">
	<h2>Dashboard - <span class="site-title">{$_config.site_name}</span></h2>
	
	<p>Welcome back, {$modx->getLoginUserName()}.</p>
</div>

<div id="db-panel">
    {include file="welcome.tpl"}
</div>


<div id="db-quicklinks" class="section">
    <h2>Quicklinks</h2>

    <ul>
        <li><a href="javascript:;" onclick="MODx.loadFrame(44);">Create Document</a></li>
        <li><a href="javascript:;" onclick="MODx.hideDashboard();">View Tree</a></li>
        <li><a href="javascript:;" onclick="MODx.loadFrame(54);">Add User</a></li>
    </ul>
</div>

<div id="db-help" class="section">
    <h2>Help</h2>
    
    <ul>
        <li><a href="http://svn.modxcms.com/docs/" target="_blank">Documentation</a></li>
        <li><a href="http://www.modxcms.com/forums/" target="_blank">Forums</a></li>
        <li><a href="http://svn.modxcms.com/jira/" target="_blank">File a bug</a></li>
    </ul>
</div>

</div>