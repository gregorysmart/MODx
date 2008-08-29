/**
 * Loads a grid of Provisioners.
 * 
 * @class MODx.grid.Provisioner
 * @extends MODx.grid.Grid
 * @constructor
 * @param {Object} config An object of options.
 * @xtype grid-provisioner
 */
MODx.grid.Provisioner = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('packages')
        ,url: MODx.config.connectors_url+'workspace/providers.php'
        ,fields: ['id','name','description','service_url','menu']
        ,paging: true
        ,autosave: true
        ,columns: [{
            header: _('name')
            ,dataIndex: 'name'
            ,editor: { xtype: 'textfield' ,allowBlank: false }
        },{
            header: _('service_url')
            ,dataIndex: 'service_url'
            ,width: 200
            ,editor: { xtype: 'textfield' ,allowBlank: false ,vtype: 'url' }
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,width: 300
            ,editor: { xtype: 'textarea' }
        }]
        ,tbar: [{
            text: _('provider_add')
            ,handler: { xtype: 'window-provider-create' }
        }]
    });
    MODx.grid.Provisioner.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.Provisioner,MODx.grid.Grid);
Ext.reg('grid-provisioner',MODx.grid.Provisioner);

/** 
 * Generates the Create Provider window.
 *  
 * @class MODx.window.CreateProvider
 * @extends MODx.Window
 * @constructor
 * @param {Object} config An object of options.
 * @xtype window-provider-create
 */
MODx.window.CreateProvider = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('provider_add')
        ,width: 375
        ,connector: MODx.config.connectors_url+'workspace/providers.php'
        ,action: 'create'
        ,fields: [{
            fieldLabel: _('name')
            ,name: 'name'
            ,xtype: 'textfield'
            ,width: 150
            ,allowBlank: false
        },{
            fieldLabel: _('service_url')
            ,name: 'service_url'
            ,xtype: 'textfield'
            ,width: 200
            ,allowBlank: false
        },{
            fieldLabel: _('description')
            ,name: 'description'
            ,xtype: 'textarea'
            ,width: 200
            ,grow: true
        }]
    });
    MODx.window.CreateProvider.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateProvider,MODx.Window);
Ext.reg('window-provider-create',MODx.window.CreateProvider);

/** 
 * Generates the Update Provider window.
 *  
 * @class MODx.window.UpdateProvider
 * @extends MODx.Window
 * @constructor
 * @param {Object} config An object of options.
 * @xtype window-provider-update
 */
MODx.window.UpdateProvider = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('provider_update')
        ,width: 375
        ,connector: MODx.config.connectors_url+'workspace/providers.php'
        ,action: 'update'
        ,fields: [{
            name: 'id'
            ,xtype: 'hidden'
        },{
            fieldLabel: _('name')
            ,name: 'name'
            ,xtype: 'textfield'
            ,width: 150
            ,allowBlank: false
        },{
            fieldLabel: _('service_url')
            ,name: 'service_url'
            ,xtype: 'textfield'
            ,width: 200
            ,allowBlank: false
        },{
            fieldLabel: _('description')
            ,name: 'description'
            ,xtype: 'textarea'
            ,width: 200
            ,grow: true
        }]
    });
    MODx.window.UpdateProvider.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.UpdateProvider,MODx.Window);
Ext.reg('window-provider-update',MODx.window.UpdateProvider);
