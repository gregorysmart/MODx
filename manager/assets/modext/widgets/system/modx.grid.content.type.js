/**
 * @class MODx.panel.ContentType
 * @extends MODx.FormPanel
 * @param {Object} config An object of options.
 * @xtype modx-panel-content-type
 */
MODx.panel.ContentType = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-content-type'
        ,url: MODx.config.connectors_url+'system/contenttype.php'
        ,baseParams: {
            action: 'updateFromGrid'
        }
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+_('content_types')+'</h2>'
            ,cls: 'modx-page-header'
            ,border: false
        },{
            layout: 'form'
            ,bodyStyle: 'padding: 1.5em;'
            ,items: [{
                html: '<p>'+_('content_type_desc')+'</p>'
                ,border: false
            },{
                xtype: 'modx-grid-content-type'
                ,preventRender: true
            }]
        }]
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    MODx.panel.ContentType.superclass.constructor.call(this,config);  
};
Ext.extend(MODx.panel.ContentType,MODx.FormPanel,{
    initialized: false
    ,setup: function() {
        /* TODO: maybe eventually convert to local grid
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'get'
            }
            ,listeners: {
                'success': {fn:function(r) {
                    if (r.object.category == '0') { r.object.category = null; }
                    r.object.plugincode = "<?php\n"+r.object.plugincode+"\n?>";
                    this.getForm().setValues(r.object);
                    Ext.getCmp('plugin-header').getEl().update('<h2>'+_('plugin')+': '+r.object.name+'</h2>');
                    this.fireEvent('ready',r.object);
                    
                    var d = Ext.decode(r.object.data);
                    var g = Ext.getCmp('grid-element-properties');
                    g.defaultProperties = d;
                    g.getStore().loadData(d);
                    this.initialized = true;
                },scope:this}
            }
        });
        */
    }
    ,beforeSubmit: function(o) {
        var g = Ext.getCmp('modx-grid-content-type');
        Ext.apply(o.form.baseParams,{
            data: g.encodeModified()
        });
    }
    ,success: function(o) {
        Ext.getCmp('modx-grid-content-type').getStore().commitChanges();
    }
});
Ext.reg('modx-panel-content-type',MODx.panel.ContentType);

/**
 * Loads a grid of content types
 * 
 * @class MODx.grid.ContentType
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-contenttype
 */
MODx.grid.ContentType = function(config) {
    config = config || {};
    var binaryColumn = MODx.load({
        xtype: 'checkbox-column'
        ,header: _('binary')
        ,dataIndex: 'binary'
        ,width: 40
        ,sortable: true
    });

    Ext.applyIf(config,{
        id: 'modx-grid-content-type'
        ,url: MODx.config.connectors_url+'system/contenttype.php'
        ,fields: ['id','name','mime_type','file_extensions','headers','binary','description','menu']
        ,paging: true
        ,remoteSort: true
        ,plugins: binaryColumn
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 50
            ,sortable: true
        },{
            header: _('name')
            ,dataIndex: 'name'
            ,sortable: true
            ,editor: { xtype: 'textfield' }
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,editor: { xtype: 'textfield' }
            ,width: 200
        },{
            header: _('mime_type')
            ,dataIndex: 'mime_type'
            ,sortable: true
            ,editor: { xtype: 'textfield' }
            ,width: 80
        },{
            header: _('file_extensions')
            ,dataIndex: 'file_extensions'
            ,sortable: true
            ,editor: { xtype: 'textfield' }
        },binaryColumn]
        ,tbar: [{
            text: _('content_type_new')
            ,handler: { xtype: 'modx-window-content-type-create' ,blankValues: true }
        }]
    });
    MODx.grid.ContentType.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.ContentType,MODx.grid.Grid);
Ext.reg('modx-grid-content-type',MODx.grid.ContentType);


/** 
 * Generates the ContentType window.
 *  
 * @class MODx.window.ContentType
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-contenttype-create
 */
MODx.window.CreateContentType = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('content_type_new')
        ,width: 350
        ,url: MODx.config.connectors_url+'system/contenttype.php'
        ,action: 'create'
        ,fields: [{
            fieldLabel: _('name')
            ,name: 'name'
            ,id: 'modx-cct-name'
            ,xtype: 'textfield'
            ,width: 200
            ,allowBlank: false
        },{
            fieldLabel: _('mime_type')
            ,name: 'mime_type'
            ,id: 'modx-cct-mime-type'
            ,xtype: 'textfield'
            ,description: _('mime_type_desc')
            ,width: 200
            ,allowBlank: false
        },{
            fieldLabel: _('file_extensions')
            ,name: 'file_extensions'
            ,id: 'modx-cct-file-extensions'
            ,xtype: 'textfield'
            ,description: _('file_extensions_desc')
            ,width: 200
            ,allowBlank: false
        },{
            xtype: 'combo-boolean'
            ,fieldLabel: _('binary')
            ,name: 'binary'
            ,id: 'modx-cct-binary'
            ,description: _('binary_desc')
            ,width: 60
            ,value: 0
        },{
            fieldLabel: _('description')
            ,name: 'description'
            ,id: 'modx-cct-description'
            ,xtype: 'textarea'
            ,width: 200
            ,grow: true
        }]
    });
    MODx.window.CreateContentType.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateContentType,MODx.Window);
Ext.reg('modx-window-content-type-create',MODx.window.CreateContentType);