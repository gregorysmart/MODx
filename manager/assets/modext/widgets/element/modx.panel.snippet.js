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
        ,id: 'modx-panel-snippet'
        ,class_key: 'modSnippet'
        ,plugin: ''
        ,bodyStyle: ''
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+_('snippet_new')+'</h2>'
            ,id: 'modx-snippet-header'
            ,cls: 'modx-page-header'
            ,border: false
        },{
            xtype: 'portal'
            ,items: [{
                columnWidth: 1
                ,items: [{
                    title: _('snippet_title')
                    ,defaults: { border: false ,msgTarget: 'side' }
                    ,bodyStyle: 'padding: 1.5em;'
                    ,layout: 'form'
                    ,labelWidth: 150
                    ,items: [{
                        html: '<p>'+_('snippet_msg')+'</p>'
                    },{
                        xtype: 'hidden'
                        ,name: 'id'
                        ,id: 'modx-snippet-id'
                        ,value: config.snippet
                    },{
                        xtype: 'hidden'
                        ,name: 'props'
                        ,id: 'modx-snippet-props'
                        ,value: null
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('snippet_name')
                        ,name: 'name'
                        ,id: 'modx-snippet-name'
                        ,width: 300
                        ,maxLength: 255
                        ,enableKeyEvents: true
                        ,allowBlank: false
                        ,listeners: {
                            'keyup': {scope:this,fn:function(f,e) {
                                Ext.getCmp('modx-snippet-header').getEl().update('<h2>'+_('snippet')+': '+f.getValue()+'</h2>');
                            }}
                        }
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('snippet_desc')
                        ,name: 'description'
                        ,id: 'modx-snippet-description'
                        ,width: 300
                        ,maxLength: 255
                    },{
                        xtype: 'modx-combo-category'
                        ,fieldLabel: _('category')
                        ,name: 'category'
                        ,id: 'modx-snippet-category'
                        ,width: 250
                        ,value: config.category || null
                    },/*{
                        xtype: 'checkbox'
                        ,fieldLabel: _('snippet_execonsave')
                        ,name: 'runsnippet'
                        ,id: 'modx-snippet-runsnippet'
                    },*/{
                        xtype: 'checkbox'
                        ,fieldLabel: _('snippet_lock')
                        ,description: _('snippet_lock_msg')
                        ,name: 'locked'
                        ,id: 'modx-snippet-locked'
                    },{
                        html: onSnipFormRender
                        ,border: false
                    },{
                        html: '<br />'+_('snippet_code')
                    },{
                        xtype: 'textarea'
                        ,hideLabel: true
                        ,name: 'snippet'
                        ,id: 'modx-snippet-snippet'
                        ,width: '95%'
                        ,height: 400
                        ,value: "<?php\n\n?>"
                        
                    }]
                },{
                    xtype: 'modx-panel-element-properties'
                    ,elementPanel: 'modx-panel-snippet'
                    ,elementId: config.snippet
                    ,elementType: 'modSnippet'
                }]
            }]
        }]
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    MODx.panel.Snippet.superclass.constructor.call(this,config);
    Ext.getCmp('modx-element-tree-panel').expand();
};
Ext.extend(MODx.panel.Snippet,MODx.FormPanel,{
    initialized: false
    ,setup: function() {
        if (this.config.snippet === '' || this.config.snippet === 0 || this.initialized) {       
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
                    Ext.getCmp('modx-snippet-header').getEl().update('<h2>'+_('snippet')+': '+r.object.name+'</h2>');
                    this.clearDirty();
                    this.fireEvent('ready',r.object);
                    
                    var d = Ext.decode(r.object.data);
                    var g = Ext.getCmp('modx-grid-element-properties');
                    g.defaultProperties = d;
                    g.getStore().loadData(d);
                    this.initialized = true;
                },scope:this}
            }
        });
    }
    ,beforeSubmit: function(o) {
        return true;
    }
    ,success: function(r) {
        Ext.getCmp('modx-grid-element-properties').save();
        
        var t = parent.Ext.getCmp('modx_element_tree');
        var c = Ext.getCmp('modx-snippet-category').getValue();
        var u = c != '' && c != null ? 'n_snippet_category_'+c : 'n_type_snippet'; 
        t.refreshNode(u,true);
    }
});
Ext.reg('modx-panel-snippet',MODx.panel.Snippet);