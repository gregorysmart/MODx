Ext.namespace('MODx','MODx.grid');
/**
 * Loads a grid of Context Settings
 * 
 * @class MODx.grid.ContextSettings
 * @extends MODx.grid.Grid
 * @constructor
 * @param {Object} config An object of options.
 * @xtype grid-context-settings
 */
MODx.grid.ContextSettings = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('context_settings')
        ,url: MODx.config.connectors_url+'context/setting.php'
        ,baseParams: {
            action: 'getList'
            ,context_key: config.context_key
        }
        ,saveParams: {
            context_key: config.context_key
        }
        ,tbar: [{
            text: _('create_new')
            ,scope: this
            ,handler: { 
                xtype: 'window-setting-create'
                ,url: MODx.config.connectors_url+'context/setting.php'
                ,fk: config.context_key
            }
        }]
    });
    MODx.grid.ContextSettings.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.ContextSettings,MODx.grid.SettingsGrid);
Ext.reg('grid-context-settings',MODx.grid.ContextSettings);