/**
 * Loads the welcome page
 * 
 * @class MODx.page.Welcome
 * @extends MODx.Component
 * @param {Object} config An object of configuration options
 * @xtype page-welcome
 */
MODx.page.Welcome = function(config) {
	config = config || {}; 
	Ext.applyIf(config,{
		tabs_div: 'welcome_tabs'
		,tabs: this.getTabs(config)
        ,tabOptions: {
            autoScroll: true
            ,height: 300
            ,minTabWidth: 130
        }
        ,components: [{
            xtype: 'grid-user-recent-resource'
            ,renderTo: 'grid-recent-resource'
            ,user: config.user
        }]
	});
    MODx.page.Welcome.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.Welcome,MODx.Component,{
	getTabs: function(config) {
		var items = [];
		if (config.config_display) {
			items.push({ contentEl: 'config' ,title: _('configcheck_title') });
		}
		items.push(
            { contentEl: 'news' ,title: _('modx_news') }
            ,{ contentEl: 'security' ,title: _('security_notices') }
            ,{ contentEl: 'recent' ,title: _('recent_docs') }
            ,{ contentEl: 'info' ,title: _('info') }
            ,{ contentEl: 'online' ,title: _('online') }
		);
        return items;
	}
});
Ext.reg('page-welcome',MODx.page.Welcome);