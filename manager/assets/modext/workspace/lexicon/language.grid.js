/**
 * Loads a grid for managing languages.
 * 
 * @class MODx.grid.Language
 * @extends MODx.grid.Grid
 * @param {Object} config An object of configuration properties
 * @xtype grid-language
 */
MODx.grid.Language = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('languages')
        ,url: MODx.config.connectors_url+'system/language.php'
        ,fields: ['id','name','menu']
        ,width: '97%'
        ,paging: true
        ,autosave: true
        ,primaryKey: 'name'
        ,columns: [{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 200
            ,sortable: true
        }]
        ,tbar: [{
            text: _('language_create')
            ,handler: { xtype: 'window-language-create' }
            ,scope: this
        }]
    });
    MODx.grid.Language.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.Language,MODx.grid.Grid);
Ext.reg('grid-language',MODx.grid.Language);

/**
 * Generates the create language window.
 *  
 * @class MODx.window.CreateLanguage
 * @extends MODx.Window
 * @constructor
 * @param {Object} config An object of options.
 * @xtype window-language-create
 */
MODx.window.CreateLanguage = function(config) {
    config = config || {};
    var r = config.record;
    Ext.applyIf(config,{
        title: _('language_create')
        ,url: MODx.config.connectors_url+'system/language.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'textfield'
            ,fieldLabel: _('name')
            ,name: 'name'
            ,width: 250
            ,maxLength: 100
        }]
    });
    MODx.window.CreateLanguage.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateLanguage,MODx.Window);
Ext.reg('window-language-create',MODx.window.CreateLanguage);
