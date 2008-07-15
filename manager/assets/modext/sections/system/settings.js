Ext.namespace('MODx');
Ext.onReady(function() {
    MODx.load({ xtype: 'modx-settings' });
});
/**
 * Loads the configuration page
 * 
 * @class MODx.Settings
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-settings
 */
MODx.Settings = function(config) {
	config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'grid-system-settings'
            ,renderTo: 'settings_grid'
            ,height: 525
            ,autoHeight: Ext.isSafari ? false : true
        }]
    });
	MODx.Settings.superclass.constructor.call(this,config);
};
Ext.extend(MODx.Settings,MODx.Component);
Ext.reg('modx-settings',MODx.Settings);