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
        ,autosave: false
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



/**
 * Update a Context Setting
 * 
 * @class MODx.window.UpdateContextSetting
 * @extends MODx.Window
 * @param {Object} config An object of config properties
 * @xtype window-context-setting-update
 */
MODx.window.UpdateContextSetting = function(config) {
    config = config || {};
    var r = config.record;
    Ext.applyIf(config,{
        title: _('setting_update')
        ,height: 200
        ,width: 550
        ,url: MODx.config.connectors_url+'context/setting.php'
        ,action: 'update'
        ,bodyStyle: 'padding: 0'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'context_key'
            ,value: r.context_key
        },{
            xtype: 'statictextfield'
            ,name: 'key'
            ,fieldLabel: _('key')
            ,allowBlank: false
            ,width: 300
            ,value: r.key
			,submitValue: r.key
        },{
            xtype: r.xtype || 'textfield'
            ,name: 'value'
            ,fieldLabel: _('value')
            ,allowBlank: false
            ,value: r.value
        }]
    });
    MODx.window.UpdateContextSetting.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.UpdateContextSetting,MODx.Window);
Ext.reg('window-context-setting-update',MODx.window.UpdateContextSetting);