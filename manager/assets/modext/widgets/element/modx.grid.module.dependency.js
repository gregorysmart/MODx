Ext.namespace('MODx','MODx.grid');
/**
 * Loads a grid of Module Dependencies.
 * 
 * @class MODx.grid.ModuleDep
 * @extends MODx.grid.Grid
 * @constructor
 * @param {Object} config An object of options.
 * @xtype grid-module-dep
 */
MODx.grid.ModuleDep = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('module_deps')
        ,url: MODx.config.connectors_url+'element/module_dependency.php'
        ,baseParams: {
            action: 'getList'
            ,module: config.module
        }
        ,fields: ['id','name','class_key','resource','menu']
        ,width: 800
        ,autosave: true
        ,paging: true
        ,remoteSort: true
        ,id: 'grid-module-dep'
        ,tbar: [{
            text: _('module_dep_add')
            ,handler: { 
                xtype: 'window-module-dep-create'
                ,blankValues: true
                ,module: config.module
            }
        }]
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 50
        },{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 350
            ,sortable: true
        },{
            header: _('class_key')
            ,dataIndex: 'class_key'
            ,width: 200
            ,sortable: true
            ,editor: { xtype: 'combo-class-key' }
            ,editable: false
        },{
            header: _('object_id')
            ,dataIndex: 'resource'
            ,width: 100
        }]
    });
    MODx.grid.ModuleDep.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.ModuleDep,MODx.grid.Grid,{

});
Ext.reg('grid-module-dep',MODx.grid.ModuleDep);


/**
 * Generates the create module dependency window.
 *  
 * @class MODx.window.CreateModuleDep
 * @extends MODx.Window
 * @constructor
 * @param {Object} config An object of options.
 * @xtype window-module-dep-create
 */
MODx.window.CreateModuleDep = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('module_dep_create')
        ,url: MODx.config.connectors_url+'element/module_dependency.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'combo-class-key'
            ,fieldLabel: _('class_key')
            ,description: _('class_key_desc')
            ,allowBlank: true
            ,listeners: {
                'change': {fn:this.updateObjField,scope:this}
            }
        },{
            xtype: 'combo-object'
            ,fieldLabel: _('object')
            ,description: _('object_id_desc')
            ,id: 'combo-object'
            ,name: 'object'
        },{
            xtype: 'hidden'
            ,name: 'module'
            ,value: config.module
        }]
    });
    MODx.window.CreateModuleDep.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateModuleDep,MODx.Window,{
    updateObjField: function(f,nv,ov) {
        var cbo = Ext.getCmp('combo-object');
        cbo.store.baseParams.class_key = f.getValue();
        cbo.store.reload({
            params: { start: 0, limit: 10 }
        });
        cbo.clearValue();
        
        cbo.reset();
    }
    ,submit: function() {
        MODx.window.CreateModuleDep.superclass.submit.call(this);
        Ext.getCmp('grid-module-dep').getStore().reload();
    }
});
Ext.reg('window-module-dep-create',MODx.window.CreateModuleDep);
