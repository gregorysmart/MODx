/**
 * @class MODx.panel.Snippet
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype panel-snippet
 */
MODx.panel.Snippet = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'element/snippet.php'
        ,baseParams: {}
        ,id: 'panel-snippet'
        ,class_key: 'modSnippet'
        ,plugin: ''
        ,bodyStyle: ''
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: {
            xtype: 'tabpanel'
            ,activeTab: 0
            ,deferredRender: false
            ,border: false
            ,defaults: {
                autoHeight: true
                ,layout: 'form'
                ,bodyStyle: 'padding: 1.5em;'
                ,labelWidth: 150
            }
            ,items: [{
                title: _('snippet_title')
                ,defaults: { border: false ,msgTarget: 'side' }
                ,items: [{
                    html: '<h2>'+_('snippet')+': '+config.name+'</h2>'
                    ,id: 'snippet-name'
                },{
                    html: '<p>'+_('snippet_msg')+'</p>'
                },{
                    xtype: 'hidden'
                    ,name: 'id'
                    ,value: config.snippet
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('snippet_name')
                    ,name: 'name'
                    ,width: 300
                    ,maxLength: 255
                    ,enableKeyEvents: true
                    ,allowBlank: false
                    ,listeners: {
                        'keyup': {scope:this,fn:function(f,e) {
                            Ext.getCmp('snippet-name').getEl().update('<h2>'+_('snippet')+': '+f.getValue()+'</h2>');
                        }}
                    }
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('snippet_desc')
                    ,name: 'description'
                    ,width: 300
                    ,maxLength: 255
                },{
                    xtype: 'combo-category'
                    ,fieldLabel: _('category')
                    ,name: 'category'
                    ,width: 250
                    ,value: config.category || null
                },{
                    xtype: 'checkbox'
                    ,fieldLabel: _('snippet_execonsave')
                    ,name: 'runsnippet'
                },{
                    xtype: 'checkbox'
                    ,fieldLabel: _('snippet_lock')
                    ,description: _('snippet_lock_msg')
                    ,name: 'locked'
                },{
                    html: onSnipFormRender
                    ,border: false
                },{
                    html: '<br />'+_('snippet_code')
                },{
                    xtype: 'textarea'
                    ,hideLabel: true
                    ,name: 'snippet'
                    ,width: '95%'
                    ,height: 400
                    ,value: "<?php\n\n?>"
                    
                }]
            },{
                title: _('snippet_properties')
                ,xtype: 'panel'
                ,layout: 'form'
                ,border: false
                ,items: [{
                    xtype: 'grid-snippet-properties'
                    ,snippet: config.snippet
                }]
            }]
        }
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    MODx.panel.Snippet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Snippet,MODx.FormPanel,{
    setup: function() {
        if (this.config.snippet === '' || this.config.snippet === 0) {       
            this.fireEvent('ready');
            return;
        }
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'get'
                ,id: this.config.snippet
            }
            ,listeners: {
            	'success': {fn:function(r) {
                    if (r.object.category == '0') { r.object.category = null; }
                    r.object.snippet = "<?php\n"+r.object.snippet+"\n?>";
                    this.getForm().setValues(r.object);
                    Ext.getCmp('snippet-name').getEl().update('<h2>'+_('snippet')+': '+r.object.name+'</h2>');
                    this.clearDirty();
                    this.fireEvent('ready',r.object);
                    
                    var d = Ext.decode(r.object.data);
                    Ext.getCmp('grid-snippet-properties').getStore().loadData(d);
                },scope:this}
            }
        });
    }
    ,beforeSubmit: function(o) {
        var g = Ext.getCmp('grid-snippet-properties');
        Ext.apply(o.form.baseParams,{
            properties: g.encode()
        });
        return true;
    }
    ,success: function(r) {
        Ext.getCmp('grid-snippet-properties').getStore().commitChanges();
    }
});
Ext.reg('panel-snippet',MODx.panel.Snippet);


/**
 * @class MODx.grid.SnippetProperties
 * @extends MODx.grid.LocalGrid
 * @param {Object} config An object of configuration properties
 * @xtype grid-snippet-properties
 */
MODx.grid.SnippetProperties = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('properties')
        ,id: 'grid-snippet-properties'
        ,autoHeight: true
        ,maxHeight: 300
        ,width: '90%'
        ,fields: ['name','description','xtype','options','value']
        ,columns: [{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 150
            ,editor: { xtype: 'textfield' ,allowBlank: false }
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,width: 200
            ,editor: { xtype: 'textfield' ,allowBlank: false }
        },{
            header: _('value')
            ,dataIndex: 'value'
            ,id: 'value'
            ,width: 250
            ,renderer: this.renderDynField.createDelegate(this,[this],true)
        },{
            header: _('type')
            ,dataIndex: 'xtype'
            ,width: 100
        }]
        ,tbar: [{
            text: _('property_create')
            ,handler: this.create
            ,scope: this
        }]
    });
    MODx.grid.SnippetProperties.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.SnippetProperties,MODx.grid.LocalProperty,{
    create: function(btn,e) {
        this.loadWindow(btn,e,{
            xtype: 'window-snippet-property-create'
            ,listeners: {
                'success': {fn:function(r) {
                    var rec = new this.propRecord({
                        name: r.name
                        ,value: r.value
                    });
                    this.getStore().add(rec);
                },scope:this}
            }
        });
    }    
    
    ,getMenu: function() {
        return [{
            text: _('property_remove')
            ,scope: this
            ,handler: this.remove.createDelegate(this,[{
                title: _('warning')
                ,text: _('property_remove_confirm')
            }])
        }];
    }
});
Ext.reg('grid-snippet-properties',MODx.grid.SnippetProperties);



/**
 * @class MODx.window.CreateSnippetProperty
 * @extends MODx.Window
 * @param {Object} config An object of configuration properties
 * @xtype window-snippet-property-create
 */
MODx.window.CreateSnippetProperty = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('property_create')
        ,height: 150
        ,width: 375
        ,fields: [{
            fieldLabel: _('name')
            ,name: 'name'
            ,xtype: 'textfield'
            ,width: 150
        },{
            fieldLabel: _('value')
            ,name: 'value'
            ,xtype: 'textfield'
            ,width: 150
        }]
    });
    MODx.window.CreateSnippetProperty.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateSnippetProperty,MODx.Window,{
    submit: function() {
        if (this.fp.getForm().isValid()) {
            if (this.fireEvent('success',this.fp.getForm().getValues())) {
                this.fp.getForm().reset();
                this.hide();
                return true;
            }
        }
        return false;
    }
});
Ext.reg('window-snippet-property-create',MODx.window.CreateSnippetProperty);