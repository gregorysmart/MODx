Ext.onReady(function() {
	MODx.load({ xtype: 'page-help' });
});
/**
 * Loads the help page
 * 
 * @class MODx.page.Help
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype page-help
 */
MODx.page.Help = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		tabs: [
            {contentEl: 'about', title: _('about_title')}
            ,{contentEl: 'help', title: _('help_title')}
            ,{contentEl: 'credits', title: _('credits')}
        ]
	});
	MODx.page.Help.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.Help,MODx.Component);
Ext.reg('page-help',MODx.page.Help);