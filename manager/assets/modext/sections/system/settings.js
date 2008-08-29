Ext.onReady(function() {
    MODx.load({ xtype: 'page-settings' });
});
/**
 * Loads the configuration page
 * 
 * @class MODx.page.Settings
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype page-settings
 */
MODx.page.Settings = function(config) {
	config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'grid-system-settings'
            ,renderTo: 'settings_grid'
            ,height: 525
            ,autoHeight: Ext.isSafari ? false : true
        }]
    });
	MODx.page.Settings.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.Settings,MODx.Component);
Ext.reg('page-settings',MODx.page.Settings);