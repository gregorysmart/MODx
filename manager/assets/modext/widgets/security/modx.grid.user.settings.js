/**
 * Loads a grid of User Settings
 * 
 * @class MODx.grid.UserSettings
 * @extends MODx.grid.Grid
 * @constructor
 * @param {Object} config An object of options.
 * @xtype grid-user-settings
 */
MODx.grid.UserSettings = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('user_settings')
        ,url: MODx.config.connectors_url+'security/user/setting.php'
        ,baseParams: {
            action: 'getList'
            ,user: config.user
        }
        ,saveParams: {
            user: config.user
        }
        ,tbar: [{
            text: _('create_new')
            ,scope: this
            ,handler: { 
                xtype: 'window-setting-create'
                ,url: MODx.config.connectors_url+'security/user/setting.php'
                ,fk: config.user
            }
        }]
    });
    MODx.grid.UserSettings.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.UserSettings,MODx.grid.SettingsGrid);
Ext.reg('grid-user-settings',MODx.grid.UserSettings);