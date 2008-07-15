Ext.onReady(function() {
	MODx.load({ xtype: 'modx-help' });
});
/**
 * Loads the help page
 * 
 * @class MODx.Help
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-help
 */
MODx.Help = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		tabs: [
            {contentEl: 'about', title: _('about_title')}
            ,{contentEl: 'help', title: _('help_title')}
            ,{contentEl: 'credits', title: _('credits')}
        ]
	});
	MODx.Help.superclass.constructor.call(this,config);
};
Ext.extend(MODx.Help,MODx.Component);
Ext.reg('modx-help',MODx.Help);