/**
 * Loads a grid of System Settings
 * 
 * @class MODx.grid.SystemSettings
 * @extends MODx.grid.Grid
 * @constructor
 * @param {Object} config An object of options.
 * @xtype grid-system-settings
 */
MODx.grid.SystemSettings = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('system_settings')
        ,url: MODx.config.connectors_url+'system/settings.php'
    });
    MODx.grid.SystemSettings.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.SystemSettings,MODx.grid.SettingsGrid);
Ext.reg('grid-system-settings',MODx.grid.SystemSettings);