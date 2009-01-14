<div id="modx-dashboard" class="modx-dashboard">

<div id="db-leftpanel">
<div id="db-header" class="section">
	<h2>{$_lang.dashboard} - <span class="site-title">{$_config.site_name}</span></h2>
	
	<p>{$welcome_back}</p>
</div>

<div id="db-quicklinks" class="section">
    <h2>{$_lang.quicklinks}</h2>

    <ul>
        <li><a href="javascript:;" onclick="MODx.loadFrame(44);">{$_lang.create_resource}</a></li>
        <li><a href="javascript:;" onclick="MODx.hideDashboard();">{$_lang.view_tree}</a></li>
        <li><a href="javascript:;" onclick="MODx.loadFrame(54);">{$_lang.add_user}</a></li>
    </ul>
</div>

<div id="db-help" class="section">
    <h2>{$_lang.help}</h2>
    
    <ul>
        <li><a href="http://svn.modxcms.com/docs/" target="_blank">{$_lang.documentation}</a></li>
        <li><a href="http://www.modxcms.com/forums/" target="_blank">{$_lang.forums}</a></li>
        <li><a href="http://svn.modxcms.com/jira/" target="_blank">{$_lang.file_bug}</a></li>
    </ul>
</div>
</div>

<div id="db-panel">
    {include file="welcome.tpl"}
</div>

</div>